<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroRecepcionDestino extends Model
{
    protected $table = "registro_recepcion_destino";
    protected $primaryKey = "id";
    public $timestamps = false;

    protected $fillable = [
        "registro_recepcion_id",
        "responsable_id"
    ];

    // Relaciones
    public function registroRecepcion(): BelongsTo
    {
        return $this->belongsTo(RegistroRecepcion::class, "registro_recepcion_id");
    }

    public function responsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class, "responsable_id");
    }
}
