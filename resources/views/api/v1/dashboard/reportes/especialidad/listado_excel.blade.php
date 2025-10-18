<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<table>
    <thead>
    <tr>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Nombre</th>
        <th style="background-color: #2c61ed; color: white; font-weight: bold;">Descripción</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($especialidades as $especialidad)
        <tr>
            <td style="border: 1px solid #000;">{{ $especialidad->nombre }}</td>
            <td style="border: 1px solid #000;">{{ $especialidad->descripcion ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('especialidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('modificado_por_usuario_id')->nullable();
            $table->unsignedBigInteger('eliminado_por_usuario_id')->nullable();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->datetimes();
            $table->softDeletesDatetime();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('especialidades');
    }
};

