<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    protected $table = 'lms_recursos';
    protected $primaryKey = 'id_recurso';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function tipoRecurso()
    {
        return $this->hasOne('App\RecursoTipo','id_recurso_tipo');
    }
}
