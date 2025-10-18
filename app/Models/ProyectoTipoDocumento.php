<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyectoTipoDocumento extends Model
{
    protected $table = "proyecto_tipos_documento";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "proyecto_id",
        "tipo_documento_id",
        "dias_plazo",
        "penalidad",
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, "proyecto_id", "id");
    }

    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, "tipo_documento_id", "id");
    }
}

