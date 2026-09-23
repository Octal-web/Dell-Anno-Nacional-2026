<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Contrato;
use App\Models\ImagemContrato;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Carbon\Carbon;

class ImagensContratosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if (!$id) {
            return Inertia::location(route('Manager.Contratos.index'));
        }

        $contrato = Contrato::query()
            ->where([
                'excluido' => NULL,
                'id' => $id
            ])
            ->with([
                'contratosIdiomas' => function ($q) {
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

        if (!$contrato) {
            return Inertia::location(route('Manager.Contratos.index'));
        }

        $contratoData = [
            'id' => $contrato->id,
            'nome' => $contrato->contratosIdiomas->isNotEmpty() ? $contrato->contratosIdiomas[0]->titulo : null,
            'imagens' => $contrato->imagens->map(function ($img) {
                return [
                    'id' => $img->id,
                    'visivel' => $img->visivel ? true : false,
                    'imagem' => asset('content/contracts/gallery/' . $img->imagem),
                ];
            })->values()->all(),
        ];

        return Inertia::render('Manager/Contratos/Imagens/index', [
            'contrato' => $contratoData,
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
            $contrato = Contrato::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $id
                ])
                ->first();

            if (!$contrato) {
                return Inertia::location(route('Manager.Contratos.index'));
            }

            foreach ($request->file('images') as $image) {
                $imagem = new ImagemContrato;

                $imagem->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($image['img']->extension());

                $imagem->contrato_id = $contrato->id;

                $response = $imagem->save();

                if ($response) {
                    $compressor->compressOrFallback($image['img']->getRealPath(), public_path('content/contracts/gallery/' . $imagem->imagem));
                } else {
                    return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Erro ao salvar imagem']);
                }
            }

            return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Imagens adicionadas com sucesso!']);
        }

        return redirect()->back();
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

            $exclusao = ImagemContrato::query()
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

            $response = ImagemContrato::query()
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
                    $registro = ImagemContrato::query()
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
