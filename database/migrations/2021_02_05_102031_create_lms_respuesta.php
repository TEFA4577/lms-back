<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsRespuesta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_respuesta', function (Blueprint $table) {
            $table->bigIncrements('id_respuesta')->comment('identificador de la respueta en el comentario');
            $table->unsignedbigInteger('id_comentario')->comment('identificador de la clase');
            $table->foreign('id_comentario')->references('id_comentario')->on('lms_comentario')->onDelete('cascade');
            $table->unsignedbigInteger('id_usuario')->comment('identificador del estudiante');
            $table->foreign('id_usuario')->references('id_usuario')->on('lms_usuarios')->onDelete('cascade');
            $table->longText('texto_respuesta')->comment('respuesta sobre comentario');
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
        Schema::dropIfExists('lms_respuesta');
    }
}
