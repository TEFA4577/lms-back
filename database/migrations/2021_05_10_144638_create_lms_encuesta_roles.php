<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsEncuestaRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_encuesta_roles', function (Blueprint $table) {
            $table->bigIncrements('id_encuesta_roles')->comment('identificador del rol en la encuesta');
            $table->unsignedbigInteger('id_encuesta')->comment('identificador de la encuesta');
            $table->foreign('id_encuesta')->references('id_encuesta')->on('lms_encuestas')->onDelete('cascade');
            $table->unsignedbigInteger('id_rol')->comment('identificador del rol');
            $table->foreign('id_rol')->references('id_rol')->on('lms_roles')->onDelete('cascade');
            $table->boolean('estado_encuesta_etiqueta')->comment('estado del rol en la encuesta')->default(1);
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
        Schema::dropIfExists('lms_encuesta_roles');
    }
}
