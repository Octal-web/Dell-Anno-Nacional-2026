<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemContrato extends Model
{
    protected $table = 'imagens_contratos';

    const CREATED_AT = 'criado';
    const UPDATED_AT = 'modificado';

    public function contrato()
    {
        return $this->belongsTo(Contrato::class);
    }
}
