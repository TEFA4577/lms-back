<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsEncuestaRespuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_encuesta_respuestas', function (Blueprint $table) {
            $table->bigIncrements('id_encuesta_respuesta')->comment('identificador de las respuestas');
            $table->unsignedBigInteger('id_encuesta_pregunta')->comment('identificador de la pregunta');
            $table->foreign('id_encuesta_pregunta')->references('id_encuesta_pregunta')->on('lms_encuesta_preguntas')->onDelete('cascade');
            $table->unsignedBigInteger('id_usuario')->comment('identificador del usuario');
            $table->foreign('id_usuario')->references('id_usuario')->on('lms_usuarios')->onDelete('cascade');
            $table->string('texto_encuesta_respuesta')->comment('texto de la respuesta');
            $table->boolean('estado_encuesta_respuesta')->default(1)->comment('estado de la respuesta');
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
        Schema::dropIfExists('lms_encuesta_respuestas');
    }
}
