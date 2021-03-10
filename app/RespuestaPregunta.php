<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespuestaPregunta extends Model
{
    protected $table = 'lms_respuestas_preguntas';
    protected $primaryKey = 'id_respuesta_pregunta';
    protected $hidden = [
        'created_at',
        'update_at'
    ];
    public function respuestaPregunta()
    {
        return $this->belongsTo('App\Pregunta', 'id_pregunta');
    }
}
