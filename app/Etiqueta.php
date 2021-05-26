<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Etiqueta extends Model
{
    protected $table = 'lms_etiquetas';
    protected $primaryKey = 'id_etiqueta';
    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot'
    ];
    public function cursosEtiqueta()
    {
        return $this->belongsToMany('App\Curso', 'lms_curso_etiquetas', 'id_etiqueta', 'id_curso')->where('estado', 1)
									->where('estado_curso', 'aprobado')->where('membresia_curso', '!=', 'FIN');
    }
}
