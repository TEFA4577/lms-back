<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'lms_modulos';
    protected $primaryKey = 'id_modulo';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function clasesModulo()
    {
        return $this->hasMany('App\Clase','id_modulo')->where('estado_clase', 1);
    }
}
