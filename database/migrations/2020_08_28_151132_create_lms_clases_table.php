<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsClasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_clases', function (Blueprint $table) {
            $table->bigIncrements('id_clase')->comment('identificador de la clase');
            $table->foreign('id_modulo')->references('id_modulo')->on('lms_modulos')->onDelete('cascade');
            $table->unsignedbigInteger('id_modulo')->comment('identificador del modulo');
            $table->string('titulo_clase')->comment('titulo de la clase con el que se mostrara en el sistema');
            $table->longText('video_clase')->commet('video principal de la clase a desarrollar');
            $table->string('descripcion_clase',500)->comment('descripcion del clase');
            $table->boolean('estado_clase')->comment('estado del clase')->default(1);
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
        Schema::dropIfExists('lms_clases');
    }
}
