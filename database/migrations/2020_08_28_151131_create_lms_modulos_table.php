<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsModulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_modulos', function (Blueprint $table) {
            $table->bigIncrements('id_modulo')->comment('identificador del modulo');
            $table->foreign('id_curso')->references('id_curso')->on('lms_cursos')->onDelete('cascade');
            $table->unsignedbigInteger('id_curso')->comment('identificador del curso');
            $table->string('nombre_modulo')->comment('nombre del modulo');
            $table->string('descripcion_modulo',500)->comment('descripcion del modulo');
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
        Schema::dropIfExists('lms_modulos');
    }
}
