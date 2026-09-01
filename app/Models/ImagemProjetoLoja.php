<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagemProjetoLoja extends Model
{
    protected $table = 'imagens_projetos_lojas';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    protected $fillable = ['*'];

    protected $guarded = ['id'];

    public function projeto()
    {
        return $this->belongsTo(ProjetoLoja::class, 'projeto_loja_id');
    }

    public function imagensIdiomas()
    {
        return $this->hasMany(ImagemProjetoLojaIdioma::class, 'imagem_id');
    }

    public function colecoes()
    {
        return $this->belongsToMany(Colecao::class, 'imagens_colecoes', 'imagem_id', 'colecao_id');
    }

    public function acabamentos()
    {
        return $this->belongsToMany(Acabamento::class, 'imagens_acabamentos', 'imagem_id', 'acabamento_id');
    }
}
