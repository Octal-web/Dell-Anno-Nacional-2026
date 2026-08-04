<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Cidade;
use App\Models\Estado;

class CidadesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function carregar(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'estado_id' => 'required|exists:estados,id',
            ]);

            $cidades = Cidade::query()
                ->where('estado_id', $request->estado_id)
                ->select('id', 'nome')
                ->orderBy('nome')
                ->get()
                ->map(function ($cidade) {
                    return [
                        'value' => $cidade->id,
                        'label' => $cidade->nome,
                    ];
                })
                ->values();

            return response()->json([
                'cidades' => $cidades,
            ]);
        } else {
            return Inertia::location(route('Home.index'));
        }
    }

    public function estados(Request $request)
    {
        if ($request->ajax()) {

            $estados = Estado::query()
                ->orderBy('nome', 'ASC')
                ->get()
                ->map(function ($estado) {
                    return [
                        'value' => $estado->id,
                        'uf' => $estado->uf,
                        'label' => $estado->nome,
                    ];
                });

            return response()->json([
                'estados' => $estados,
            ]);
        } else {
            return Inertia::location(route('Home.index'));
        }
    }
}
