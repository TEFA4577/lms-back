<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    protected $table = 'lms_membresia';
    protected $primaryKey = 'id_membresia';
    protected $hidden = [
        'created_at',
        'update_at'
    ];
}
