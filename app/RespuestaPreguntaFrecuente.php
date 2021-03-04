<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RespuestaPreguntaFrecuente extends Model
{
    protected $table = 'lms_respuestas_preguntas_frecuentes';
    protected $primaryKey = 'id_respuesta_pregunta';
    protected $hidden = [
        'created_at',
        'update_at'
    ];
    public function preguntasFrecuentesRespuesta()
    {
        return $this->belongsTo('App\PreguntaFrecuente', 'id_pregunta_frecuente');
    }
}
