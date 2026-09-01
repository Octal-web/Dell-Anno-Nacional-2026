<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcabamentoCategoria extends Model {
    protected $table = 'acabamentos_categorias';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function acabamentosCategoriasIdiomas()
    {
        return $this->hasMany(AcabamentoCategoriaIdioma::class, 'categoria_id');
    }

    public function acabamentos()
    {
        return $this->hasMany(Acabamento::class, 'categoria_id');
    }
}
