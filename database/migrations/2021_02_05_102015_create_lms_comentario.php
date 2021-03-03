<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsComentario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_comentario', function (Blueprint $table) {
            $table->bigIncrements('id_comentario')->comment('identificador del comentario en el curso');
            $table->unsignedbigInteger('id_clase')->comment('identificador de la clase');
            $table->foreign('id_clase')->references('id_clase')->on('lms_clases')->onDelete('cascade');
            $table->unsignedbigInteger('id_usuario')->comment('identificador del estudiante');
            $table->foreign('id_usuario')->references('id_usuario')->on('lms_usuarios')->onDelete('cascade');
            $table->longText('texto_comentario')->comment('comentario sobre la clase');
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
        Schema::dropIfExists('lms_comentario');
    }
}
