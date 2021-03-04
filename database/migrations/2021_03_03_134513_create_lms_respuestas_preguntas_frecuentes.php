<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsRespuestasPreguntasFrecuentes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_respuestas_preguntas_frecuentes', function (Blueprint $table) {
            $table->bigIncrements('id_respuesta_pregunta')->comment('identificador de respuesta a pregunta frecuente');
            $table->unsignedbigInteger('id_pregunta')->comment('identificador de pregunta frecuente');
            $table->foreign('id_pregunta')->references('id_pregunta_frecuente')->on('lms_preguntas_frecuentes')->onDelete('cascade');
            $table->string('texto_respuesta_pregunta')->comment('Respuesta a pregunta frecuente que se realiza');
            $table->boolean('estado_respuesta_pregunta')->comment('estado de la respuesta')->default(1);
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
        Schema::dropIfExists('lms_respuestas_preguntas_frecuentes');
    }
}
