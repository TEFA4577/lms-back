<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class Usuario extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'lms_usuarios';
    protected $primaryKey = 'id_usuario';
    protected $hidden = [
        'password_usuario',
        'remember_token',
        'intentos',
        'fecha_intento',
        'created_at',
        'updated_at'
    ];
    public function datosDocente()
    {
        return $this->hasOne('App\Docente', 'id_usuario');
    }
    public function redesDocente()
    {
        return $this->hasMany('App\RedSocial','id_usuario');
    }
    public function usuarioCursos()
    {
        return $this->belongsToMany('App\Curso', 'lms_usuario_cursos', 'id_usuario', 'id_curso')
            ->wherePivot('estado_usuario_curso', 'adquirido');
    }
    public function usuarioCursosNoHabilitado()
    {
        return $this->belongsToMany('App\Curso', 'lms_usuario_cursos', 'id_usuario', 'id_curso')
            ->wherePivot('estado_usuario_curso', 'no confirmado');
    }
    public function rolUsuario()
    {
        return $this->belongsTo('App\Rol', 'id_rol', 'id_rol');
    }
    public function usuarioComentario()
    {
        return $this->hasMany('App\Comentario','id_comentario');
    }
    public function usuarioRespuestaComentario()
    {
        return $this->hasMany('App\respuesta','id_respuesta');
    }
    public function usuarioRespuestaEncuesta()
    {
        return $this->hasMany('App\EncuestaRespuesta','id_encuesta_respuesta');
    }
}
