<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\PostFinishRequest;
use App\Models\Acabamento;
use App\Models\AcabamentoCategoria;
use App\Models\AcabamentoIdioma;
use App\Models\Idioma;
use App\Services\ImageCompressor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class AcabamentosController extends Controller
{
    public function index()
    {
        $idioma = inertia()->getShared('idioma');
        $traducao = fn ($q) => $q->whereHas('idiomas', fn ($r) => $r->where('codigo', $idioma->codigo)->orWhere('padrao', true))->orderByDesc('idioma_id');

        $acabamentos = Acabamento::query()->where(['excluido' => NULL])->with(['acabamentosIdiomas' => $traducao])
            ->orderBy('ordem')->orderByDesc('id')->get()->map(fn ($item) => [
                'id' => $item->id, 'visivel' => (bool) $item->visivel,
                'imagem' => rafator('content/finishes/' . $item->imagem),
                'nome' => optional($item->acabamentosIdiomas->first())->nome,
            ]);
        $categorias = AcabamentoCategoria::query()->where(['excluido' => NULL])->with(['acabamentosCategoriasIdiomas' => $traducao])
            ->orderBy('ordem')->orderByDesc('id')->get()->map(fn ($item) => [
                'id' => $item->id, 'visivel' => (bool) $item->visivel,
                'nome' => optional($item->acabamentosCategoriasIdiomas->first())->nome,
            ]);

        return Inertia::render('Manager/Acabamentos/index', compact('acabamentos', 'categorias'));
    }

    public function adicionar()
    {
        $categorias = AcabamentoCategoria::query()
            ->where(['excluido' => NULL])
            ->with(['acabamentosCategoriasIdiomas' => function ($q) {
                $q->whereHas('idiomas', function ($r) {
                    $r->where('padrao', true);
                });
            }])
            ->orderBy('ordem')
            ->get()
            ->map(function ($categoria) {
                return [
                    'value' => $categoria->id,
                    'label' => $categoria->acabamentosCategoriasIdiomas[0]->nome,
                ];
            });

        return Inertia::render('Manager/Acabamentos/adicionar', [
            'categorias' => $categorias,
        ]);
    }

    public function novo(PostFinishRequest $request, ImageCompressor $compressor)
    {
        $acabamento = new Acabamento;
        $acabamento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
        $acabamento->categoria_id = $request->integer('categoria_id');
        $acabamento->visivel = true;
        $acabamento->save();

        $traducao = new AcabamentoIdioma;
        $traducao->nome = $request->nome;
        $traducao->acabamento_id = $acabamento->id;
        $traducao->idioma_id = inertia()->getShared('idioma')->id;
        $traducao->save();
        $compressor->compressOrFallback($request->file('img')->getRealPath(), public_path('content/finishes/' . $acabamento->imagem));

        return to_route('Manager.Acabamentos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function editar($id)
    {
        $codigo = request('lang');
        $acabamento = Acabamento::query()->where(['excluido' => NULL, 'id' => $id])->with([
            'acabamentosIdiomas' => fn ($q) => $q
                ->when($codigo, fn ($r) => $r->whereHas('idiomas', fn ($i) => $i->where('codigo', $codigo)))
                ->when(!$codigo, fn ($r) => $r->whereHas('idiomas', fn ($i) => $i->where('padrao', true))),
        ])->first();
        if (!$acabamento) return Inertia::location(route('Manager.Acabamentos.index'));

        $idioma = inertia()->getShared('idioma');
        $categorias = AcabamentoCategoria::query()
            ->where(['excluido' => NULL])
            ->with(['acabamentosCategoriasIdiomas' => function ($q) use ($idioma) {
                $q->whereHas('idiomas', function ($r) use ($idioma) {
                    $r->where('codigo', $idioma->codigo)->orWhere('padrao', true);
                })->orderByDesc('idioma_id');
            }])
            ->orderBy('ordem')
            ->get()
            ->map(function ($categoria) {
                return [
                    'value' => $categoria->id,
                    'label' => $categoria->acabamentosCategoriasIdiomas[0]->nome,
                ];
            });

        return Inertia::render('Manager/Acabamentos/editar', [
            'acabamento' => ['id' => $acabamento->id, 'nome' => optional($acabamento->acabamentosIdiomas->first())->nome,
                'categoria_id' => $acabamento->categoria_id, 'imagem' => rafator('content/finishes/' . $acabamento->imagem)],
            'categorias' => $categorias,
        ]);
    }

    public function atualizar(PostFinishRequest $request, $id, ImageCompressor $compressor)
    {
        $acabamento = Acabamento::query()->where(['excluido' => NULL, 'id' => $id])->first();
        if (!$acabamento) return to_route('Manager.Acabamentos.index')->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);

        $codigo = $request->query('lang');
        $traducao = AcabamentoIdioma::query()->where(['excluido' => NULL, 'acabamento_id' => $id])
            ->when($codigo, fn ($q) => $q->whereHas('idiomas', fn ($i) => $i->where('codigo', $codigo)))
            ->when(!$codigo, fn ($q) => $q->whereHas('idiomas', fn ($i) => $i->where('padrao', true)))->first();
        $idiomaId = Idioma::query()->when($codigo, fn ($q) => $q->where('codigo', $codigo))->when(!$codigo, fn ($q) => $q->where('padrao', true))->value('id');
        if (!$idiomaId) return to_route('Manager.Acabamentos.index')->with('message', ['type' => 'error', 'msg' => 'Idioma inválido.']);
        if (!$traducao) {
            $traducao = new AcabamentoIdioma;
            $traducao->acabamento_id = $id;
            $traducao->idioma_id = $idiomaId;
        }

        $imagemAnterior = $acabamento->imagem;
        if ($request->hasFile('img')) $acabamento->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
        $acabamento->categoria_id = $request->integer('categoria_id');
        $traducao->nome = $request->nome;
        $acabamento->save();
        $traducao->save();
        if ($request->hasFile('img')) {
            $compressor->compressOrFallback($request->file('img')->getRealPath(), public_path('content/finishes/' . $acabamento->imagem));
            File::delete(public_path('content/finishes/' . $imagemAnterior));
        }
        return to_route('Manager.Acabamentos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function excluir(Request $request, $id)
    {
        $ok = Acabamento::query()->where(['excluido' => NULL, 'id' => $id])->update(['excluido' => Carbon::now()]);
        return redirect()->back()->with('message', $ok ? ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.'] : ['type' => 'error', 'msg' => 'Não foi possível excluir o registro.']);
    }

    public function visibilidade(Request $request, $id)
    {
        $item = Acabamento::query()->where(['excluido' => NULL, 'id' => $id])->first();
        if (!$item) return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        $item->visivel = !$item->visivel;
        $item->save();
        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Visibilidade alterada com sucesso!']);
    }

    public function ordenar(Request $request)
    {
        foreach ($request->input('odr', []) as $item) Acabamento::query()->where(['excluido' => NULL, 'id' => $item['id']])->update(['ordem' => $item['ordem']]);
        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registros reordenados com sucesso!']);
    }

}
