<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntervencionProceso extends Model
{
    protected $table = "intervencion_procesos";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        "intervencion_id",
        "area_material_intervenido",
        "intervencion_tratamiento",
        "insumo_producto_herramiental",
        "procedimiento",
    ];

}

