<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $table = 'contratos';

    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function imagens()
    {
        return $this->hasMany(ImagemContrato::class);
    }

    public function contratosIdiomas()
    {
        return $this->hasMany(ContratoIdioma::class);
    }
}
