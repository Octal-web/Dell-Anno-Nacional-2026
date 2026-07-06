<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\Mostra;
use App\Models\MostraAno;
use App\Models\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostMostraRequest;
use App\Services\Manager\MostraService;

use InvalidArgumentException;

class MostrasController extends Controller
{
    protected $mostraService;

    public function __construct(MostraService $mostraService)
    {
        parent::__construct();
        $this->mostraService = $mostraService;
    }

    private const TIPOS = [
        'casacor' => 'Casacor',
        'casas-conceito' => 'Casas Conceito',
        'outras-mostras' => 'Outras mostras',
    ];

    private const TIPOS_FIXOS = [
        'casacor',
        'casas-conceito',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $mostras = Mostra::query()
            ->where([
                'excluido' => null
            ])
            ->with([
                'mostrasIdiomas' => function ($q) {
                    $q->where([
                        'excluido' => null
                    ])
                    ->whereHas('idiomas', function ($r) {
                        $r->where('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($mostra) {
                return [
                    'id' => $mostra->id,
                    'visivel' => $mostra->visivel,
                    'imagem' => $mostra->imagem ? rafator('content/fairs/thumbs/' . $mostra->imagem) : null,
                    'nome' => $mostra->mostrasIdiomas->isNotEmpty() ? $mostra->mostrasIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Manager/Mostras/index', [
            'mostras' => $mostras
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar() {
        $tipo = request('tipo');

        if (!$tipo || !$this->tipoValido($tipo)) {
            return to_route('Manager.Mostras.index')->with('message', [
                'type' => 'error',
                'msg' => 'Tipo inválido.'
            ]);
        }

        $mostraData = null;

        if ($this->tipoFixo($tipo)) {
            $idioma = inertia()->getShared('idioma');

            $mostra = Mostra::query()
                ->where([
                    'slug' => $tipo,
                    'excluido' => null
                ])
                ->with([
                    'mostrasIdiomas' => function ($q) use ($idioma) {
                        $q->where([
                            'excluido' => null
                        ])
                        ->whereHas('idiomas', function ($r) use ($idioma) {
                            $r->where('codigo', $idioma->codigo)
                              ->orWhere('padrao', true);
                        })
                        ->orderBy('idioma_id', 'DESC');
                    }
                ])
                ->first();

            if (!$mostra) {
                return to_route('Manager.Mostras.index')->with('message', [
                    'type' => 'error',
                    'msg' => 'Mostra não encontrada para o tipo informado.'
                ]);
            }

            $mostraData = [
                'id' => $mostra->id,
                'slug' => $mostra->slug,
                'nome' => $mostra->mostrasIdiomas->isNotEmpty() ? $mostra->mostrasIdiomas[0]->nome : self::TIPOS[$tipo],
            ];
        }

        return Inertia::render('Manager/Mostras/adicionar', [
            'tipo' => $tipo,
            'tipoLabel' => self::TIPOS[$tipo],
            'mostra' => $mostraData,
            'ocultarIdiomas' => $this->tipoFixo($tipo),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Manager\PostMostraRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostMostraRequest $request) {
        if($request->ajax()){
            try {
                $this->mostraService->novo($request);

                return to_route('Manager.Mostras.index')->with('message', [
                    'type' => 'success',
                    'msg' => 'Registro salvo com sucesso!'
                ]);
            } catch (InvalidArgumentException $e) {
                return back()->with('message', [
                    'type' => 'error',
                    'msg' => $e->getMessage()
                ]);
            }
        }

        return to_route('Manager.Mostras.index')->with('message', [
            'type' => 'error',
            'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * Recebe mostras.id.
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

        $mostra = Mostra::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'mostrasIdiomas' => function ($query) use ($idioma) {
                    $query->where([
                        'excluido' => null
                    ])
                    ->when($idioma, function ($r) use($idioma) {
                        $r->whereHas('idiomas', function($q) use($idioma) {
                            $q->where('codigo', $idioma);
                        });
                    })
                    ->when(!$idioma, function ($r) {
                        $r->whereHas('idiomas', function($q) {
                            $q->where('padrao', true);
                        });
                    });
                }
            ])
            ->first();

        if(!$mostra) {
            return Inertia::location(route('Manager.Mostras.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $tipoFixo = $this->tipoFixo($mostra->slug);

        $mostraIdioma = $mostra->mostrasIdiomas->isNotEmpty()
            ? $mostra->mostrasIdiomas[0]
            : null;

        $mostraAno = null;

        if (!$tipoFixo) {
            $mostraAno = MostraAno::query()
                ->where([
                    'excluido' => null,
                    'mostra_id' => $mostra->id
                ])
                ->orderBy('ordem', 'ASC')
                ->orderBy('id', 'DESC')
                ->first();
        }

        $mostraData = [
            'id' => $mostra->id,
            'ano_id' => $mostraAno ? $mostraAno->id : null,
            'slug' => $mostra->slug,
            'ano' => $mostraAno ? $mostraAno->ano : null,
            'imagem' => $mostra->imagem ? rafator('content/fairs/thumbs/' . $mostra->imagem) : null,
            'nome' => $mostraIdioma ? $mostraIdioma->nome : null,
            'descricao' => $mostraIdioma ? $mostraIdioma->descricao : null,
            'titulo_pagina' => $mostraIdioma ? $mostraIdioma->titulo_pagina : null,
            'descricao_pagina' => $mostraIdioma ? $mostraIdioma->descricao_pagina : null,
        ];

        $anos = [];

        if ($tipoFixo) {
            $anos = MostraAno::query()
                ->where([
                    'excluido' => null,
                    'mostra_id' => $mostra->id
                ])
                ->orderBy('ordem', 'ASC')
                ->orderBy('ano', 'DESC')
                ->orderBy('id', 'DESC')
                ->get()
                ->map(function($ano) {
                    return [
                        'id' => $ano->id,
                        'ano' => $ano->ano,
                        'nome' => $ano->ano,
                        'visivel' => $ano->visivel,
                        'ordem' => $ano->ordem,
                    ];
                });
        }

        return Inertia::render('Manager/Mostras/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'mostra' => $mostraData,
            'anos' => $anos,
            'tipoFixo' => $tipoFixo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * Recebe mostras.id.
     *
     * @param  \App\Http\Requests\Manager\PostMostraRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostMostraRequest $request, $id) {
        if($request->ajax()){
            try {
                $this->mostraService->atualizar($request, $id);

                return to_route('Manager.Mostras.index')->with('message', [
                    'type' => 'success',
                    'msg' => 'Registro salvo com sucesso!'
                ]);
            } catch (InvalidArgumentException $e) {
                return back()->with('message', [
                    'type' => 'error',
                    'msg' => $e->getMessage()
                ]);
            }
        }

        return to_route('Manager.Mostras.index')->with('message', [
            'type' => 'error',
            'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.'
        ]);
    }

    /**
     * Atualiza rapidamente o ano.
     * Recebe mostras_anos.id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizarAno(Request $request, $id) {
        if($request->ajax()){
            $request->validate([
                'ano' => 'required|integer|digits:4',
            ], [
                'ano.required' => 'Por favor, informe o ano.',
                'ano.integer' => 'Por favor, informe um ano válido.',
                'ano.digits' => 'Por favor, informe o ano com 4 dígitos.',
            ]);

            try {
                $this->mostraService->atualizarAno($request, $id);

                return back()->with('message', [
                    'type' => 'success',
                    'msg' => 'Ano atualizado com sucesso!'
                ]);
            } catch (InvalidArgumentException $e) {
                return back()->with('message', [
                    'type' => 'error',
                    'msg' => $e->getMessage()
                ]);
            }
        }

        return back()->with('message', [
            'type' => 'error',
            'msg' => 'Não foi possível atualizar o ano. Tente novamente mais tarde.'
        ]);
    }

    /**
     * Set the specified resource as deleted.
     * Recebe mostras_anos.id quando usado na tabela de anos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function excluir($id) {
        try {
            $this->mostraService->excluir($id);

            return back()->with('message', [
                'type' => 'success',
                'msg' => 'Registro excluído com sucesso!'
            ]);
        } catch (InvalidArgumentException $e) {
            return back()->with('message', [
                'type' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle visibility.
     * Recebe mostras_anos.id quando usado na tabela de anos.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function visibilidade($id) {
        try {
            $this->mostraService->visibilidade($id);

            return back()->with('message', [
                'type' => 'success',
                'msg' => 'Visibilidade alterada com sucesso!'
            ]);
        } catch (InvalidArgumentException $e) {
            return back()->with('message', [
                'type' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Sort resources.
     * Recebe mostras_anos.id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ordenar(Request $request) {
        if ($request->ajax()) {
            try {
                $this->mostraService->ordenar($request);

                return redirect()->back()->with('message', [
                    'type' => 'success',
                    'msg' => 'Registros reordenados com sucesso!'
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('message', [
                    'type' => 'error',
                    'msg' => 'Registros não reordenados, tente novamente mais tarde!'
                ]);
            }
        }

        return redirect()->back();
    }

    private function tipoValido(?string $tipo): bool {
        return $tipo && array_key_exists($tipo, self::TIPOS);
    }

    private function tipoFixo(?string $tipo): bool {
        return $tipo && in_array($tipo, self::TIPOS_FIXOS, true);
    }
}