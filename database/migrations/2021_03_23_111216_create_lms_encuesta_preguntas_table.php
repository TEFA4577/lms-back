<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsEncuestaPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_encuesta_preguntas', function (Blueprint $table) {
            $table->bigIncrements('id_encuesta_pregunta')->comment('identificador de las preguntas');
            $table->unsignedBigInteger('id_encuesta')->comment('identificador de la encuesta');
            $table->foreign('id_encuesta')->references('id_encuesta')->on('lms_encuestas')->onDelete('cascade');
            $table->string('texto_encuesta_pregunta')->comment('texto de la pregunta');
            $table->boolean('estado_encuesta_pregunta')->default(1)->comment('estado de la pregunta');
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
        Schema::dropIfExists('lms_encuesta_preguntas');
    }
}
