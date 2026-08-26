<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $connection = 'crm';

    protected $table = 'sites';

    protected $guarded = ['*'];
}
