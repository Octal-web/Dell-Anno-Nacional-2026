<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostContactRequest;
use App\Services\ContactService;
use Inertia\Inertia;

use App\Models\ProjetoContato;

class ContatoController extends Controller
{
    protected $contactService;

    public function __construct(ContactService $contactService)
    {
        parent::__construct();
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $idioma = inertia()->getShared('idioma');

        $projetos = ProjetoContato::query()
            ->where([
                'excluido' => NULL,
                'visivel' => true
            ])
            ->with([
                'projetosContatosIdiomas' => function ($q) use ($idioma) {
                    $q->whereHas('idiomas', function ($r) use ($idioma) {
                        $r->where('codigo', $idioma)
                          ->orWhere('padrao', true);
                    })
                    ->orderBy('idioma_id', 'DESC');
                }
            ])
            ->orderBy('ordem', 'ASC')
            ->orderBy('id', 'DESC')
            ->get()
            ->map(function($projeto) {
                return [
                    'id' => $projeto->id,
                    'imagem' => rafator('content/contact-projects/thumbs/' . $projeto->imagem),
                    'descricao' => $projeto->projetosContatosIdiomas->isNotEmpty() ? $projeto->projetosContatosIdiomas[0]->descricao : null,
                ];
            });

        return Inertia::render('Contato', [
            'projetos' => $projetos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function enviar(PostContactRequest $request) {
        if($request->post()){
            $data = $request->validated();
        
            $contato = $this->contactService->create($data);

            return back()->with('message', [
                'type' => 'success',
                'msg' => 'Contato enviado com sucesso!',
            ]);
        }

        return Inertia::location(route('Contato.index'));
    }
};