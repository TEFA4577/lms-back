<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsMembresiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_membresias', function (Blueprint $table) {
            $table->bigIncrements('id_membresia')->comment('identificador de membresia');
            $table->string('nombre_membresia')->comment('titulo de la membrecia');
            $table->string('texto_membresia')->comment('texto de membresia');
            $table->boolean('membresia')->comment('aprobar o no, membresia')->default(0);
            $table->boolean('estado_membresia')->comment('estado de la membresia')->default(1);
            $table->decimal('precio_membresia',16,2)->comment('precio de la membresia');
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
        Schema::dropIfExists('lms_membresias');
    }
}