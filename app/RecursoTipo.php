<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecursoTipo extends Model
{
    protected $table = 'lms_recurso_tipos';
    protected $primaryKey = 'id_recurso_tipo';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
