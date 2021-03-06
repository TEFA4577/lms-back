<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioCurso extends Model
{
    protected $table = 'lms_usuario_cursos';
    protected $primaryKey = 'id_usuario_curso';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function cursoSolicitado()
    {
        return $this->hasOne('App\Curso', 'id_curso', 'id_curso')->where('estado', 1);
    }
    public function usuario()
    {
        return $this->hasOne('App\Usuario', 'id_usuario', 'id_usuario');
    }
    public function usuarioCursos()
    {
        return $this->belongsToMany('App\Curso', 'lms_usuario_cursos', 'id_usuario', 'id_curso', 'App\Usuario', 'id_usuario', 'id_usuario')
            ->wherePivot('estado_usuario_curso', 'adquirido');
    }
}
