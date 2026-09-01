<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemProjetoLojaIdioma extends Model
{
    protected $table = 'imagens_projetos_lojas_idiomas';

    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function imagem()
    {
        return $this->belongsTo(ImagemProjetoLoja::class, 'imagem_id');
    }

    public function idiomas()
    {
        return $this->belongsTo(Idioma::class, 'idioma_id');
    }
}
