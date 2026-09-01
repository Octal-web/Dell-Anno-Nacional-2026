<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\AcabamentoCategoria;

class AcabamentosController extends Controller
{
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $categorias = AcabamentoCategoria::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'acabamentosCategoriasIdiomas' => function ($q) use ($idioma) {
                    $q->where(['excluido' => NULL])
                    ->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                },
                'acabamentos' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true
                    ])
                    ->with(['acabamentosIdiomas' => function ($q) use ($idioma) {
                        $q->where(['excluido' => NULL])
                        ->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    }])
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($categoria) {
                return [
                    'id' => $categoria->id,
                    'nome' => $categoria->acabamentosCategoriasIdiomas->isNotEmpty() ? $categoria->acabamentosCategoriasIdiomas[0]->nome : null,
                    'acabamentos' => $categoria->acabamentos->map(function($acabamento) {
                        return [
                            'id' => $acabamento->id,
                            'imagem' => rafator('content/finishes/' . $acabamento->imagem),
                            'nome' => $acabamento->acabamentosIdiomas->isNotEmpty() ? $acabamento->acabamentosIdiomas[0]->nome : null,
                        ];
                    }),
                ];
            });

        return Inertia::render('Acabamentos', [
            'categorias' => $categorias
        ]);
    }
};
