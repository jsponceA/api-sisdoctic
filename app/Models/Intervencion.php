<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intervencion extends Model
{
    use SoftDeletes;

    protected $table = "intervenciones";
    protected $primaryKey = "id";
    protected $fillable = [
        "creado_por_usuario_id",
        "modificado_por_usuario_id",
        "eliminado_por_usuario_id",
        "categoria_id",
        "denominacion_id",
        "director_encargado_id",
        "conservador_responsable_id",
        // I. DATOS DE IDENTIFICACIÓN
        "numero_ficha",
        "fecha_recepcion",
        "fecha_entrega",
        "numero_registro_nacional",
        "numero_inventario",
        "codigo_museo",
        "otros_codigos",
        "cultura_estilo_autor",
        "periodo_epoca",
        "procedencia",
        "descripcion",
        // II. DATOS TÉCNICOS
        "material",
        "tecnicas",
        "alto",
        "largo",
        "ancho",
        "profundidad",
        "diametro_maximo",
        "diametro_minimo",
        "peso_inicial",
        "peso_final",
        // III. ESTADO DE CONSERVACIÓN
        "integridad",
        "numero_fragmentos",
        "tipo_dano",
        "otros_tipo_dano",
        "agentes_deterioro",
        "condiciones_exposicion_almacenaje",
        "intervenciones_anteriores",
        "analisis_realizados",
        "diagnostico",
        // IV. PROCESO DE INTERVENCIÓN
        "resultado_tiempo_empleado",
        "embalaje_soporte",
        "observaciones",
        "recomendaciones",
    ];

    protected $casts = [
        'integridad' => 'array',
        'tipo_dano' => 'array',
        'agentes_deterioro' => 'array',
    ];

    protected $appends = [
        "fecha_creacion_format",
        "fecha_modificacion_format",
        "fecha_recepcion_format",
        "fecha_entrega_format",
    ];

    // Relaciones principales
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, "categoria_id", "id");
    }

    public function denominacion(): BelongsTo
    {
        return $this->belongsTo(Denominacion::class, "denominacion_id", "id");
    }

    public function directorEncargado(): BelongsTo
    {
        return $this->belongsTo(Responsable::class, "director_encargado_id", "id");
    }

    public function conservadorResponsable(): BelongsTo
    {
        return $this->belongsTo(Responsable::class, "conservador_responsable_id", "id");
    }

    public function procesos(): HasMany
    {
        return $this->hasMany(IntervencionProceso::class, "intervencion_id", "id");
    }

    public function vistaGenerales(): HasMany
    {
        return $this->hasMany(IntervencionVistaGeneral::class, "intervencion_id", "id");
    }

    public function vistaDetalles(): HasMany
    {
        return $this->hasMany(IntervencionVistaDetalle::class, "intervencion_id", "id");
    }

    public function vistaIntervenciones(): HasMany
    {
        return $this->hasMany(IntervencionVistaIntervencion::class, "intervencion_id", "id");
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
            get: fn($value, array $attributes) => !empty($attributes['created_at']) ? now()->parse($attributes['created_at'])->format("d/m/Y h:i A") : null
        );
    }

    protected function fechaModificacionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['updated_at']) ? now()->parse($attributes['updated_at'])->format("d/m/Y h:i A") : null
        );
    }

    protected function fechaRecepcionFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_recepcion']) ? now()->parse($attributes['fecha_recepcion'])->format("d/m/Y") : null
        );
    }

    protected function fechaEntregaFormat(): Attribute
    {
        return Attribute::make(
            get: fn($value, array $attributes) => !empty($attributes['fecha_entrega']) ? now()->parse($attributes['fecha_entrega'])->format("d/m/Y") : null
        );
    }
}
