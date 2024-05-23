<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocentesSolicitudesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docentes_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')
                  ->constrained('docentes')
                  ->onDelete('cascade');
            $table->foreignId('solicitud_ambiente_id')
                  ->constrained('solicitudes_ambientes')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('docentes_solicitudes');
    }
}
