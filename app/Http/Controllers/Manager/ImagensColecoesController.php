<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\PostStoreProjectImageRequest;
use App\Models\AcabamentoCategoria;
use App\Models\Ambiente;
use App\Models\Colecao;
use App\Models\Idioma;
use App\Models\ImagemProjetoLoja;
use App\Models\ImagemProjetoLojaIdioma;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class ImagensColecoesController extends Controller
{
    public function index($id)
    {
        $colecao = Colecao::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->with([
                'colecoesIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->where('padrao', true);
                    });
                },
                'imagens' => function ($q) {
                    $q->where([
                        'excluido' => NULL,
                    ])
                        ->orderBy('ordem', 'ASC')
                        ->orderBy('id', 'DESC');
                },
                'ambiente.ambientesIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->where('padrao', true);
                    });
                },
            ])
            ->first();

        if (!$colecao) {
            return Inertia::location(route('Manager.Ambientes.index'));
        }

        return Inertia::render('Manager/Ambientes/Colecoes/Imagens/index', [
            'colecao' => [
                'id' => $colecao->id,
                'ambiente_id' => $colecao->ambiente_id,
                'ambiente_nome' => $colecao->ambiente->ambientesIdiomas[0]->nome,
                'nome' => $colecao->colecoesIdiomas[0]->nome,
                'imagens' => $colecao->imagens->map(function ($imagem) {
                    return [
                        'id' => $imagem->id,
                        'visivel' => $imagem->visivel ? true : false,
                        'imagem' => asset('content/stores/projects/gallery/s/' . $imagem->imagem),
                        'imagem_completa' => asset('content/stores/projects/gallery/b/' . $imagem->imagem),
                    ];
                })->values()->all(),
            ],
        ]);
    }

    public function novo(Request $request, $id, ImageCompressor $compressor)
    {
        if (!$request->ajax()) {
            return redirect()->back();
        }

        $colecao = Colecao::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->first();

        if (!$colecao) {
            return Inertia::location(route('Manager.Ambientes.index'));
        }

        foreach ($request->file('images') as $image) {
            $imagem = new ImagemProjetoLoja;
            $imagem->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($image['img']->extension());
            $imagem->visivel = true;
            $imagem->save();
            $imagem->colecoes()->attach($colecao->id);

            $compressor->compressOrFallback(
                $image['img_alt']->getRealPath(),
                public_path('content/stores/projects/gallery/s/' . $imagem->imagem)
            );

            $compressor->compressOrFallback(
                $image['img']->getRealPath(),
                public_path('content/stores/projects/gallery/b/' . $imagem->imagem)
            );
        }

        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Imagens adicionadas com sucesso!']);
    }

    public function editar($id)
    {
        $idioma = request('lang');
        $colecao_id = request('colecao');

        $imagem = ImagemProjetoLoja::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->with([
                'imagensIdiomas' => function ($q) use ($idioma) {
                    $q->when($idioma, function ($r) use ($idioma) {
                        $r->whereHas('idiomas', function ($query) use ($idioma) {
                            $query->where('codigo', $idioma);
                        });
                    })
                        ->when(!$idioma, function ($r) {
                            $r->whereHas('idiomas', function ($query) {
                                $query->where('padrao', true);
                            });
                        });
                },
                'acabamentos',
                'colecoes',
            ])
            ->first();

        $colecao = Colecao::query()
            ->where([
                'excluido' => NULL,
                'id' => $colecao_id,
            ])
            ->with(['colecoesIdiomas' => function ($q) {
                $q->whereHas('idiomas', function ($r) {
                    $r->where('padrao', true);
                });
            }])
            ->first();

        if (!$imagem || !$colecao) {
            return Inertia::location(route('Manager.Ambientes.index'));
        }

        return Inertia::render('Manager/Ambientes/Colecoes/Imagens/editar', [
            'imagemItem' => [
                'id' => $imagem->id,
                'colecao_id' => $colecao->id,
                'ambiente_id' => $colecao->ambiente_id,
                'colecao_nome' => $colecao->colecoesIdiomas[0]->nome,
                'imagem' => asset('content/stores/projects/gallery/s/' . $imagem->imagem),
                'detalhes' => $imagem->imagensIdiomas->isNotEmpty() ? $imagem->imagensIdiomas[0]->detalhes : null,
                'acabamentos' => $imagem->acabamentos->pluck('id')->values()->all(),
                'colecoes' => $imagem->colecoes->pluck('id')->values()->all(),
            ],
            'acabamentos' => $this->acabamentosOptions(),
            'colecoes' => $this->colecoesOptions(),
        ]);
    }

    public function atualizar(PostStoreProjectImageRequest $request, $id, ImageCompressor $compressor)
    {
        $imagem = ImagemProjetoLoja::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->first();

        if (!$imagem) {
            return Inertia::location(route('Manager.Ambientes.index'));
        }

        if ($request->hasFile('img')) {
            $imagem_original = $imagem->imagem;
            $imagem->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            $imagem->save();

            $compressor->compressOrFallback(
                $request->file('img')->getRealPath(),
                public_path('content/stores/projects/gallery/s/' . $imagem->imagem)
            );

            $compressor->compressOrFallback(
                $request->file('img')->getRealPath(),
                public_path('content/stores/projects/gallery/b/' . $imagem->imagem)
            );

            if (File::exists(public_path('content/stores/projects/gallery/s/' . $imagem_original))) {
                File::delete(public_path('content/stores/projects/gallery/s/' . $imagem_original));
            }

            if (File::exists(public_path('content/stores/projects/gallery/b/' . $imagem_original))) {
                File::delete(public_path('content/stores/projects/gallery/b/' . $imagem_original));
            }
        }

        $idioma = $request->query('lang');
        $imagem_idioma = ImagemProjetoLojaIdioma::query()
            ->where([
                'excluido' => NULL,
                'imagem_id' => $imagem->id,
            ])
            ->when($idioma, function ($q) use ($idioma) {
                $q->whereHas('idiomas', function ($query) use ($idioma) {
                    $query->where('codigo', $idioma);
                });
            })
            ->when(!$idioma, function ($q) {
                $q->whereHas('idiomas', function ($query) {
                    $query->where('padrao', true);
                });
            })
            ->first();

        $idioma_id = Idioma::query()
            ->when($idioma, function ($q) use ($idioma) {
                $q->where('codigo', $idioma);
            })
            ->when(!$idioma, function ($q) {
                $q->where('padrao', true);
            })
            ->value('id');

        if (!$imagem_idioma) {
            $imagem_idioma = new ImagemProjetoLojaIdioma;
            $imagem_idioma->imagem_id = $imagem->id;
            $imagem_idioma->idioma_id = $idioma_id;
        }

        $imagem_idioma->detalhes = $request->detalhes;
        $imagem_idioma->save();

        $imagem->acabamentos()->sync($request->acabamentos ?? []);
        $imagem->colecoes()->sync($request->colecoes ?? []);

        return to_route('Manager.Ambientes.Colecoes.Imagens.index', [
            'id' => $request->colecao_id,
        ])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function cortar(Request $request, $id, ImageCompressor $compressor)
    {
        if (!$request->ajax()) {
            return redirect()->back();
        }

        $imagem = ImagemProjetoLoja::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->first();

        if (!$imagem || !$request->hasFile('img')) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro ou imagem não encontrado!']);
        }

        $compressor->compressOrFallback(
            $request->file('img')->getRealPath(),
            public_path('content/stores/projects/gallery/s/' . $imagem->imagem)
        );

        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Imagem cortada com sucesso!']);
    }

    public function excluir(Request $request, $id)
    {
        $colecao = Colecao::query()
            ->where([
                'excluido' => NULL,
                'id' => $request->query('colecao'),
            ])
            ->first();

        if (!$colecao) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Coleção não encontrada!']);
        }

        $colecao->imagens()->detach($id);

        return redirect()->back()->with('message', ['type' => 'alert', 'msg' => 'Imagem removida da coleção com sucesso.']);
    }

    public function visibilidade(Request $request, $id)
    {
        $imagem = ImagemProjetoLoja::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->first();

        if (!$imagem) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        }

        $imagem->visivel = 1 - $imagem->visivel;
        $imagem->save();

        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Visibilidade alterada com sucesso!']);
    }

    public function ordenar(Request $request)
    {
        foreach ($request->odr ?? [] as $item) {
            ImagemProjetoLoja::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $item['id'],
                ])
                ->update(['ordem' => $item['ordem']]);
        }

        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registros reordenados com sucesso!']);
    }

    private function acabamentosOptions()
    {
        return AcabamentoCategoria::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
            ])
            ->whereHas('acabamentos', function ($q) {
                $q->where([
                    'excluido' => NULL,
                    'visivel' => true,
                ]);
            })
            ->with([
                'acabamentosCategoriasIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->where('padrao', true);
                    });
                },
                'acabamentos' => function ($q) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true,
                    ])
                        ->with(['acabamentosIdiomas' => function ($r) {
                            $r->whereHas('idiomas', function ($s) {
                                $s->where('padrao', true);
                            });
                        }])
                        ->orderBy('ordem', 'ASC')
                        ->orderBy('id', 'DESC');
                },
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($categoria) {
                return [
                    'label' => $categoria->acabamentosCategoriasIdiomas->isNotEmpty() ? $categoria->acabamentosCategoriasIdiomas[0]->nome : 'Categoria sem nome',
                    'options' => $categoria->acabamentos->map(function ($acabamento) {
                        return [
                            'value' => $acabamento->id,
                            'label' => $acabamento->acabamentosIdiomas->isNotEmpty() ? $acabamento->acabamentosIdiomas[0]->nome : 'Acabamento sem nome',
                        ];
                    })->values(),
                ];
            })
            ->values();
    }

    private function colecoesOptions()
    {
        return Ambiente::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true,
            ])
            ->whereHas('colecoes', function ($q) {
                $q->where([
                    'excluido' => NULL,
                    'visivel' => true,
                ]);
            })
            ->with([
                'ambientesIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->where('padrao', true);
                    });
                },
                'colecoes' => function ($q) {
                    $q->where([
                        'excluido' => NULL,
                        'visivel' => true,
                    ])
                        ->with(['colecoesIdiomas' => function ($r) {
                            $r->whereHas('idiomas', function ($s) {
                                $s->where('padrao', true);
                            });
                        }])
                        ->orderBy('ordem', 'ASC')
                        ->orderBy('id', 'DESC');
                },
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($ambiente) {
                return [
                    'label' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->nome : 'Ambiente sem nome',
                    'options' => $ambiente->colecoes->map(function ($colecao) {
                        return [
                            'value' => $colecao->id,
                            'label' => $colecao->colecoesIdiomas->isNotEmpty() ? $colecao->colecoesIdiomas[0]->nome : 'Coleção sem nome',
                        ];
                    })->values(),
                ];
            })
            ->values();
    }
}
