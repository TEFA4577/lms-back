<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsDocentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_docentes', function (Blueprint $table) {
            $table->bigIncrements('id_docente')->comment('identificador del docente');
            $table->foreign('id_usuario')->references('id_usuario')->on('lms_usuarios')->onDelete('cascade');
            $table->unsignedbigInteger('id_usuario')->comment('identificador del usuario');
            $table->integer('telefono_docente')->comment('telefono del docente');
            $table->boolean('estado_docente')->comment('estado del docente')->default(1);
            $table->string('descripcion_docente')->comment('descripcion del docente')->nullable();
            $table->string('video_presentacion')->comment('video de presentacion del docente(opcional)')->nullable();
            $table->string('cv_docente')->comment('cv del docente(opcional)')->nullable();
            $table->string('experiencia_docente')->comment('experiencia del docente(opcional)')->nullable();
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
        Schema::dropIfExists('lms_docentes');
    }
}
