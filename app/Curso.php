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

}
