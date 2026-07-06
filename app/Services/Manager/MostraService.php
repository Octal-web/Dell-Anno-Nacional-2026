<?php

namespace App\Services\Manager;

use App\Models\Mostra;
use App\Models\MostraAno;
use App\Models\MostraIdioma;

use App\Services\ImageCompressor;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

use InvalidArgumentException;

class MostraService
{
    private const TIPOS = [
        'casacor',
        'casas-conceito',
        'outras-mostras',
    ];

    private const TIPOS_FIXOS = [
        'casacor',
        'casas-conceito',
    ];

    private const PATH_IMAGEM = 'content/fairs/thumbs/';

    protected $compressor;

    public function __construct(ImageCompressor $compressor)
    {
        $this->compressor = $compressor;
    }

    /**
     * Cria:
     * - casacor/casas-conceito: apenas MostraAno vinculado à Mostra existente;
     * - outras-mostras: Mostra + MostraIdioma + MostraAno.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\MostraAno
     */
    public function novo(Request $request): MostraAno
    {
        $idioma = inertia()->getShared('idioma');

        return DB::transaction(function () use ($request, $idioma) {
            $tipo = $request->input('tipo');

            if (!$this->tipoValido($tipo)) {
                throw new InvalidArgumentException('Tipo inválido.');
            }

            if ($this->isTipoFixo($tipo)) {
                $mostra = $this->buscarMostraPorTipo($tipo);
            } else {
                $mostra = $this->criarMostra($request, $idioma);
            }

            $this->validarAnoDuplicado($mostra->id, $request->ano);

            $mostraAno = new MostraAno;
            $mostraAno->ano = $request->ano;
            $mostraAno->visivel = false;
            $mostraAno->ordem = $this->proximaOrdemMostraAno();
            $mostraAno->mostra_id = $mostra->id;
            $mostraAno->criado = Carbon::now();
            $mostraAno->save();

            return $mostraAno;
        });
    }

    /**
     * Atualiza Mostra/MostraIdioma.
     * Recebe mostras.id.
     * Se não for tipo fixo, atualiza automaticamente o único MostraAno.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Models\Mostra
     */
    public function atualizar(Request $request, $id): Mostra
    {
        $idioma = inertia()->getShared('idioma');

        return DB::transaction(function () use ($request, $id, $idioma) {
            $mostra = Mostra::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            if (!$mostra) {
                throw new InvalidArgumentException('Registro não encontrado.');
            }

            $tipoFixo = $this->isTipoFixo($mostra->slug);
            $imagemOriginal = $mostra->imagem;

            if (!$tipoFixo) {
                $mostraAno = MostraAno::query()
                    ->where([
                        'excluido' => null,
                        'mostra_id' => $mostra->id
                    ])
                    ->orderBy('ordem', 'ASC')
                    ->orderBy('id', 'DESC')
                    ->first();

                if (!$mostraAno) {
                    throw new InvalidArgumentException('Ano não encontrado para esta mostra.');
                }

                $this->validarAnoDuplicado($mostra->id, $request->ano, $mostraAno->id);

                $mostraAno->ano = $request->ano;
                $mostraAno->modificado = Carbon::now();
                $mostraAno->save();
            }

            if ($request->hasFile('img') && $request->file('img')->getError() == 0) {
                $mostra->imagem = $this->gerarNomeArquivo($request->file('img'));
            }

            if ($request->has('nome') && !$tipoFixo) {
                $mostra->slug = $this->gerarSlugUnico($request->nome, $mostra->id);
            }

            $mostra->modificado = Carbon::now();
            $mostra->save();

            if ($request->has('nome')) {
                $this->salvarMostraIdioma($mostra, $request, $idioma->id);
            }

            if ($request->hasFile('img') && $request->file('img')->getError() == 0) {
                File::ensureDirectoryExists(public_path(self::PATH_IMAGEM));

                if ($imagemOriginal && File::exists(public_path(self::PATH_IMAGEM . $imagemOriginal))) {
                    File::delete(public_path(self::PATH_IMAGEM . $imagemOriginal));
                }

                $this->compressor->compressOrFallback(
                    $request->file('img')->getRealPath(),
                    public_path(self::PATH_IMAGEM . $mostra->imagem)
                );
            }

            $this->sincronizarVisibilidadeMostra($mostra);

            return $mostra;
        });
    }

    /**
     * Atualiza rapidamente o ano pela tabela do editar.
     * Recebe mostras_anos.id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Models\MostraAno
     */
    public function atualizarAno(Request $request, $id): MostraAno
    {
        return DB::transaction(function () use ($request, $id) {
            $mostraAno = MostraAno::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->first();

            if (!$mostraAno) {
                throw new InvalidArgumentException('Registro não encontrado.');
            }

            $this->validarAnoDuplicado($mostraAno->mostra_id, $request->ano, $mostraAno->id);

            $mostraAno->ano = $request->ano;
            $mostraAno->modificado = Carbon::now();
            $mostraAno->save();

            return $mostraAno;
        });
    }

    /**
     * Exclui logicamente o MostraAno.
     * Se a Mostra for customizada e não tiver mais anos ativos, exclui também Mostra e idiomas.
     *
     * @param  int  $id
     * @return void
     */
    public function excluir($id): void
    {
        DB::transaction(function () use ($id) {
            $mostraAno = MostraAno::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->with('mostra')
                ->first();

            if (!$mostraAno) {
                throw new InvalidArgumentException('Registro não encontrado.');
            }

            $mostra = $mostraAno->mostra;

            $mostraAno->excluido = Carbon::now();
            $mostraAno->modificado = Carbon::now();
            $mostraAno->save();

            if ($mostra) {
                $temAnosAtivos = MostraAno::query()
                    ->where([
                        'mostra_id' => $mostra->id,
                        'excluido' => null
                    ])
                    ->exists();

                if (!$this->isTipoFixo($mostra->slug) && !$temAnosAtivos) {
                    $mostra->excluido = Carbon::now();
                    $mostra->modificado = Carbon::now();
                    $mostra->save();

                    MostraIdioma::query()
                        ->where([
                            'mostra_id' => $mostra->id,
                            'excluido' => null
                        ])
                        ->update([
                            'excluido' => Carbon::now(),
                            'modificado' => Carbon::now()
                        ]);
                } else {
                    $this->sincronizarVisibilidadeMostra($mostra);
                }
            }
        });
    }

    /**
     * Alterna a visibilidade do MostraAno e sincroniza a Mostra principal.
     *
     * @param  int  $id
     * @return void
     */
    public function visibilidade($id): void
    {
        DB::transaction(function () use ($id) {
            $mostraAno = MostraAno::query()
                ->where([
                    'excluido' => null,
                    'id' => $id
                ])
                ->with('mostra')
                ->first();

            if (!$mostraAno) {
                throw new InvalidArgumentException('Registro não encontrado.');
            }

            $mostraAno->visivel = !$mostraAno->visivel;
            $mostraAno->modificado = Carbon::now();
            $mostraAno->save();

            if ($mostraAno->mostra) {
                $this->sincronizarVisibilidadeMostra($mostraAno->mostra);
            }
        });
    }

    /**
     * Atualiza ordenação dos anos.
     *
     * Aceita:
     * - odr: [3, 2, 1]
     * - odr: [{ id: 3, ordem: 0 }, { id: 2, ordem: 1 }]
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function ordenar(Request $request): void
    {
        if ($request->odr && is_array($request->odr)) {
            foreach ($request->odr as $key => $value) {
                $id = is_array($value) ? ($value['id'] ?? null) : $value;
                $ordem = is_array($value) ? ($value['ordem'] ?? $key) : $key;

                if ($id) {
                    MostraAno::query()
                        ->where([
                            'excluido' => null,
                            'id' => $id
                        ])
                        ->update([
                            'ordem' => $ordem,
                            'modificado' => Carbon::now()
                        ]);
                }
            }
        }
    }

    private function criarMostra(Request $request, $idioma): Mostra
    {
        $mostra = new Mostra;
        $mostra->imagem = $this->gerarNomeArquivo($request->file('img'));
        $mostra->slug = $this->gerarSlugUnico($request->nome);
        $mostra->visivel = false;
        $mostra->ordem = $this->proximaOrdemMostra();
        $mostra->criado = Carbon::now();
        $mostra->save();

        $this->salvarMostraIdioma($mostra, $request, $idioma->id);

        File::ensureDirectoryExists(public_path(self::PATH_IMAGEM));

        $this->compressor->compressOrFallback(
            $request->file('img')->getRealPath(),
            public_path(self::PATH_IMAGEM . $mostra->imagem)
        );

        return $mostra;
    }

    private function salvarMostraIdioma(Mostra $mostra, Request $request, $idiomaId): MostraIdioma
    {
        $mostraIdioma = MostraIdioma::query()
            ->where([
                'mostra_id' => $mostra->id,
                'idioma_id' => $idiomaId,
                'excluido' => null
            ])
            ->first();

        if (!$mostraIdioma) {
            $mostraIdioma = new MostraIdioma;
            $mostraIdioma->mostra_id = $mostra->id;
            $mostraIdioma->idioma_id = $idiomaId;
            $mostraIdioma->criado = Carbon::now();
        } else {
            $mostraIdioma->modificado = Carbon::now();
        }

        $mostraIdioma->nome = $request->nome;
        $mostraIdioma->descricao = $request->descricao;
        $mostraIdioma->titulo_pagina = $request->titulo_pagina ?? '';
        $mostraIdioma->descricao_pagina = $request->descricao_pagina ?? '';
        $mostraIdioma->save();

        return $mostraIdioma;
    }

    private function buscarMostraPorTipo(string $tipo): Mostra
    {
        if (!$this->isTipoFixo($tipo)) {
            throw new InvalidArgumentException('Tipo inválido.');
        }

        $mostra = Mostra::query()
            ->where([
                'slug' => $tipo,
                'excluido' => null
            ])
            ->first();

        if (!$mostra) {
            throw new InvalidArgumentException('Mostra não encontrada para o tipo informado.');
        }

        return $mostra;
    }

    private function validarAnoDuplicado($mostraId, $ano, $ignorarId = null): void
    {
        $existe = MostraAno::query()
            ->where([
                'mostra_id' => $mostraId,
                'ano' => $ano,
                'excluido' => null
            ])
            ->when($ignorarId, function ($q) use ($ignorarId) {
                $q->where('id', '!=', $ignorarId);
            })
            ->exists();

        if ($existe) {
            throw ValidationException::withMessages([
                'ano' => 'Este ano já está cadastrado para esta mostra.'
            ]);
        }
    }

    private function gerarNomeArquivo($file): string
    {
        return md5(uniqid((string) rand(), true)) . '.' . strtolower($file->extension());
    }

    private function gerarSlugUnico(string $nome, $ignorarId = null): string
    {
        $slugBase = Str::slug($nome);
        $slug = $slugBase;
        $count = 1;

        while (
            Mostra::query()
                ->where('slug', $slug)
                ->where([
                    'excluido' => null
                ])
                ->when($ignorarId, function ($q) use ($ignorarId) {
                    $q->where('id', '!=', $ignorarId);
                })
                ->exists()
        ) {
            $slug = $slugBase . '-' . $count;
            $count++;
        }

        return $slug;
    }

    private function proximaOrdemMostra(): int
    {
        return (int) Mostra::query()
            ->where([
                'excluido' => null
            ])
            ->max('ordem') + 1;
    }

    private function proximaOrdemMostraAno(): int
    {
        return (int) MostraAno::query()
            ->where([
                'excluido' => null
            ])
            ->max('ordem') + 1;
    }

    private function sincronizarVisibilidadeMostra(Mostra $mostra): void
    {
        $mostra->visivel = MostraAno::query()
            ->where([
                'mostra_id' => $mostra->id,
                'excluido' => null,
                'visivel' => true
            ])
            ->exists();

        $mostra->modificado = Carbon::now();
        $mostra->save();
    }

    private function tipoValido(?string $tipo): bool
    {
        return $tipo && in_array($tipo, self::TIPOS, true);
    }

    private function isTipoFixo(?string $tipo): bool
    {
        return $tipo && in_array($tipo, self::TIPOS_FIXOS, true);
    }
}