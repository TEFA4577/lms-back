<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsUsuarioRedesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_usuario_redes', function (Blueprint $table) {
            $table->bigIncrements('id_usuario_red')->comment('identificador del usario red');
            $table->foreign('id_usuario')->references('id_usuario')->on('lms_usuarios')->onDelete('cascade');
            $table->unsignedbigInteger('id_usuario')->comment('identificador del usuario');
            $table->string('url_red')->comment('link de la red social del usuario');
            $table->string('tipo_red')->comment('tipo de red social');
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
        Schema::dropIfExists('lms_usuario_redes');
    }
}
