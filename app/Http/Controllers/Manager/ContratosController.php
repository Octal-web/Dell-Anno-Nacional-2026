<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\PostContractRequest;
use App\Models\Contrato;
use App\Models\ContratoIdioma;
use App\Models\Idioma;
use App\Services\ImageCompressor;
use DeepCopy\DeepCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
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
        $contratos = Contrato::query()
            ->where([
                'excluido' => NULL
            ])
            ->with([
                'contratosIdiomas' => function ($q) {
                    $q->whereHas('idiomas', function ($r) {
                        $r->Where('padrao', true);
                    })
                        ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($contrato) {
                return [
                    'id' => $contrato->id,
                    'visivel' => $contrato->visivel,
                    'imagem' => rafator('content/contracts/thumbs/' . $contrato->imagem),
                    'titulo' => $contrato->contratosIdiomas->isNotEmpty() ? $contrato->contratosIdiomas[0]->titulo : null,
                ];
            });

        return Inertia::render('Manager/Contratos/index', [
            'contratos' => $contratos
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar()
    {
        return Inertia::render('Manager/Contratos/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostContractRequest $request,  ImageCompressor $compressor)
    {
        if ($request->ajax()) {
            $idioma = inertia()->getShared('idioma');

            $contrato = new Contrato;
            $contrato_idioma = new ContratoIdioma;

            $contrato->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            $response = $contrato->save();

            $contrato_idioma->titulo = $request->titulo;
            $contrato_idioma->subtitulo = $request->subtitulo;
            $contrato_idioma->texto = $request->texto;

            $contrato_idioma->contrato_id = $contrato->id;
            $contrato_idioma->idioma_id = $idioma->id;

            $response = $contrato_idioma->save();

            if ($response) {
                $compressor->compressOrFallback($request->file('img')->getRealPath(), public_path('content/contracts/thumbs/' . $contrato->imagem));

                return to_route('Manager.Contratos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id)
    {
        if (!$id) {
            return Inertia::location(route('Manager.Contratos.index'));
        }

        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $contrato = Contrato::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'contratosIdiomas' => function ($q) use ($idioma) {
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
                }
            ])
            ->first();

        if (!$contrato) {
            return Inertia::location(route('Manager.Contratos.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $contratoData = [
            'id' => $contrato->id,
            'imagem' => rafator('content/contracts/thumbs/' . $contrato->imagem),
            'titulo' => $contrato->contratosIdiomas[0]->titulo,
            'subtitulo' => $contrato->contratosIdiomas[0]->subtitulo,
            'texto' => $contrato->contratosIdiomas[0]->texto,
        ];

        return Inertia::render('Manager/Contratos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'contrato' => $contratoData,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostContractRequest $request, $id, ImageCompressor $compressor)
    {
        if ($request->ajax()) {
            $contrato = Contrato::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $contrato_idioma = ContratoIdioma::query()
                ->where([
                    'excluido' => null,
                    'contrato_id' => $contrato->id
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

            if (!$contrato) {
                return to_route('Manager.Contratos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($contrato, 'contratosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Contratos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Contratos.index'));
            }

            if (!$contrato_idioma) {
                $contrato_idioma = new ContratoIdioma;

                $contrato_idioma->contrato_id = $contrato->id;
                $contrato_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $contratoOriginal = $copier->copy($contrato);
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $contrato->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $contrato_idioma->titulo = $request->titulo;
            $contrato_idioma->subtitulo = $request->subtitulo;
            $contrato_idioma->texto = $request->texto;

            $response = $contrato->save();
            $response = $contrato_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($contrato->imagem && isset($contratoOriginal) && File::exists('content/contracts/thumbs/' . $contratoOriginal->imagem)) {
                        File::delete('content/contracts/thumbs/' . $contratoOriginal->imagem);
                    }

                    $compressor->compressOrFallback($request->file('img')->getRealPath(), public_path('content/contracts/thumbs/' . $contrato->imagem));
                }

                return to_route('Manager.Contratos.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Contratos.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = Contrato::query()
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

            $response = Contrato::query()
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
                    $registro = Contrato::query()
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
};
