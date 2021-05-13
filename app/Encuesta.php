<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    protected $table = 'lms_encuestas';
    protected $primaryKey = 'id_encuesta';
    protected $hidden = ['pivot'];

    public function encuestaPregunta()
    {
        return $this->hasMany('App\EncuestaPregunta', 'id_encuesta');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Rol', 'lms_encuesta_roles', 'id_encuesta', 'id_rol');
    }
}
