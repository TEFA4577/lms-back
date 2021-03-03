<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_roles', function (Blueprint $table) {
            $table->bigIncrements('id_rol')->comment('identificador del rol');
            $table->string('nombre_rol')->comment('nombre del rol');
            $table->boolean('estado_rol')->comment('estado del rol')->default(1);
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
        Schema::dropIfExists('lms_roles');
    }
}
