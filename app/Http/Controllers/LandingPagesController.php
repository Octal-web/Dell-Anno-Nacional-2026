<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LandingPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        if (!$slug) {
            return redirect()->route('Home.index');
        }

        $notifyCookie = request()->cookie('notify-cookies');
        $rejectCookie = request()->cookie('reject-cookies');

        $url = request()->query() 
            ? 'https://crmunicasa.com.br/loja/dellanno/' . $slug . '?' . http_build_query(request()->query()) 
            : 'https://crmunicasa.com.br/loja/dellanno/' . $slug;

        $response = Http::withHeaders([
            'X-Notify-Cookies' => $notifyCookie,
            'X-Reject-Cookies' => $rejectCookie,
        ])->get($url);
        
         return response($response->body(), 200)->header('Content-Type', 'text/html');

        if ($response->ok()) {
            return response($response->body(), 200)->header('Content-Type', 'text/html');
        } else {
            return redirect()->route('Home.index');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function cadastroConcluido($slug, $token)
    {
        if (!$slug) {
            return redirect()->route('Home.index');
        }

        $notifyCookie = request()->cookie('notify-cookies');
        $rejectCookie = request()->cookie('reject-cookies');

        $url = request()->query() 
            ? 'https://crmunicasa.com.br/loja/dellanno/' . $slug . '/contato/concluido/' . $token . '?' . http_build_query(request()->query()) 
            : 'https://crmunicasa.com.br/loja/dellanno/' . $slug . '/contato/concluido/' . $token;

        $response = Http::withHeaders([
            'X-Notify-Cookies' => $notifyCookie,
            'X-Reject-Cookies' => $rejectCookie,
        ])->get($url);

        if ($response->ok()) {
            return response($response->body(), 200)->header('Content-Type', 'text/html');
        } else {
            return redirect()->route('Home.index');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function politicaDePrivacidade($slug)
    {
        if (!$slug) {
            return redirect()->route('Home.index');
        }

        $notifyCookie = request()->cookie('notify-cookies');
        $rejectCookie = request()->cookie('reject-cookies');

        $url = request()->query() 
            ? 'https://crmunicasa.com.br/loja/dellanno/' . $slug . '/politica-de-privacidade/' . '?' . http_build_query(request()->query()) 
            : 'https://crmunicasa.com.br/loja/dellanno/' . $slug . '/politica-de-privacidade/';

        $response = Http::withHeaders([
            'X-Notify-Cookies' => $notifyCookie,
            'X-Reject-Cookies' => $rejectCookie,
        ])->get($url);

        if ($response->ok()) {
            return response($response->body(), 200)->header('Content-Type', 'text/html');
        } else {
            return redirect()->route('Home.index');
        }
    }
    
    /**
     * Display a listing of the resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function politicaDeCookies($slug)
    {
        if (!$slug) {
            return redirect()->route('Home.index');
        }

        $notifyCookie = request()->cookie('notify-cookies');
        $rejectCookie = request()->cookie('reject-cookies');

        $url = request()->query() 
            ? 'https://crmunicasa.com.br/loja/dellanno/' . $slug . '/politica-de-cookies/' . '?' . http_build_query(request()->query()) 
            : 'https://crmunicasa.com.br/loja/dellanno/' . $slug . '/politica-de-cookies/';

        $response = Http::withHeaders([
            'X-Notify-Cookies' => $notifyCookie,
            'X-Reject-Cookies' => $rejectCookie,
        ])->get($url);

        if ($response->ok()) {
            return response($response->body(), 200)->header('Content-Type', 'text/html');
        } else {
            return redirect()->route('Home.index');
        }
    }
}