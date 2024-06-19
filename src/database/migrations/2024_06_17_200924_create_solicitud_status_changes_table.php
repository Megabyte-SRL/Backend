<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudStatusChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitud_status_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_ambiente_id')
                  ->constrained('solicitudes_ambientes')
                  ->onDelete('cascade');
            $table->string('estado_antiguo');
            $table->string('estado_nuevo');
            $table->timestamp('fecha');
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
        Schema::dropIfExists('solicitud_status_changes');
    }
}
