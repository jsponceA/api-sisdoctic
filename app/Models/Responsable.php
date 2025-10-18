<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Responsable extends Model
{
    use SoftDeletes;

    protected $table = "responsables";
    protected $primaryKey = "id";
    protected $fillable = [
        "creado_por_usuario_id",
        "modificado_por_usuario_id",
        "eliminado_por_usuario_id",
        "nombres",
        "apellidos",
        "correo",
        "tipo_documento",
        "numero_documento",
        "telefono"
    ];

    protected $appends = [
        "fecha_creacion_format",
        "fecha_modificacion_format",
        "nombre_completo"
    ];

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, "creado_por_usuario_id","id");
    }

    public function modificadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, "modificado_por_usuario_id","id");
    }

    public function eliminadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, "eliminado_por_usuario_id","id");
    }

    protected function fechaCreacionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['created_at']) ? now()->parse($attributes['created_at'])->format("d/m/Y h:i: A") : null
        );
    }

    protected function fechaModificacionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['updated_at']) ? now()->parse($attributes['updated_at'])->format("d/m/Y h:i: A") : null
        );
    }

    protected function nombreCompleto(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => trim(($attributes['nombres'] ?? '') . ' ' . ($attributes['apellidos'] ?? ''))
        );
    }
}
