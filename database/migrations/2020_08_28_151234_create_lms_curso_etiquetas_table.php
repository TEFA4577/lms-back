<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsCursoEtiquetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_curso_etiquetas', function (Blueprint $table) {
            $table->bigIncrements('id_curso_etiqueta')->comment('identificador de la etiqueta en el curso');
            $table->unsignedbigInteger('id_curso')->comment('identificador del curso');
            $table->foreign('id_curso')->references('id_curso')->on('lms_cursos')->onDelete('cascade');
            $table->unsignedbigInteger('id_etiqueta')->comment('identificador de la curso');
            $table->foreign('id_etiqueta')->references('id_etiqueta')->on('lms_etiquetas')->onDelete('cascade');
            $table->boolean('estado_curso_etiqueta')->comment('estado de la etiqueta en el curso')->default(1);
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
        Schema::dropIfExists('lms_curso_etiquetas');
    }
}
