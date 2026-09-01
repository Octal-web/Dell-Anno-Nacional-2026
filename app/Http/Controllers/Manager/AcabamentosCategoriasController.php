<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\PostFinishCategoryRequest;
use App\Models\AcabamentoCategoria;
use App\Models\AcabamentoCategoriaIdioma;
use App\Models\Idioma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AcabamentosCategoriasController extends Controller
{
    public function adicionar()
    {
        return Inertia::render('Manager/Acabamentos/Categorias/adicionar');
    }

    public function novo(PostFinishCategoryRequest $request)
    {
        $categoria = new AcabamentoCategoria;
        $categoria->visivel = true;
        $categoria->save();
        $traducao = new AcabamentoCategoriaIdioma;
        $traducao->nome = $request->nome;
        $traducao->categoria_id = $categoria->id;
        $traducao->idioma_id = inertia()->getShared('idioma')->id;
        $traducao->save();
        return to_route('Manager.Acabamentos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function editar($id)
    {
        $codigo = request('lang');
        $categoria = AcabamentoCategoria::query()->where(['excluido' => NULL, 'id' => $id])->with([
            'acabamentosCategoriasIdiomas' => fn ($q) => $q
                ->when($codigo, fn ($r) => $r->whereHas('idiomas', fn ($i) => $i->where('codigo', $codigo)))
                ->when(!$codigo, fn ($r) => $r->whereHas('idiomas', fn ($i) => $i->where('padrao', true))),
        ])->first();
        if (!$categoria) return Inertia::location(route('Manager.Acabamentos.index'));
        return Inertia::render('Manager/Acabamentos/Categorias/editar', ['categoria' => [
            'id' => $categoria->id, 'nome' => optional($categoria->acabamentosCategoriasIdiomas->first())->nome,
        ]]);
    }

    public function atualizar(PostFinishCategoryRequest $request, $id)
    {
        $categoria = AcabamentoCategoria::query()->where(['excluido' => NULL, 'id' => $id])->first();
        if (!$categoria) return to_route('Manager.Acabamentos.index')->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        $codigo = $request->query('lang');
        $traducao = AcabamentoCategoriaIdioma::query()->where(['excluido' => NULL, 'categoria_id' => $id])
            ->when($codigo, fn ($q) => $q->whereHas('idiomas', fn ($i) => $i->where('codigo', $codigo)))
            ->when(!$codigo, fn ($q) => $q->whereHas('idiomas', fn ($i) => $i->where('padrao', true)))->first();
        $idiomaId = Idioma::query()->when($codigo, fn ($q) => $q->where('codigo', $codigo))->when(!$codigo, fn ($q) => $q->where('padrao', true))->value('id');
        if (!$idiomaId) return to_route('Manager.Acabamentos.index')->with('message', ['type' => 'error', 'msg' => 'Idioma inválido.']);
        if (!$traducao) {
            $traducao = new AcabamentoCategoriaIdioma;
            $traducao->categoria_id = $id;
            $traducao->idioma_id = $idiomaId;
        }
        $traducao->nome = $request->nome;
        $traducao->save();
        return to_route('Manager.Acabamentos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function excluir(Request $request, $id)
    {
        $categoria = AcabamentoCategoria::query()->where(['excluido' => NULL, 'id' => $id])->first();
        if (!$categoria) return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        if ($categoria->acabamentos()->where(['excluido' => NULL])->exists()) return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'A categoria possui acabamentos e não pode ser excluída.']);
        $categoria->excluido = Carbon::now();
        $categoria->save();
        return redirect()->back()->with('message', ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.']);
    }

    public function visibilidade(Request $request, $id)
    {
        $item = AcabamentoCategoria::query()->where(['excluido' => NULL, 'id' => $id])->first();
        if (!$item) return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        $item->visivel = !$item->visivel;
        $item->save();
        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Visibilidade alterada com sucesso!']);
    }

    public function ordenar(Request $request)
    {
        foreach ($request->input('odr', []) as $item) AcabamentoCategoria::query()->where(['excluido' => NULL, 'id' => $item['id']])->update(['ordem' => $item['ordem']]);
        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registros reordenados com sucesso!']);
    }
}
