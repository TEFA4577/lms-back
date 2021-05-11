<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PruebaOpcion extends Model
{
    protected $table = 'lms_prueba_opciones';
    protected $primaryKey = 'id_prueba_opcion';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
