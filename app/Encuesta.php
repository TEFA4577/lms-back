<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $table = 'lms_encuestas';
    protected $primaryKey = 'id_encuesta';
    protected $hidden = ['create_at', 'update_at'];

    public function encuestaPregunta()
    {
        return $this->hasMany('App\EncuestaPregunta', 'id_encuesta');
    }

    public function encuestaRol()
    {
        return $this->belongsTo('App\Rol', 'id_rol
        ', 'id_rol');
    }
}
