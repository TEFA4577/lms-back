<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsMembresiaDocentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_membresia_docentes', function (Blueprint $table) {
            $table->bigIncrements('id_membresia_docente')->comment('identificador de docente membresia');
            $table->unsignedbigInteger('id_docente')->comment('identificador de la tabla docente');
            $table->foreign('id_docente')->references('id_docente')->on('lms_docentes')->onDelete('cascade');
            $table->unsignedbigInteger('id_membresia');
            $table->foreign('id_membresia')->references('id_membresia')->on('lms_membresias')->onDelete('cascade');
            $table->string('comprobante')->comment('imagen del comprobante de pago')->nullable();
            $table->string('estado_membresia_docente')->comment('estado de la menbresia que toma el docente')->default('no confirmado');
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
        Schema::dropIfExists('lms_membresia_docentes');
    }
}
