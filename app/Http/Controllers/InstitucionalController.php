<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Models\Acontecimento;
use App\Models\Etapa;

class InstitucionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idioma = inertia()->getShared('idioma');

        $acontecimentos = Acontecimento::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'acontecimentosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                    })
                        ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($acontecimento) {
                return [
                    'id' => $acontecimento->id,
                    'ano' => $acontecimento->ano,
                    'imagem' => rafator('content/timeline/thumbs/' . $acontecimento->imagem),
                    'descricao' => $acontecimento->acontecimentosIdiomas->isNotEmpty() ? $acontecimento->acontecimentosIdiomas[0]->descricao : null,
                ];
            });

        $etapas = Etapa::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'etapasIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                    })
                        ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($etapa) {
                return [
                    'id' => $etapa->id,
                    'imagem' => rafator('content/steps/thumbs/' . $etapa->imagem),
                    'titulo' => $etapa->etapasIdiomas->isNotEmpty() ? $etapa->etapasIdiomas[0]->titulo : null,
                    'descricao' => $etapa->etapasIdiomas->isNotEmpty() ? $etapa->etapasIdiomas[0]->descricao : null,
                ];
            });

        return Inertia::render('Institucional', [
            'acontecimentos' => $acontecimentos,
            'etapas' => $etapas,
        ]);
    }
};
