<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsCursoEncuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_curso_encuestas', function (Blueprint $table) {
            $table->bigIncrements('id_encuesta')->comment('identificador de la encuesta');
            $table->unsignedbigInteger('id_usuario_curso')->comment('identificador del usuario');
            $table->foreign('id_usuario_curso')->references('id_usuario_curso')->on('lms_usuario_cursos')->onDelete('cascade');
            $table->string('respuestas')->comment('respuestas de la encuesta');
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
        Schema::dropIfExists('lms_curso_encuestas');
    }
}
