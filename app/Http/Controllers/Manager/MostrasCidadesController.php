<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\MostraAno;
use App\Models\MostraCidade;
use App\Models\MostraCidadeIdioma;
use App\Models\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostMostraCityRequest;

use Carbon\Carbon;
use Illuminate\Support\Facades\Response;


class MostrasCidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id) {
        if (!$id) {
            return Inertia::location(route('Manager.Mostras.index'));
        }

        $mostraAno = MostraAno::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->with([
                'mostra' => function ($q) {
                    $q->where([
                        'excluido' => NULL
                    ])
                    ->with([
                        'mostrasIdiomas' => function ($q) {
                            $q->where([
                                'excluido' => NULL
                            ]);
                        }
                    ]);
                },
                'mostrasCidades' => function ($q)  {
                    $q->where([
                        'excluido' => null
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'DESC')
                    ->with([
                        'mostrasCidadesIdiomas' => function ($query) {
                            $query->whereHas('idiomas', function ($secr) {
                                $secr->Where('padrao', true);
                            })
                            ->orderBy('idioma_id', 'DESC');
                        }
                    ]);
                },
            ])
            ->first();

        if(!$mostraAno) {
            return Inertia::location(route('Manager.Mostras.index'));
        }

        $mostraNome = count($mostraAno->mostra->mostrasIdiomas) ? $mostraAno->mostra->mostrasIdiomas[0]->nome : null;

        $mostraAnoData = [
            'id' => $mostraAno->id,
            'nome' => $mostraNome,
            'ano' => $mostraAno->ano,
            'mostrasCidades' => $mostraAno->mostrasCidades->map(function ($mostraCidade) {
                return [
                    'id' => $mostraCidade->id,
                    'visivel' => $mostraCidade->visivel ? true : false,
                    'nome' => count($mostraCidade->mostrasCidadesIdiomas) ? $mostraCidade->mostrasCidadesIdiomas[0]->nome : null,
                ];
            })->values()->all(),
        ];

        return Inertia::render('Manager/Mostras/Cidades/index', [
            'mostraAno' => $mostraAnoData,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar($id) {
        if (!$id) {
            return Inertia::location(route('Manager.Mostras.index'));
        }

        return Inertia::render('Manager/Mostras/Cidades/adicionar', [
            'id' => $id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostMostraCityRequest $request, $id) {
        if (!$id) {
            return Inertia::location(route('Manager.Mostras.index'));
        }
        
        if($request->ajax()){
            $idioma = inertia()->getShared('idioma');
            
            $mostra_cidade = new MostraCidade;
            $mostra_cidade_idioma = new MostraCidadeIdioma;

            $mostra_cidade->mostra_ano_id = $id;

            $response = $mostra_cidade->save();

            $mostra_cidade_idioma->nome = $request->nome;
            $mostra_cidade_idioma->cidade = $request->cidade;
            $mostra_cidade_idioma->descricao = $request->descricao;

            $mostra_cidade_idioma->mostra_cidade_id = $mostra_cidade->id;
            $mostra_cidade_idioma->idioma_id = $idioma->id;

            $response = $mostra_cidade_idioma->save();

            if ($response) {
                return to_route('Manager.Mostras.Cidades.index', ['id' => $id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editar($id) {
        if (!$id) {
            return Inertia::location(route('Manager.Mostras.index'));
        }
        
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $mostra_cidade = MostraCidade::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'mostrasCidadesIdiomas' => function ($q) use ($idioma) {
                    $q->when($idioma, function ($r) use($idioma) {
                        $r->whereHas('idiomas', function($query) use($idioma) {
                            $query->where('codigo', $idioma);
                        });
                    })
                    ->when(!$idioma, function ($r) {
                        $r->whereHas('idiomas', function($query) {
                            $query->where('padrao', true);
                        });
                    });
                },
            ])
            ->first();

        if(!$mostra_cidade) {
            return Inertia::location(route('Manager.Mostras.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $mostraCidadeData = [
            'id' => $mostra_cidade->id,
            'mostra_ano_id' => $mostra_cidade->mostra_ano_id,
            'nome' => count($mostra_cidade->mostrasCidadesIdiomas) ? $mostra_cidade->mostrasCidadesIdiomas[0]->nome : null,
            'cidade' => count($mostra_cidade->mostrasCidadesIdiomas) ? $mostra_cidade->mostrasCidadesIdiomas[0]->cidade : null,
            'descricao' => count($mostra_cidade->mostrasCidadesIdiomas) ? $mostra_cidade->mostrasCidadesIdiomas[0]->descricao : null,
        ];

        return Inertia::render('Manager/Mostras/Cidades/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'mostraCidade' => $mostraCidadeData,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostMostraCityRequest $request, $id) {
        if($request->ajax()){
            $mostra_cidade = MostraCidade::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $mostra_cidade_idioma = MostraCidadeIdioma::query()
                ->where([
                    'excluido' => null,
                    'mostra_cidade_id' => $mostra_cidade->id
                ])
                ->when($idioma, function ($q) use($idioma) {
                    $q->whereHas('idiomas', function($query) use($idioma) {
                        $query->where('codigo', $idioma);
                    });
                })
                ->when(!$idioma, function ($q) {
                    $q->whereHas('idiomas', function($query) {
                        $query->where('padrao', true);
                    });
                })
                ->first();

            if (!$mostra_cidade) {
                return to_route('Manager.Mostras.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($mostra_cidade, 'imagensIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Mostras.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Mostras.index'));
            }

            if (!$mostra_cidade_idioma) {
                $mostra_cidade_idioma = new MostraCidadeIdioma;

                $mostra_cidade_idioma->mostra_cidade_id = $mostra_cidade->id;
                $mostra_cidade_idioma->idioma_id = $idioma;
            }

            $mostra_cidade_idioma->nome = $request->nome;
            $mostra_cidade_idioma->cidade = $request->cidade;
            $mostra_cidade_idioma->descricao = $request->descricao;

            $response = $mostra_cidade->save();
            $response = $mostra_cidade_idioma->save();

            if ($response) {
                return to_route('Manager.Mostras.Cidades.index', ['id' => $mostra_cidade->mostra_ano_id])->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Mostras.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
    }

    /**
     * Set the specified resource as deleted.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function excluir(Request $request, $id) {
        if ($request->ajax()){
            if (!$id) {
                return $request->header('referer');
            }

            $exclusao = MostraCidade::query()
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
    public function visibilidade(Request $request, $id) {
        if ($request->ajax()){
            if (!$id) {
                return redirect()->back()->with(['type' => 'error', 'message' => 'Registro não encontrado!']);
            }

            $response = MostraCidade::query()
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
            }
            else {
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
    public function ordenar(Request $request) {
        if ($request->ajax()){
            $erros = [];

            if ($request->odr && is_array($request->odr)) {
                foreach ($request->odr as $key => $value) {
                    $registro = MostraCidade::query()
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