<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjetoContato extends Model {
    protected $table = 'projetos_contatos';
    
    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function projetosContatosIdiomas()
    {
        return $this->hasMany(ProjetoContatoIdioma::class);
    }
}
