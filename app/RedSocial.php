<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RedSocial extends Model
{
    protected $table = 'lms_usuario_redes';
    protected $primaryKey = 'id_usuario_red';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
