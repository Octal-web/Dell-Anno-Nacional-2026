<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expectativa extends Model
{
    // protected $connection = 'unicasa';

    protected $table = 'expectativa_projetos';

    protected $fillable = [
        'cliente_id',
        'cod_marca',
        'observacao_cliente',
        'expectativa_investimento',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
