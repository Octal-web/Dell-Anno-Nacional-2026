<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use App\Models\ProjetoContato;
use App\Models\ProjetoContatoIdioma;
use App\Models\Idioma;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Manager\PostContactProjectRequest;
use App\Services\ImageCompressor;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

use DeepCopy\DeepCopy;

class ProjetosContatosController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adicionar()
    {
        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        return Inertia::render('Manager/ProjetosContatos/adicionar');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function novo(PostContactProjectRequest $request, ImageCompressor $compressor)
    {
        if ($request->ajax()) {
            $idioma = inertia()->getShared('idioma');

            $projeto = new ProjetoContato;
            $projeto_idioma = new ProjetoContatoIdioma;

            $projeto->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());

            $response = $projeto->save();

            $projeto_idioma->descricao = $request->descricao;

            $projeto_idioma->projeto_contato_id = $projeto->id;
            $projeto_idioma->idioma_id = $idioma->id;

            $response = $projeto_idioma->save();

            if ($response) {

                $compressor->compressOrFallback($request->file('img')->getRealPath(), public_path('content/contact-projects/thumbs/' . $projeto->imagem));

                return to_route('Manager.Contato.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
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
            return Inertia::location(route('Manager.Contato.index'));
        }

        $idiomas = Idioma::query()
            ->orderBy('padrao', 'DESC')
            ->orderBy('id', 'DESC')
            ->get();

        $idioma = request('lang');

        $projeto = ProjetoContato::query()
            ->where([
                'excluido' => null,
                'id' => $id
            ])
            ->with([
                'projetosContatosIdiomas' => function ($q) use ($idioma) {
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
            ])
            ->first();

        if (!$projeto) {
            return Inertia::location(route('Manager.Contato.index'));
        }

        $idioma = inertia()->getShared('idioma');

        $projeto = [
            'id' => $projeto->id,
            'imagem' => asset('content/contact-projects/thumbs/' . $projeto->imagem),
            'descricao' => count($projeto->projetosContatosIdiomas) ? $projeto->projetosContatosIdiomas[0]->descricao : null
        ];

        return Inertia::render('Manager/ProjetosContatos/editar', [
            'idiomas' => $idiomas,
            'idioma' => $idioma,
            'projeto' => $projeto
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function atualizar(PostContactProjectRequest $request, $id, ImageCompressor $compressor)
    {
        if ($request->ajax()) {
            $projeto = ProjetoContato::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            $idioma = $request->query('lang');

            $projeto_idioma = ProjetoContatoIdioma::query()
                ->where([
                    'excluido' => null,
                    'projeto_contato_id' => $projeto->id
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

            if (!$projeto) {
                return to_route('Manager.Contato.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
            }

            $idioma = $this->getLanguages($projeto, 'projetosContatosIdiomas', $idioma);

            if (!$idioma) {
                if ($request->ajax()) {
                    return to_route('Manager.Contato.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
                }
                return Inertia::location(route('Manager.Contato.index'));
            }

            if (!$projeto_idioma) {
                $projeto_idioma = new ProjetoContatoIdioma;

                $projeto_idioma->projeto_contato_id = $projeto->id;
                $projeto_idioma->idioma_id = $idioma;
            } else {
                $copier = new DeepCopy();
                $projetoOriginal = $copier->copy($projeto);
            }

            if ($request->file('img') && $request->file('img')->getError() == 0) {
                $projeto->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
            }

            $projeto_idioma->descricao = $request->descricao;

            $response = $projeto->save();
            $response = $projeto_idioma->save();

            if ($response) {
                if ($request->file('img') && $request->file('img')->getError() == 0) {
                    if ($projeto->imagem && isset($projetoOriginal) && File::exists('content/contact-projects/thumbs/' . $projetoOriginal->imagem)) {
                        File::delete('content/contact-projects/thumbs/' . $projetoOriginal->imagem);
                    }

                    $compressor->compressOrFallback($request->file('img')->getRealPath(), public_path('content/contact-projects/thumbs/' . $projeto->imagem));
                }

                return to_route('Manager.Contato.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
            }
        }

        return to_route('Manager.Contato.index')->with('message', ['type' => 'error', 'msg' => 'Não foi possível salvar as informações. Tente novamente mais tarde.']);
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

            $exclusao = ProjetoContato::query()
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

            $response = ProjetoContato::query()
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
                    $registro = ProjetoContato::query()
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
