<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'lms_roles';
    protected $primaryKey = 'id_rol';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
