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
            $table->foreignId('usuario_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('horario_disponible_id')
                  ->constrained('horarios_disponibles')
                  ->onDelete('cascade');
            $table->integer('capacidad');
            $table->string('materia');
            $table->string('estado');
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
