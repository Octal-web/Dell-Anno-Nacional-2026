<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $table = 'leads';

    protected $connection = '8poroito';

    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cep',
        'uf',
        'cidade',
        'conversoes',
        'cliente',
        'projeto',
        'token',
        'expectativa_investimento',
        'entrada',
        'dispositivo',
        'posicao_formulario',
        'origem',
        'campanha',
        'grupo',
        'anuncio',
        'termo',
    ];

    protected $casts = [
        'criado' => 'datetime',
    ];
}
