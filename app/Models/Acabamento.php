<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acabamento extends Model {
    protected $table = 'acabamentos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function acabamentosIdiomas()
    {
        return $this->hasMany(AcabamentoIdioma::class, 'acabamento_id');
    }

    public function categoria()
    {
        return $this->belongsTo(AcabamentoCategoria::class, 'categoria_id');
    }
    
    public function imagens()
    {
        return $this->belongsToMany(ImagemProjetoLoja::class, 'imagens_acabamentos', 'acabamento_id', 'imagem_id');
    }
}
