<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsUsuarioCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_usuario_cursos', function (Blueprint $table) {
            $table->bigIncrements('id_usuario_curso')->comment('identificador de la curso que toma el usuario');
            $table->unsignedbigInteger('id_usuario')->comment('identificador del usuario');
            $table->foreign('id_usuario')->references('id_usuario')->on('lms_usuarios')->onDelete('cascade');
            $table->unsignedbigInteger('id_curso')->comment('identificador del curso');
            $table->foreign('id_curso')->references('id_curso')->on('lms_cursos')->onDelete('cascade');
            $table->json('progreso_curso')->comment('guardado del progreso con relacion al curso')->nullable();
            $table->string('estado_usuario_curso')->comment('estado de la curso que toma el usuario')->default('no confirmado');
            $table->string('comprobante')->comment('imagen del comprobante de pago')->nullable();
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
        Schema::dropIfExists('lms_usuario_cursos');
    }
}
