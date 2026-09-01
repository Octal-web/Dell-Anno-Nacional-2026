<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colecao extends Model {
    protected $table = 'colecoes';

    protected $fillable = ['ordem'];
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function colecoesIdiomas()
    {
        return $this->hasMany(ColecaoIdioma::class);
    }

    public function imagens()
    {
        return $this->belongsToMany(ImagemProjetoLoja::class, 'imagens_colecoes', 'colecao_id', 'imagem_id');
    }

    public function ambiente()
    {
        return $this->belongsTo(Ambiente::class, 'ambiente_id');
    }
}
