<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsPreguntas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_preguntas', function (Blueprint $table) {
            $table->bigIncrements('id_pregunta')->comment('identificador de preguntas frecuentes');
            $table->string('texto_pregunta', 200)->comment('pregunta frecuente realizada');
            $table->boolean('estado_pregunta')->comment('estado de la pregunta frecuente')->default(1);
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
        Schema::dropIfExists('lms_preguntas');
    }
}
