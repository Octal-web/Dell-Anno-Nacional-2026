<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColecaoIdioma extends Model
{
    protected $table = 'colecoes_idiomas';

    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function colecao()
    {
        return $this->belongsTo(Colecao::class);
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}
