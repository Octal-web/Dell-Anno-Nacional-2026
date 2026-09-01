<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\PostProductRequest;
use App\Models\Ambiente;
use App\Models\AmbienteIdioma;
use App\Models\Idioma;
use App\Services\ImageCompressor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AmbientesController extends Controller
{
    public function index()
    {
        $ambientes = Ambiente::query()
            ->where([
                'excluido' => NULL,
            ])
            ->with(['ambientesIdiomas' => function ($q) {
                $q->whereHas('idiomas', function ($r) {
                    $r->where('padrao', true);
                })
                    ->orderBy('idioma_id', 'DESC');
            }])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function ($ambiente) {
                return [
                    'id' => $ambiente->id,
                    'visivel' => $ambiente->visivel ? true : false,
                    'imagem' => rafator('content/products/thumbs/' . $ambiente->imagem),
                    'nome' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->nome : null,
                ];
            });

        return Inertia::render('Manager/Ambientes/index', [
            'ambientes' => $ambientes,
        ]);
    }

    public function adicionar()
    {
        return Inertia::render('Manager/Ambientes/adicionar');
    }

    public function novo(PostProductRequest $request, ImageCompressor $compressor)
    {
        $idioma = inertia()->getShared('idioma');
        $ambiente = new Ambiente;

        $slug_base = Str::slug($request->nome);
        $slug = $slug_base;
        $count = 1;

        while (Ambiente::query()->where('slug', $slug)->exists()) {
            $slug = $slug_base . '-' . $count;
            $count++;
        }

        $ambiente->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
        $ambiente->banner = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img_banner')->extension());
        $ambiente->slug = $slug;
        $ambiente->visivel = true;
        $ambiente->save();

        $ambiente_idioma = new AmbienteIdioma;
        $ambiente_idioma->nome = $request->nome;
        $ambiente_idioma->descricao = $request->descricao;
        $ambiente_idioma->titulo_pagina = $request->titulo_pagina;
        $ambiente_idioma->descricao_pagina = $request->descricao_pagina;
        $ambiente_idioma->ambiente_id = $ambiente->id;
        $ambiente_idioma->idioma_id = $idioma->id;
        $ambiente_idioma->save();

        $compressor->compressOrFallback(
            $request->file('img')->getRealPath(),
            public_path('content/products/thumbs/' . $ambiente->imagem)
        );

        $compressor->compressOrFallback(
            $request->file('img_banner')->getRealPath(),
            public_path('content/products/banner/' . $ambiente->banner)
        );

        return to_route('Manager.Ambientes.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function editar($id)
    {
        $idioma = request('lang');
        $ambiente = Ambiente::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->with(['ambientesIdiomas' => function ($q) use ($idioma) {
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
            }])
            ->first();

        if (!$ambiente) {
            return Inertia::location(route('Manager.Ambientes.index'));
        }

        return Inertia::render('Manager/Ambientes/editar', [
            'ambiente' => [
                'id' => $ambiente->id,
                'imagem' => rafator('content/products/thumbs/' . $ambiente->imagem),
                'banner' => rafator('content/products/banner/' . $ambiente->banner),
                'nome' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->nome : null,
                'descricao' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->descricao : null,
                'titulo_pagina' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->titulo_pagina : null,
                'descricao_pagina' => $ambiente->ambientesIdiomas->isNotEmpty() ? $ambiente->ambientesIdiomas[0]->descricao_pagina : null,
            ],
        ]);
    }

    public function atualizar(PostProductRequest $request, $id, ImageCompressor $compressor)
    {
        $ambiente = Ambiente::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->first();

        if (!$ambiente) {
            return to_route('Manager.Ambientes.index')->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        }

        $codigo = $request->query('lang');
        $ambiente_idioma = AmbienteIdioma::query()
            ->where([
                'excluido' => NULL,
                'ambiente_id' => $id,
            ])
            ->when($codigo, function ($q) use ($codigo) {
                $q->whereHas('idiomas', function ($query) use ($codigo) {
                    $query->where('codigo', $codigo);
                });
            })
            ->when(!$codigo, function ($q) {
                $q->whereHas('idiomas', function ($query) {
                    $query->where('padrao', true);
                });
            })
            ->first();

        if (!$ambiente_idioma) {
            $ambiente_idioma = new AmbienteIdioma;
            $ambiente_idioma->ambiente_id = $id;
            $ambiente_idioma->idioma_id = Idioma::query()
                ->when($codigo, function ($q) use ($codigo) {
                    $q->where('codigo', $codigo);
                })
                ->when(!$codigo, function ($q) {
                    $q->where('padrao', true);
                })
                ->value('id');
        }

        $imagem_original = $ambiente->imagem;
        $banner_original = $ambiente->banner;

        if ($request->hasFile('img')) {
            $ambiente->imagem = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img')->extension());
        }

        if ($request->hasFile('img_banner')) {
            $ambiente->banner = md5(uniqid((string) rand(), true)) . '.' . strtolower($request->file('img_banner')->extension());
        }

        if (!$codigo) {
            $slug_base = Str::slug($request->nome);
            $slug = $slug_base;
            $count = 1;

            while (Ambiente::query()->where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $slug_base . '-' . $count;
                $count++;
            }

            $ambiente->slug = $slug;
        }

        $ambiente_idioma->nome = $request->nome;
        $ambiente_idioma->descricao = $request->descricao;
        $ambiente_idioma->titulo_pagina = $request->titulo_pagina;
        $ambiente_idioma->descricao_pagina = $request->descricao_pagina;
        $ambiente->save();
        $ambiente_idioma->save();

        if ($request->hasFile('img')) {
            if ($imagem_original && File::exists(public_path('content/products/thumbs/' . $imagem_original))) {
                File::delete(public_path('content/products/thumbs/' . $imagem_original));
            }

            $compressor->compressOrFallback(
                $request->file('img')->getRealPath(),
                public_path('content/products/thumbs/' . $ambiente->imagem)
            );
        }

        if ($request->hasFile('img_banner')) {
            if ($banner_original && File::exists(public_path('content/products/banner/' . $banner_original))) {
                File::delete(public_path('content/products/banner/' . $banner_original));
            }

            $compressor->compressOrFallback(
                $request->file('img_banner')->getRealPath(),
                public_path('content/products/banner/' . $ambiente->banner)
            );
        }

        return to_route('Manager.Ambientes.index')->with('message', ['type' => 'success', 'msg' => 'Registro salvo com sucesso!']);
    }

    public function excluir(Request $request, $id)
    {
        $ambiente = Ambiente::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->first();

        if ($ambiente && $ambiente->colecoes()->where(['excluido' => NULL])->exists()) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'O ambiente possui coleções e não pode ser excluído.']);
        }

        $response = $ambiente ? $ambiente->update(['excluido' => Carbon::now()]) : false;

        return redirect()->back()->with('message', $response
            ? ['type' => 'alert', 'msg' => 'Registro excluído com sucesso.']
            : ['type' => 'error', 'msg' => 'Não foi possível excluir o registro.']);
    }

    public function visibilidade(Request $request, $id)
    {
        $ambiente = Ambiente::query()
            ->where([
                'excluido' => NULL,
                'id' => $id,
            ])
            ->first();

        if (!$ambiente) {
            return redirect()->back()->with('message', ['type' => 'error', 'msg' => 'Registro não encontrado!']);
        }

        $ambiente->visivel = 1 - $ambiente->visivel;
        $ambiente->save();

        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Visibilidade alterada com sucesso!']);
    }

    public function ordenar(Request $request)
    {
        foreach ($request->odr ?? [] as $item) {
            Ambiente::query()
                ->where([
                    'excluido' => NULL,
                    'id' => $item['id'],
                ])
                ->update(['ordem' => $item['ordem']]);
        }

        return redirect()->back()->with('message', ['type' => 'success', 'msg' => 'Registros reordenados com sucesso!']);
    }
}
