<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\PostCollectionRequest;
use App\Models\Ambiente;
use App\Models\Colecao;
use App\Models\ColecaoIdioma;
use App\Models\Idioma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ColecoesController extends Controller
{
    public function index($id)
    {
        $ambiente = Ambiente::query()
            ->where(['excluido' => NULL, 'id' => $id])
            ->with([
                'ambientesIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->where('padrao', true);
                    });
                },
                'colecoes' => function ($q) {
                    $q->where(['excluido' => NULL])
                        ->with(['colecoesIdiomas' => function ($r) {
                            $r->whereHas('idiomas', function ($i) {
                                $i->where('padrao', true);
                            });
                        }])
                        ->orderBy('ordem', 'ASC')
                        ->orderBy('id', 'DESC');
                },
            ])->first();

        if (!$ambiente) return Inertia::location(route('Manager.Ambientes.index'));

        return Inertia::render('Manager/Ambientes/Colecoes/index', [
            'ambiente' => [
                'id' => $ambiente->id,
                'nome' => $ambiente->ambientesIdiomas[0]->nome,
                'colecoes' => $ambiente->colecoes->map(function ($colecao) {
                    return [
                        'id' => $colecao->id,
                        'visivel' => $colecao->visivel ? true : false,
                        'nome' => $colecao->colecoesIdiomas[0]->nome,
                    ];
                }),
            ],
        ]);
    }

    public function adicionar($id)
    {
        return Inertia::render('Manager/Ambientes/Colecoes/adicionar', ['id' => $id]);
    }

    public function novo(PostCollectionRequest $request, $id)
    {
        $colecao = new Colecao;
        $colecao->ambiente_id = $id;
        $colecao->visivel = true;
        $colecao->save();

        $colecao_idioma = new ColecaoIdioma;
        $colecao_idioma->nome = $request->nome;
        $colecao_idioma->descricao_curta = $request->descricao_curta;
        $colecao_idioma->descricao = $request->descricao;
        $colecao_idioma->colecao_id = $colecao->id;
        $colecao_idioma->idioma_id = inertia()->getShared('idioma')->id;
        $colecao_idioma->save();

        return to_route('Manager.Ambientes.Colecoes.index', ['id' => $id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function editar($id)
    {
        $idioma = request('lang');
        $colecao = Colecao::query()->where(['excluido' => NULL, 'id' => $id])->with(['colecoesIdiomas' => function ($q) use ($idioma) {
            $q->when($idioma, function ($r) use ($idioma) {
                $r->whereHas('idiomas', function ($query) use ($idioma) { $query->where('codigo', $idioma); });
            })->when(!$idioma, function ($r) {
                $r->whereHas('idiomas', function ($query) { $query->where('padrao', true); });
            });
        }])->first();
        if (!$colecao) return Inertia::location(route('Manager.Ambientes.index'));
        return Inertia::render('Manager/Ambientes/Colecoes/editar', ['colecao' => [
            'id' => $colecao->id,
            'ambiente_id' => $colecao->ambiente_id,
            'nome' => $colecao->colecoesIdiomas->isNotEmpty() ? $colecao->colecoesIdiomas[0]->nome : null,
            'descricao_curta' => $colecao->colecoesIdiomas->isNotEmpty() ? $colecao->colecoesIdiomas[0]->descricao_curta : null,
            'descricao' => $colecao->colecoesIdiomas->isNotEmpty() ? $colecao->colecoesIdiomas[0]->descricao : null,
        ]]);
    }

    public function atualizar(PostCollectionRequest $request, $id)
    {
        $colecao = Colecao::query()->where(['excluido' => NULL, 'id' => $id])->first();
        if (!$colecao) return Inertia::location(route('Manager.Ambientes.index'));
        $codigo = $request->query('lang');
        $traducao = ColecaoIdioma::query()->where(['excluido' => NULL, 'colecao_id' => $id])
            ->when($codigo, function ($q) use ($codigo) { $q->whereHas('idiomas', function ($i) use ($codigo) { $i->where('codigo', $codigo); }); })
            ->when(!$codigo, function ($q) { $q->whereHas('idiomas', function ($i) { $i->where('padrao', true); }); })->first();
        if (!$traducao) {
            $traducao = new ColecaoIdioma;
            $traducao->colecao_id = $id;
            $traducao->idioma_id = Idioma::query()->when($codigo, function ($q) use ($codigo) { $q->where('codigo', $codigo); })->when(!$codigo, function ($q) { $q->where('padrao', true); })->value('id');
        }
        $traducao->nome = $request->nome;
        $traducao->descricao_curta = $request->descricao_curta;
        $traducao->descricao = $request->descricao;
        $traducao->save();
        return to_route('Manager.Ambientes.Colecoes.index', ['id' => $colecao->ambiente_id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function excluir(Request $request, $id) { return $this->change($id, 'delete'); }
    public function visibilidade(Request $request, $id) { return $this->change($id, 'visibility'); }
    public function ordenar(Request $request) {
        foreach ($request->odr ?? [] as $item) Colecao::query()->where(['excluido' => NULL, 'id' => $item['id']])->update(['ordem' => $item['ordem']]);
        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registros reordenados com sucesso!']);
    }
    private function change($id, $action) {
        $colecao = Colecao::query()->where(['excluido' => NULL, 'id' => $id])->first();
        if (!$colecao) return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        if ($action === 'delete') $colecao->excluido = Carbon::now(); else $colecao->visivel = 1 - $colecao->visivel;
        $colecao->save();
        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registro alterado com sucesso!']);
    }
}
