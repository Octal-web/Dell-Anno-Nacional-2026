<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;

use Inertia\Inertia;

class InspiracaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Manager/Inspiracao/index');
    }
};
