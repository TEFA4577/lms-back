<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsRecursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_recursos', function (Blueprint $table) {
            $table->bigIncrements('id_recurso')->comment('identificador del recurso');
            $table->foreign('id_clase')->references('id_clase')->on('lms_clases')->onDelete('cascade');
            $table->unsignedbigInteger('id_clase')->comment('identificador de la clase');
            $table->foreign('id_recurso_tipo')->references('id_recurso_tipo')->on('lms_recurso_tipos')->onDelete('cascade');
            $table->unsignedbigInteger('id_recurso_tipo')->comment('identificador del tipo de recurso');
            $table->string('link_recurso')->comment('link de alojamiento del recurso en el servidor');
            $table->boolean('estado_recurso')->comment('estado del recurso')->default(1);
            $table->string('nombre_recurso')->comment('nombre del recurso con el que se guardo en el servidor');
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
        Schema::dropIfExists('lms_recursos');
    }
}
