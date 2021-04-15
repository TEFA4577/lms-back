<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    protected $table = 'lms_membresias';
    protected $primaryKey = 'id_membresia';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function membresiaDocente()
    {
        return $this->hasMany('App\MembresiaDocente', 'id_membresia', 'id_membresia');
    }
}
