<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsEncuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_encuestas', function (Blueprint $table) {
            $table->bigIncrements('id_encuesta')->comment('identificador encuestas');
            $table->string('texto_encuesta')->comment('titulo de la encuesta');
            $table->boolean('estado_encuesta')->default(1)->comment('estado de la encuesta');
            $table->timestamps();
        });

        /*
            $table->unsignedBigInteger('id_rol')->comment('identificador del rol');
            $table->foreign('id_rol')->references('id_rol')->on('lms_roles');
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lms_encuestas');
    }
}
