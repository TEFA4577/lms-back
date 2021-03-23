<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EncuestaRespuesta extends Model
{
    protected $table = 'lms_encuesta_respuestas';
    protected $primaryKey = 'id_encuesta_respuesta';
    protected $hidden = ['create_at', 'update_at'];

    public function respuestaEncuesta()
    {
        return $this->belongsTo('App\EncuestaPregunta', 'id_encuesta_pregunta');
    }
    public function usuarioRespuestaEncuesta()
    {
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }
}
