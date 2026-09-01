<?php

namespace App\Http\Controllers;

use App\Models\Conteudo;
use App\Models\Loja;
use App\Models\Mostra;
use App\Models\Pagina;
use App\Models\Post;
use App\Models\Ambiente;
use App\Models\ProjetoLoja;
use App\Models\Showroom;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController
{
    public function __invoke()
    {
        //TODO: SmartMaterials colocar quando tiver Controller
        $sitemap = Sitemap::create();

        $paginas = Pagina::query()
            ->where('excluido', null)
            ->get();

        foreach ($paginas as $pagina) {
            $route = $pagina->controladora . '.' . $pagina->acao;

            $ultimaModificacao = Conteudo::query()
                ->where([
                    'excluido' => NULL,
                    'controladora' => $pagina->controladora,
                    'acao' => $pagina->acao
                ])
                ->orderByDesc('modificado')
                ->first();;

            if (
                Route::has($route) &&
                $pagina->acao === 'index'
            ) {
                $sitemap->add(
                    Url::create(route($route))
                        ->setLastModificationDate(
                            $ultimaModificacao->modificado ?? $ultimaModificacao->criado ?? $pagina->modificado ?? $pagina->criado
                        )
                        ->setPriority(($pagina->controladora === 'Politicas' || $pagina->controladora === 'Manual') ? 0.3 : 1.0)
                );
            }
        }

        $subsitesPath = realpath(base_path('../subsites'));

        if (File::isDirectory($subsitesPath)) {
            collect(File::directories($subsitesPath))
                ->each(function ($directory) use ($sitemap) {
                    $slug = basename($directory);

                    if (str_contains($slug, '.old')) {
                        return;
                    }

                    $sitemap->add(
                        Url::create(
                            config('url') . $slug
                        )
                            ->setLastModificationDate(
                                Carbon::createFromTimestamp(
                                    File::lastModified($directory)
                                )
                            )
                            ->setPriority(1.0)
                    );
                });
        }

        Site::query()
            ->where([
                'excluido' => null,
                'marca_id' => 2
            ])
            ->whereNotIn('slug', [
                'loja-teste',
            ])
            ->get()
            ->each(function ($item) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('LandingPage.index', [
                            'slug' => $item->slug,
                        ])
                    )
                        ->setLastModificationDate(Carbon::parse($item->modificado ?? $item->criado))
                        ->setPriority(0.7)
                );
            });

        Ambiente::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($item) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('Produtos.produto', [
                            'slug' => $item->slug,
                        ])
                    )
                        ->setLastModificationDate($item->modificado ?? $item->criado)
                        ->setPriority(0.6)
                );
            });

        Loja::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($loja) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('Lojas.loja', [
                            'slug' => $loja->slug,
                        ])
                    )
                        ->setLastModificationDate($loja->modificado ?? $loja->criado)
                        ->setPriority(0.6)
                );
            });

        ProjetoLoja::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($loja) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('Lojas.Projetos.projeto', [
                            'slug' => $loja->slug,
                        ])
                    )
                        ->setLastModificationDate($loja->modificado ?? $loja->criado)
                        ->setPriority(0.6)
                );
            });

        Showroom::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($item) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('Showrooms.showroom', [
                            'slug' => $item->slug,
                        ])
                    )
                        ->setLastModificationDate($item->modificado ?? $item->criado)
                        ->setPriority(0.6)
                );
            });

        Post::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($item) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('Blog.post', [
                            'slug' => $item->slug,
                        ])
                    )
                        ->setLastModificationDate($item->modificado ?? $item->criado)
                        ->setPriority(0.6)
                );
            });

        Mostra::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($item) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('Mostras.mostra', [
                            'slug' => $item->slug,
                        ])
                    )
                        ->setLastModificationDate($item->modificado ?? $item->criado)
                        ->setPriority(0.6)
                );
            });

        return $sitemap;
    }
}
