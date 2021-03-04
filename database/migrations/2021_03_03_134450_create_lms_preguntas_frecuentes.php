<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsPreguntasFrecuentes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_preguntas_frecuentes', function (Blueprint $table) {
            $table->bigIncrements('id_pregunta_frecuente')->comment('identificador de preguntas frecuentes');
            $table->string('texto_pregunta_frecuente', 200)->comment('pregunta frecuente realizada');
            $table->boolean('estado_pregunta_frecuente')->comment('estado de la pregunta frecuente')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_preguntas_frecuentes');
    }
}
