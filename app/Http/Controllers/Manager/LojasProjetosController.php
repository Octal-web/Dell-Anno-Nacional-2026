<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\PostStoreProjectRequest;
use App\Models\Idioma;
use App\Models\Loja;
use App\Models\ProjetoLoja;
use App\Models\ProjetoLojaIdioma;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LojasProjetosController extends Controller
{
    public function index()
    {
        $projetos = ProjetoLoja::query()
            ->where(['excluido' => NULL])
            ->with(['projetosLojasIdiomas' => function ($q) {
                $q->where(['excluido' => NULL])->whereHas('idiomas', function ($r) {
                    $r->where('padrao', true);
                })->orderByDesc('idioma_id');
            }])
            ->orderBy('ordem')
            ->orderByDesc('id')
            ->get()
            ->map(function ($projeto) {
                return [
                    'id' => $projeto->id,
                    'visivel' => (bool) $projeto->visivel,
                    'imagem' => rafator('content/stores/projects/thumbs/' . $projeto->imagem),
                    'nome' => $projeto->projetosLojasIdiomas->isNotEmpty() ? $projeto->projetosLojasIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Manager/Lojas/Projetos/index', [
            'projetos' => $projetos,
        ]);
    }

    public function adicionar()
    {
        return Inertia::render('Manager/Lojas/Projetos/adicionar', [
            'lojas' => $this->lojasOptions(),
        ]);
    }

    public function novo(PostStoreProjectRequest $request)
    {
        $slugBase = Str::slug($request->nome);
        $slug = $slugBase;
        $count = 1;
        while (ProjetoLoja::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $count++;
        }

        $projeto = new ProjetoLoja;
        $projeto->slug = $slug;
        $projeto->loja_id = $request->loja_id;
        $projeto->visivel = true;
        $projeto->save();

        $traducao = new ProjetoLojaIdioma;
        $traducao->nome = $request->nome;
        $traducao->creditos = $request->creditos;
        $traducao->conteudo = $request->conteudo;
        $traducao->titulo_pagina = $request->titulo_pagina;
        $traducao->descricao_pagina = $request->descricao_pagina;
        $traducao->projeto_loja_id = $projeto->id;
        $traducao->idioma_id = inertia()->getShared('idioma')->id;
        $traducao->save();

        return to_route('Manager.Lojas.Projetos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function editar($id)
    {
        $codigo = request('lang');
        $projeto = ProjetoLoja::query()
            ->where(['excluido' => NULL])
            ->whereKey($id)
            ->with(['projetosLojasIdiomas' => function ($q) use ($codigo) {
                $q->where(['excluido' => NULL])
                    ->when($codigo, fn ($r) => $r->whereHas('idiomas', fn ($i) => $i->where('codigo', $codigo)))
                    ->when(!$codigo, fn ($r) => $r->whereHas('idiomas', fn ($i) => $i->where('padrao', true)));
            }])
            ->first();

        if (!$projeto) {
            return Inertia::location(route('Manager.Lojas.Projetos.index'));
        }

        $traducao = $projeto->projetosLojasIdiomas->first();
        return Inertia::render('Manager/Lojas/Projetos/editar', [
            'projeto' => [
                'id' => $projeto->id,
                'loja_id' => $projeto->loja_id,
                'nome' => optional($traducao)->nome,
                'creditos' => optional($traducao)->creditos,
                'conteudo' => optional($traducao)->conteudo,
                'titulo_pagina' => optional($traducao)->titulo_pagina,
                'descricao_pagina' => optional($traducao)->descricao_pagina,
            ],
            'lojas' => $this->lojasOptions(),
        ]);
    }

    public function atualizar(PostStoreProjectRequest $request, $id)
    {
        $projeto = ProjetoLoja::query()->where(['excluido' => NULL, 'id' => $id])->first();
        if (!$projeto) {
            return to_route('Manager.Lojas.Projetos.index')->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        }

        $codigo = $request->query('lang');
        $traducao = ProjetoLojaIdioma::query()
            ->where([
                'excluido' => NULL,
                'projeto_loja_id' => $id,
            ])
            ->when($codigo, fn ($q) => $q->whereHas('idiomas', fn ($i) => $i->where('codigo', $codigo)))
            ->when(!$codigo, fn ($q) => $q->whereHas('idiomas', fn ($i) => $i->where('padrao', true)))
            ->first();
        $idiomaId = Idioma::query()
            ->when($codigo, fn ($q) => $q->where('codigo', $codigo))
            ->when(!$codigo, fn ($q) => $q->where('padrao', true))
            ->value('id');

        if (!$idiomaId) {
            return to_route('Manager.Lojas.Projetos.index')->with('message', ['type' => 'error', 'msg' => 'Idioma inválido.']);
        }
        if (!$traducao) {
            $traducao = new ProjetoLojaIdioma;
            $traducao->projeto_loja_id = $id;
            $traducao->idioma_id = $idiomaId;
        }

        if (!$codigo && $request->nome !== $traducao->nome) {
            $slugBase = Str::slug($request->nome);
            $slug = $slugBase;
            $count = 1;
            while (ProjetoLoja::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slugBase . '-' . $count++;
            }
            $projeto->slug = $slug;
        }

        $traducao->nome = $request->nome;
        $traducao->creditos = $request->creditos;
        $traducao->conteudo = $request->conteudo;
        $traducao->titulo_pagina = $request->titulo_pagina;
        $traducao->descricao_pagina = $request->descricao_pagina;
        $projeto->loja_id = $request->loja_id;
        $projeto->save();
        $traducao->save();

        return to_route('Manager.Lojas.Projetos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function excluir(Request $request, $id)
    {
        $ok = ProjetoLoja::query()->where(['excluido' => NULL, 'id' => $id])->update(['excluido' => Carbon::now()]);
        return redirect()->back()->with('message', $ok
            ? ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.']
            : ['type' => 'error', 'msg' => 'Não foi possível excluir o registro.']);
    }

    public function visibilidade(Request $request, $id)
    {
        $projeto = ProjetoLoja::query()->where(['excluido' => NULL, 'id' => $id])->first();
        if (!$projeto) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        }
        $projeto->visivel = !$projeto->visivel;
        $projeto->save();
        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Visibilidade alterada com sucesso!']);
    }

    public function ordenar(Request $request)
    {
        foreach ($request->input('odr', []) as $item) {
            ProjetoLoja::query()->where(['excluido' => NULL, 'id' => $item['id']])->update(['ordem' => $item['ordem']]);
        }
        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registros reordenados com sucesso!']);
    }

    public function baixarVideo($id)
    {
        if (!$id) {
            return redirect()->route('Manager.Lojas.Projetos.index');
        }

        $projeto = ProjetoLoja::query()
            ->where([
                'id' => $id,
                'excluido' => NULL,
            ])
            ->first();

        if (!$projeto) {
            return redirect()->route('Manager.Lojas.Projetos.index');
        }

        $caminho = public_path('content/stores/projects/video/' . $projeto->video);

        if (!File::exists($caminho)) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Não foi possível encontrar o arquivo!']);
        }

        $extensao = pathinfo($caminho)['extension'];

        return response()->download($caminho, $projeto->slug . '.' . $extensao);
    }

    private function lojasOptions()
    {
        return Loja::query()
            ->whereNull('excluido')
            ->where('visivel', true)
            ->with([
                'pais',
                'lojasIdiomas' => fn ($q) => $q
                    ->whereHas('idiomas', fn ($r) => $r->where('padrao', true))
                    ->orderByDesc('idioma_id'),
            ])
            ->orderBy('ordem')
            ->orderByDesc('id')
            ->get()
            ->map(fn ($loja) => [
                'pais' => $loja->pais ? $loja->pais->name : 'Sem país',
                'value' => $loja->id,
                'label' => $loja->lojasIdiomas->isNotEmpty()
                    ? $loja->lojasIdiomas[0]->cidade . ' - ' . $loja->lojasIdiomas[0]->estado
                    : null,
            ])
            ->groupBy('pais')
            ->map(fn ($lojas, $pais) => [
                'label' => $pais,
                'options' => $lojas->values(),
            ])
            ->values()
            ->prepend([
                'label' => '',
                'options' => [
                    ['value' => null, 'label' => 'Nenhuma'],
                ],
            ]);
    }
}
