<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'lms_comentario';
    protected $primaryKey = 'id_comentario';
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public function comentarioRespuesta()
    {
        return $this->hasMany('App\Respuesta', 'id_comentario', 'App\Usuario', 'id_usuario');
    }
    public function usuarioComentario()
    {
        return $this->belongsTo('App\Usuario', 'id_usuario');
    }

}
