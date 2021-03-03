<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Comentario;
use App\Respuesta;
use App\Clase;

class ForoController extends Controller
{
    public function index()
    {
        $comentario = Comentario::orderBy('id_comentario', 'asc')
            ->with('comentarioRespuesta')
            ->with('usuarioComentario')
            ->get();
        return response()->json($comentario);
    }

    public function comentariosRespuestasClase($id){
        $comentarios=Comentario::where('id_clase',$id)
            ->with('comentarioRespuesta')
            ->with('usuarioComentario')
            ->get();
        return response()->json($comentarios);
    }
    public function registrarComentario(Request $request){
        $comentario=new Comentario();
        $comentario->id_clase=$request->id_clase;
        $comentario->id_usuario=$request->id_usuario;
        $comentario->texto_comentario = $request->texto_comentario;
        $comentario->save();
        return response()->json(['mensaje' => 'Registro Realizado con Exito', 'estado' => 'success', 'data'=>$comentario]);
    }

    public function registrarRespuestaComentario(Request $request){
        $respuesta=new Respuesta();
        $respuesta->id_comentario = $request->id_comentario;
        $respuesta->id_usuario = $request->id_usuario;
        $respuesta->texto_respuesta = $request->texto_respuesta;
        $respuesta->save();
        return response()->json(['mensaje' => 'Registro Realizado con Exito', 'estado' => 'success', 'data'=>$respuesta]);
    }
}
