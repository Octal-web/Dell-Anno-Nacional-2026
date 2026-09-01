<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcabamentoCategoriaIdioma extends Model
{
    protected $table = 'acabamentos_categorias_idiomas';

    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function acabamentoCategoria()
    {
        return $this->belongsTo(AcabamentoCategoria::class, 'categoria_id');
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}
