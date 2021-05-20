<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prueba extends Model
{
    protected $table = 'lms_pruebas';
    protected $primaryKey = 'id_prueba';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function pruebaCurso()
    {
        return $this->hasOne('App\Curso', 'id_curso', 'id_curso');
    }
    public function pruebaOpcion()
    {
        return $this->hasMany('App\PruebaOpcion', 'id_prueba')->where('estado_prueba_opcion', 1);
    }
	public function opcionCorrecta()
	{
		return $this->hasMany('App\PruebaOpcion', 'id_prueba')->where('estado_prueba_opcion', 1)->where('respuesta_opcion', 1);
	}
}
