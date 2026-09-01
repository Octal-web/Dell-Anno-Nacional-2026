<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ProjetoLoja;
use App\Models\ImagemProjetoLoja;
use App\Models\ImagemProjetoLojaIdioma;
use App\Models\AcabamentoCategoria;
use App\Models\Ambiente;
use App\Models\Idioma;
use App\Services\ImageCompressor;
use App\Http\Requests\Manager\PostStoreProjectImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

use Carbon\Carbon;

class ImagensLojasProjetosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if (!$id) {
            return Inertia::location(route('Manager.Lojas.Projetos.index'));
        }

        $projeto = ProjetoLoja::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->with([
                'projetosLojasIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                        ->orderBy('idioma_id', 'DESC');
                },
                'imagens' => function ($q) {
                    $q->where([
                        'excluido' => NULL
                    ])
                        ->orderBy('ordem', 'ASC')
                        ->orderBy('id', 'DESC');
                }

            ])
            ->first();

        if (!$projeto) {
            return Inertia::location(route('Manager.Lojas.Projetos.index'));
        }

        $projetoData = [
            'id' => $projeto->id,
            'nome' => $projeto->projetosLojasIdiomas->isNotEmpty() ? $projeto->projetosLojasIdiomas[0]->nome : null,
            'imagens' => $projeto->imagens->map(function ($img) {
                return [
                    'id' => $img->id,
                    'visivel' => $img->visivel ? true : false,
                    'imagem' => asset('content/stores/projects/gallery/s/' . $img->imagem),
                    'imagem_completa' => asset('content/stores/projects/gallery/b/' . $img->imagem),
                ];
            })->values()->all(),
        ];

        return Inertia::render('Manager/Lojas/Projetos/Imagens/index', [
            'projeto' => $projetoData,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(Request $request, $id, ImageCompressor $compressor)
    {
        if ($request->ajax()) {
            $projeto = ProjetoLoja::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $id
                ])
                ->first();

            if (!$projeto) {
                return Inertia::location(route('Manager.Lojas.Projetos.index'));
            }

            foreach ($request->file('images') as $image) {
                $imagem = new ImagemProjetoLoja;

                $imagem->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($image['img']->extension());

                $imagem->projeto_loja_id = $projeto->id;

                $response = $imagem->save();

                if ($response) {
                    $compressor->compressOrFallback($image['img_alt']->getRealPath(), public_path('content/stores/projects/gallery/s/' . $imagem->imagem));

                    $compressor->compressOrFallback($image['img']->getRealPath(), public_path('content/stores/projects/gallery/b/' . $imagem->imagem));
                } else {
                    return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Erro ao salvar imagem']);
                }
            }

            return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Imagens adicionadas com sucesso!']);
        }

        return redirect()->back();
    }

    public function editar($id)
    {
        $idioma = request('lang');

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
                'projeto',
            ])
            ->first();

        if (!$imagem) {
            return Inertia::location(route('Manager.Lojas.Projetos.index'));
        }

        return Inertia::render('Manager/Lojas/Projetos/Imagens/editar', [
            'imagemItem' => [
                'id' => $imagem->id,
                'projeto_id' => $imagem->projeto_loja_id,
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
            return Inertia::location(route('Manager.Lojas.Projetos.index'));
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

        return to_route('Manager.Lojas.Projetos.Imagens.index', ['id' => $imagem->projeto_loja_id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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

    /**
     * Crop the specified resource image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cortar(Request $request, $id, ImageCompressor $compressor)
    {
        if ($request->ajax()) {
            if (!$id) {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
            }

            $imagem = ImagemProjetoLoja::query()
                ->where([
                    'id' => $id,
                    'excluido' => NULL
                ])
                ->first();

            if (!$imagem) {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
            }

            if ($request->hasFile('img')) {
                $path = public_path('content/stores/projects/gallery/s/' . $imagem->imagem);

                if (file_exists($path)) {
                    @unlink($path);
                }

                $compressor->compressOrFallback($request->file('img')->getRealPath(), public_path('content/stores/projects/gallery/s/' . $imagem->imagem));

                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Imagem cortada com sucesso!']);
            }

            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Nenhuma imagem enviada.']);
        }

        return $request->header('referer');
    }

    /**
     * Set the specified resource as deleted.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function excluir(Request $request, $id)
    {
        if ($request->ajax()) {
            if (!$id) {
                return $request->header('referer');
            }

            $exclusao = ImagemProjetoLoja::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $id
                ])
                ->update([
                    'excluido' => Carbon::now()
                ]);

            if ($exclusao == true) {
                return redirect()->back()->with('message', ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.']);
            } else {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Não foi possível excluir o registro.']);
            }
        }
    }

    /**
     * Set the specified resource to visible/invisible.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visibilidade(Request $request, $id)
    {
        if ($request->ajax()) {
            if (!$id) {
                return redirect()->back()->with(['type' => 'error', 'message' => 'Registro não encontrado!']);
            }

            $response = ImagemProjetoLoja::query()
                ->where([
                    'id' => $id,
                    'excluido' => NULL
                ])
                ->first();

            if (!$response) {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
            }

            $response->visivel = 1 - $response->visivel;
            $response->save();

            if ($response) {
                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Visibilidade alterada com sucesso!']);
            } else {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Visibilidade não alterada!']);
            }
        }

        return $request->header('referer');
    }

    /**
     * Update the order of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ordenar(Request $request)
    {
        if ($request->ajax()) {
            $erros = [];

            if ($request->odr && is_array($request->odr)) {
                foreach ($request->odr as $key => $value) {
                    $registro = ImagemProjetoLoja::query()
                        ->where([
                            'excluido' => NULL,
                            'id' => $value
                        ])
                        ->update([
                            'ordem' => $key,
                        ]);

                    $errors[] = $registro;
                }
            }

            if (!count($erros)) {
                return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registros reordenados com sucesso!']);
            } else {
                return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registros não reordenados, tente novamente mais tarde!']);
            }
        }

        return redirect()->back();
    }
}
