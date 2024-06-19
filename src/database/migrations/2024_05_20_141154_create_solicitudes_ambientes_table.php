<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesAmbientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_ambientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')
                  ->constrained('docentes')
                  ->onDelete('cascade');
            $table->foreignId('horario_disponible_id')
                  ->constrained('horarios_disponibles')
                  ->onDelete('cascade');
            $table->foreignId('grupo_id')
                  ->constrained('grupos')
                  ->onDelete('cascade');
            $table->integer('capacidad');
            $table->string('tipo_reserva');
            $table->string('razon_rechazo')->nullable();
            $table->integer('prioridad')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes_ambientes');
    }
}
