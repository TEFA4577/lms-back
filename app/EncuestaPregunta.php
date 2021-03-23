<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncuestaPregunta extends Model
{
    protected $table = 'lms_encuesta_preguntas';
    protected $primaryKey = 'id_encuesta_pregunta';
    protected $hidden = ['create_at', 'update_at'];

    public function preguntaEncuesta()
    {
        return $this->belongsTo('App\Encuesta', 'id_encuesta');
    }
    public function respuestaEncuesta()
    {
        return $this->hasMany('App\EncuestaRespuesta', 'id_encuesta_respuesta');
    }
}
