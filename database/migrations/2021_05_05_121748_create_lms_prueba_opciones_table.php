<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsPruebaOpcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_prueba_opciones', function (Blueprint $table) {
            $table->bigIncrements('id_prueba_opcion');
            $table->unsignedBigInteger('id_prueba');
            $table->foreign('id_prueba')->references('id_prueba')->on('lms_pruebas')->onDelete('cascade');
            $table->string('texto_prueba_opcion');
            $table->boolean('respuesta_opcion');
            $table->json('valor_prueba_opcion')->nullable();
            $table->boolean('estado_prueba_opcion')->default(1)->comment('campo para eliminación lógica');
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
        Schema::dropIfExists('lms_prueba_opciones');
    }
}
