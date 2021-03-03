<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CursoEtiqueta extends Model
{
    protected $table = 'lms_curso_etiquetas';
    protected $primaryKey = 'id_curso_etiqueta';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
