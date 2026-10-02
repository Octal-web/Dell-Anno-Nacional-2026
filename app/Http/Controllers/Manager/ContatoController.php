<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ProjetoContato;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

use Carbon\Carbon;

class ContatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $projetos = ProjetoContato::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'projetosContatosIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($projeto) {
                return [
                    'id' => $projeto->id,
                    'visivel' => $projeto->visivel,
                    'imagem' => rafator('content/contact-projects/thumbs/' . $projeto->imagem),
                    'titulo' => $projeto->projetosContatosIdiomas->isNotEmpty() && $projeto->projetosContatosIdiomas[0]->descricao
                        ? Str::limit($projeto->projetosContatosIdiomas[0]->descricao, 40)
                        : 'Projeto #' . $projeto->id,
                ];
            });

        return Inertia::render('Manager/Contato/index', [
            'projetos' => $projetos,
        ]);
    }
}
