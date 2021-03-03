<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'lms_clases';
    protected $primaryKey = 'id_clase';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function recursosClase()
    {
        return $this->hasMany('App\Recurso','id_clase');
    }
}
