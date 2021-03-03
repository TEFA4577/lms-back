<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table = 'lms_respuesta';
    protected $primaryKey = 'id_respuesta';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function usuarioComentarioRespuesta()
    {
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }
    public function respuestaComentario()
    {
        return $this->belongsTo('App\Comentario', 'id_comentario');
    }

}
