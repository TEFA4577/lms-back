<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreguntaFrecuente extends Model
{
    protected $table = 'lms_preguntas_frecuentes';
    protected $primaryKey = 'id_pregunta_frecuente';
    protected $hidden = [
        'created_at',
        'update_at'
    ];
    public function respuestasPreguntaFrecuente()
    {
        return $this->hasMany('App\RespuestaPreguntaFrecuente', 'id_pregunta');
    }
}
