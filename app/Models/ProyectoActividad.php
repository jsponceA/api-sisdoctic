<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyectoActividad extends Model
{
    protected $table = "proyecto_actividades";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        "proyecto_id",
        "nombre_actividad",
        "descripcion_actividad",
        "fecha_programada",
        "responsable_actividad",
        "estado_actividad",
    ];
}

