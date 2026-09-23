<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContratoIdioma extends Model
{
    protected $table = 'contratos_idiomas';

    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}
