<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Conteudo;
use App\Models\Pagina;
use Inertia\Inertia;

class ProdutosController extends Controller
{
    public function index()
    {
        $idioma = inertia()->getShared('idioma');
        $ambientes = Ambiente::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
            ])
            ->with(['ambientesIdiomas' => function ($q) use ($idioma) {
                $q->whereHas('idiomas', function ($r) use ($idioma) {
                    $r->where('codigo', $idioma)
                        ->orWhere('padrao', true);
                })
                    ->orderBy('idioma_id', 'DESC');
            }])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($ambiente) {
                return [
                    'id' => $ambiente->id,
                    'slug' => $ambiente->slug,
                    'imagem' => rafator('content/products/thumbs/' . $ambiente->imagem),
                    'nome' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->nome : null,
                    'descricao' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->descricao : null,
                ];
            });

        return Inertia::render('Produtos', [
            'produtos' => $ambientes,
        ]);
    }

    public function produto($slug = null)
    {
        $idioma = inertia()->getShared('idioma');
        $ambiente = Ambiente::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
                'slug' => $slug,
            ])
            ->with([
                'ambientesIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                            ->orWhere('padrao', true);
                    })
                        ->orderBy('idioma_id', 'DESC');
                },
                'colecoes' => function ($q) use ($idioma) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true,
                    ])
                        ->with([
                            'colecoesIdiomas' => function ($query) use ($idioma) {
                                $query->whereHas('idiomas', function ($r) use ($idioma) {
                                    $r->where('codigo', $idioma)
                                        ->orWhere('padrao', true);
                                })
                                    ->orderBy('idioma_id', 'DESC');
                            },
                            'imagens' => function ($query) use ($idioma) {
                                $query->where([
                                    'excluido' => NULL,
                                    'visivel' => true,
                                ])
                                    ->with([
                                        'imagensIdiomas' => function ($q) use ($idioma) {
                                            $q->whereHas('idiomas', function ($r) use ($idioma) {
                                                $r->where('codigo', $idioma)
                                                    ->orWhere('padrao', true);
                                            })
                                                ->orderBy('idioma_id', 'DESC');
                                        },
                                        'acabamentos' => function ($q) use ($idioma) {
                                            $q->where([
                                                'excluido' => NULL,
                                                'visivel' => true,
                                            ])
                                                ->with(['acabamentosIdiomas' => function ($r) use ($idioma) {
                                                    $r->whereHas('idiomas', function ($s) use ($idioma) {
                                                        $s->where('codigo', $idioma)
                                                            ->orWhere('padrao', true);
                                                    })
                                                        ->orderBy('idioma_id', 'DESC');
                                                }])
                                                ->orderBy('ordem', 'ASC')
                                                ->orderBy('id', 'DESC');
                                        },
                                    ])
                                    ->orderBy('ordem', 'ASC')
                                    ->orderBy('id', 'DESC');
                            },
                        ])
                        ->orderBy('ordem', 'ASC')
                        ->orderBy('id', 'DESC');
                },
            ])
            ->first();

        if (!$ambiente) {
            return Inertia::location(route('Produtos.index'));
        }

        $ambiente_data = [
            'id' => $ambiente->id,
            'banner' => rafator('content/products/banner/' . $ambiente->banner),
            'nome' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->nome : null,
            'descricao' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->descricao : null,
            'slug' => $ambiente->slug,
            'colecoes' => $ambiente->colecoes->map(function ($colecao) {
                return [
                    'id' => $colecao->id,
                    'nome' => $colecao->colecoesIdiomas->isNotEmpty() ? $colecao->colecoesIdiomas[0]->nome : null,
                    'descricao_curta' => $colecao->colecoesIdiomas->isNotEmpty() ? $colecao->colecoesIdiomas[0]->descricao_curta : null,
                    'imagens' => $colecao->imagens->map(function ($imagem) {
                        $acabamentos = $imagem->acabamentos
                            ->map(function ($acabamento) {
                                return $acabamento->acabamentosIdiomas->isNotEmpty() ? $acabamento->acabamentosIdiomas[0]->nome : null;
                            })
                            ->filter()
                            ->implode(', ');

                        return [
                            'id' => $imagem->id,
                            'imagem' => rafator('content/stores/projects/gallery/s/' . $imagem->imagem),
                            'imagem_grande' => rafator('content/stores/projects/gallery/b/' . $imagem->imagem),
                            'detalhes' => $imagem->imagensIdiomas->isNotEmpty() ? $imagem->imagensIdiomas[0]->detalhes : null,
                            'acabamentos' => $acabamentos,
                        ];
                    }),
                ];
            }),
        ];

        $pagina = new Pagina;
        $pagina->titulo = $ambiente->ambientesIdiomas[0]->titulo_pagina . ' | Dell Anno';
        $pagina->descricao = $ambiente->ambientesIdiomas[0]->descricao_pagina . ' | Dell Anno';
        $pagina->tituloCompartilhamento = $ambiente->ambientesIdiomas[0]->titulo_pagina . ' | Dell Anno';
        $pagina->descricaoCompartilhamento = $ambiente->ambientesIdiomas[0]->descricao_pagina . ' | Dell Anno';

        list($width, $height, $type, $attr) = getimagesize(public_path('/content/products/thumbs/' . $ambiente->imagem));

        $pagina->imagem = [
            'endereco' => '/content/products/thumbs/' . $ambiente->imagem,
            'tipo' => image_type_to_mime_type($type),
            'largura' => $width,
            'altura' => $height,
        ];
        
        return Inertia::render('Produto', [
            'pagina' => $pagina,
            'produto' => $ambiente_data,
            'chamadaForm' => $this->chamadaForm($idioma),
        ]);
    }

    private function chamadaForm($idioma)
    {
        $conteudo = Conteudo::query()
            ->where([
                'excluido' => NULL,
                'id' => 14,
            ])
            ->with(['conteudosIdiomas' => function ($q) use ($idioma) {
                $q->whereHas('idiomas', function ($r) use ($idioma) {
                    $r->where('codigo', $idioma)
                        ->orWhere('padrao', true);
                })
                    ->orderBy('idioma_id', 'DESC');
            }])
            ->first();

        if (!$conteudo) {
            return null;
        }

        return [
            'id' => $conteudo->id,
            'titulo' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->titulo : null,
            'texto' => $conteudo->conteudosIdiomas->isNotEmpty() ? $conteudo->conteudosIdiomas[0]->texto : null,
        ];
    }
}
