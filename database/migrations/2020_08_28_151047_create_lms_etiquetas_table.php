<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsEtiquetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_etiquetas', function (Blueprint $table) {
            $table->bigIncrements('id_etiqueta')->comment('identificador de la etiqueta');
            $table->string('nombre_etiqueta')->comment('nombre de la etiqueta')->unique();
            $table->string('descripcion_etiqueta', 500)->comment('descripcion de la etiqueta');
            $table->string('imagen_etiqueta')->comment('imagen de la etiqueta')->nullable();
            $table->boolean('estado_etiqueta')->comment('estado de la etiqueta')->default(1);
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
        Schema::dropIfExists('lms_etiquetas');
    }
}
