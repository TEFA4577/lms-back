<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncuestaRol extends Model
{
    protected $table = 'lms_encuesta_roles';
    protected $primaryKey = 'id_encuesta_roles';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
