<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    protected $table = 'lms_docentes';
    protected $primaryKey = 'id_docente';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function docenteRedes()
    {
        return $this->hasMany('App\RedSocial', 'id_usuario');
    }
    public function membresiaNoHabilitada()
    {
        return $this->belongsToMany('App\Membresia', 'lms_membresia_docentes', 'id_docente', 'id_membresia')
            ->wherePivot('estado_docente_membresia', 'no confirmado');
    }
}
