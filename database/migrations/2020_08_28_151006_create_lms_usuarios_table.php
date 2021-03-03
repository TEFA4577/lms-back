<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLmsUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lms_usuarios', function (Blueprint $table) {
            $table->bigIncrements('id_usuario')->comment('identificador del usuario');
            $table->foreign('id_rol')->references('id_rol')->on('lms_roles');
            $table->unsignedbigInteger('id_rol')->comment('identificador del rol');
            $table->string('nombre_usuario')->comment('nombre del usuario');
            $table->string('correo_usuario')->comment('correo del usuario')->unique();
            $table->string('estado_usuario')->comment('estado del usuario')->default('activo');
            $table->string('foto_usuario')->comment('foto del usuario')->nullable();
            $table->string('password_usuario', 500)->comment('contraseña del usuario');
            $table->integer('intentos')->comment('intentos de iniciar Sesión al sistema')->nullable();
            $table->timestamp('fecha_intento')->comment('fecha y hora del ultimo intento de Sesión al sistema')->nullable();
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
        Schema::dropIfExists('lms_usuarios');
    }
}
