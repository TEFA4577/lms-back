<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioEvaluacion extends Model
{
    protected $table = 'lms_usuario_evaluaciones';
    protected $primaryKey = 'id_usuario_evaluacion';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function cursoSolicitado()
    {
        return $this->hasOne('App\Curso', 'id_curso', 'id_curso');
    }
    public function usuario()
    {
        return $this->hasOne('App\Usuario', 'id_usuario', 'id_usuario');
    }
}
