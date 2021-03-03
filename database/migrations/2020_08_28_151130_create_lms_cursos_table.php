<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_cursos', function (Blueprint $table) {
            $table->bigIncrements('id_curso')->comment('identificador del curso');
            $table->foreign('id_usuario')->references('id_usuario')->on('lms_usuarios')->onDelete('cascade');
            $table->unsignedbigInteger('id_usuario')->comment('identificador del usuario creador del curso');
            $table->string('nombre_curso')->comment('nombre del curso');
            $table->string('descripcion_curso',500)->comment('descripcion del curso');
            $table->string('imagen_curso')->comment('imagen del curso');
            $table->string('estado_curso')->comment('estado del curso')->default('no enviado para revision');
            $table->string('usuario_revisor')->comment('usuario que reviso la curso')->nullable();
            $table->string('mensaje')->comment('mensaje por parte del revisor')->nullable();
            $table->decimal('precio',16,2)->comment('precio del curso');
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
        Schema::dropIfExists('lms_cursos');
    }
}
