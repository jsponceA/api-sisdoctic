<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyectoResponsable extends Model
{
    protected $table = "proyecto_responsables";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "proyecto_id",
        "responsable_id",
        "especialidad_id",
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, "proyecto_id", "id");
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class, "responsable_id", "id");
    }

    public function especialidad(): BelongsTo
    {
        return $this->belongsTo(Especialidad::class, "especialidad_id", "id");
    }
}

