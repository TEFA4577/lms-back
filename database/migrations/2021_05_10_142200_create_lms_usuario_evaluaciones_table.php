<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsUsuarioEvaluacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_usuario_evaluaciones', function (Blueprint $table) {
            $table->bigIncrements('id_usuario_evaluacion');
            $table->unsignedbigInteger('id_usuario')->comment('identificador del usuario');
            $table->foreign('id_usuario')->references('id_usuario')->on('lms_usuarios')->onDelete('cascade');
            $table->unsignedbigInteger('id_curso')->comment('identificador del curso');
            $table->foreign('id_curso')->references('id_curso')->on('lms_cursos')->onDelete('cascade');
            $table->json('progreso_evaluacion')->nullable();
            $table->boolean('estado')->default(1);
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
        Schema::dropIfExists('lms_usuario_evaluaciones');
    }
}
