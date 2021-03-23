<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembresiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membresias', function (Blueprint $table) {
            $table->bigIncrements('id_membresia')->comment('identificador de membresia');
            $table->string('titulo_membresia')->comment('titulo de la membrecia');
            $table->string('texto_membresia')->comment('texto de membresia');
            $table->string('estado_membresia')->comment('estado de membresia')->default('no confirmado');
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
        Schema::dropIfExists('membresias');
    }
}
