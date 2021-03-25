<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembresiaDocente extends Model
{
    protected $table = 'lms_membresia_docentes';
    protected $primaryKey = 'id_membresia_docente';
    protected $hidden = [
        'created_at',
        'update_at'
    ];

    public function membresiaSolicitada()
    {
        return $this->hasOne('App\Membresia', 'id_membresia', 'id_membresia');
    }
    public function docente()
    {
        return $this->hasOne('App\Docente', 'id_docente', 'id_docente');
    }
}