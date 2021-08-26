<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'lms_preguntas';
    protected $primaryKey = 'id_pregunta';
    protected $hidden = [
        'created_at',
        'update_at'
    ];
    public function preguntaRespuesta()
    {
        return $this->hasMany('App\RespuestaPregunta', 'id_pregunta')->where('estado_respuesta_pregunta', 1);
    }
}
