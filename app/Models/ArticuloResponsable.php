<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloResponsable extends Model
{
    protected $table = 'articulo_responsables';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'articulo_id',
        'responsable_id',
    ];
}
