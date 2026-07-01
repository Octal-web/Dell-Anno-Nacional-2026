<?php

namespace App\Http\Controllers;

use App\Models\Acabamento;
use App\Models\Conteudo;
use App\Models\Loja;
use App\Models\Mostra;
use App\Models\Pagina;
use App\Models\Post;
use App\Models\Produto;
use App\Models\ProjetoLoja;
use App\Models\Showroom;
use Illuminate\Support\Facades\Route;
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

        Produto::query()
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

        Acabamento::query()
            ->where([
                'excluido' => null,
                'visivel' => true
            ])
            ->get()
            ->each(function ($item) use ($sitemap) {
                $sitemap->add(
                    Url::create(
                        route('Acabamentos.acabamento', [
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
