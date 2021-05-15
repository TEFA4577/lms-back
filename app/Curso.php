<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'lms_cursos';
    protected $primaryKey = 'id_curso';
    protected $hidden = ['pivot'];
    public function etiquetasCurso()
    {
        return $this->belongsToMany('App\Etiqueta', 'lms_curso_etiquetas', 'id_curso', 'id_etiqueta');
    }
    public function modulosCurso()
    {
        return $this->hasMany('App\Modulo', 'id_curso');
    }
    public function cursoEstudiante()
    {
        return $this->hasMany('App\UsuarioCurso', 'id_curso', 'id_curso');
    }
    public function membresiaDocente()
    {
        return $this->belongsToMany('\App\MembresiaDocente','lms_membresia_cursos', 'id_curso', 'id_membresia_usuario');
    }
    public function cursoEvaluacion()
    {
        return $this->hasMany('App\UsuarioEvaluacion', 'id_curso', 'id_curso');
    }
}
