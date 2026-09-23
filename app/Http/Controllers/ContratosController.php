<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use Inertia\Inertia;

class ContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $idioma = inertia()->getShared('idioma');

        $contratos = Contrato::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
            ])
            ->with([
                'contratosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                    })
                        ->orderBy('idioma_id', 'DESC');
                },
                'imagens' => function ($q) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                        ->orderBy('ordem', 'ASC')
                        ->orderBy('id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'imagens' => $item->imagens->map(function ($img) {
                        return [
                            'id' => $img->id,
                            'imagem' => rafator('content/contracts/gallery/' . $img->imagem),
                        ];
                    }),
                    'titulo' => $item->contratosIdiomas[0]->titulo,
                    'subtitulo' => $item->contratosIdiomas[0]->subtitulo,
                    'texto' => $item->contratosIdiomas[0]->texto,
                    'banner' => rafator('content/contracts/thumbs/' . $item->imagem),
                ];
            });

        return Inertia::render('Contratos', [
            'contratos' => $contratos
        ]);
    }
}
