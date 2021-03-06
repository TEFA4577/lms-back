<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembresiaDocente extends Model
{
    protected $table = 'lms_membresia_docentes';
    protected $primaryKey = 'id_membresia_usuario';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function membresiaSolicitada()
    {
        return $this->hasOne('App\Membresia', 'id_membresia', 'id_membresia');
    }
    public function usuario()
    {
        return $this->hasOne('App\Usuario', 'id_usuario', 'id_usuario');
    }
    public function membresiaCurso()
    {
        return $this->belongsToMany('App\Curso', 'lms_membresia_cursos', 'id_membresia_usuario', 'id_curso');
    }
}
