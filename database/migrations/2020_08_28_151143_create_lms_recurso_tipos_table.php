<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsRecursoTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_recurso_tipos', function (Blueprint $table) {
            $table->bigIncrements('id_recurso_tipo')->comment('identificador del recurso_tipo');
            $table->string('nombre_recurso_tipo')->comment('nombre del recurso_tipo');
            $table->boolean('estado_recurso_tipo')->comment('estado del recurso_tipo')->default(1);
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
        Schema::dropIfExists('lms_recurso_tipos');
    }
}
