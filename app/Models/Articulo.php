<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Articulo extends Model
{
    use SoftDeletes;

    protected $table = "articulos";
    protected $primaryKey = "id";
    protected $fillable = [
        "tipo_material_id",
        "denominacion_id",
        "estado_conservacion_id",
        "sala_id",
        "creado_por_usuario_id",
        "modificado_por_usuario_id",
        "eliminado_por_usuario_id",
        "numero_ficha",
        "codigo_inventario_objeto",
        "numero_registro_nacional",
        "precedencia_sitio",
        "numero_vitrina",
        "descripcion_objeto",
        "estado_integridad",
        "porcentaje_integridad",
        "diagnostico_general",
        "proceso_trabajo",
        "indentificacion_fotos",
        "observaciones",
        "foto_registro_inicial",
        "foto_registro_final",
        "foto_proceso_inicial",
        "foto_proceso_final",
        "fecha_inicio",
        "fecha_fin",
    ];

    protected $appends = [
        "fecha_creacion_format",
        "fecha_modificacion_format",
        "fecha_inicio_format",
        "fecha_fin_format",
        "foto_registro_inicial_url",
        "foto_registro_final_url",
        "foto_proceso_inicial_url",
        "foto_proceso_final_url",
    ];

    // Relaciones principales
    public function tipoMaterial(): BelongsTo
    {
        return $this->belongsTo(TipoMaterial::class, "tipo_material_id", "id");
    }

    public function denominacion(): BelongsTo
    {
        return $this->belongsTo(Denominacion::class, "denominacion_id", "id");
    }

    public function estadoConservacion(): BelongsTo
    {
        return $this->belongsTo(EstadoConservacion::class, "estado_conservacion_id", "id");
    }

    public function sala(): BelongsTo
    {
        return $this->belongsTo(Sala::class, "sala_id", "id");
    }

    public function responsables(): BelongsToMany
    {
        return $this->belongsToMany(Responsable::class, "articulo_responsables", "articulo_id", "responsable_id");
    }

    // Relaciones de auditoría
    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, "creado_por_usuario_id", "id");
    }

    public function modificadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, "modificado_por_usuario_id", "id");
    }

    public function eliminadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, "eliminado_por_usuario_id", "id");
    }

    // Atributos computados
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

    protected function fechaInicioFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_inicio']) ? now()->parse($attributes['fecha_inicio'])->format("d/m/Y") : null
        );
    }

    protected function fechaFinFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_fin']) ? now()->parse($attributes['fecha_fin'])->format("d/m/Y") : null
        );
    }

    protected function fotoRegistroInicialUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['foto_registro_inicial']) ? Storage::url("dashboard/articulos/{$attributes['foto_registro_inicial']}") : null
        );
    }

    protected function fotoRegistroFinalUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['foto_registro_final']) ? Storage::url("dashboard/articulos/{$attributes['foto_registro_final']}") : null
        );
    }

    protected function fotoProcesoInicialUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['foto_proceso_inicial']) ? Storage::url("dashboard/articulos/{$attributes['foto_proceso_inicial']}") : null
        );
    }

    protected function fotoProcesoFinalUrl(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['foto_proceso_final']) ? Storage::url("dashboard/articulos/{$attributes['foto_proceso_final']}") : null
        );
    }
}
