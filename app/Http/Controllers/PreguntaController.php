<?php

namespace App\Http\Controllers;

use App\Pregunta;
use App\RespuestaPregunta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    public $hostBackend;
    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
    }
    public function registrarPregunta(Request $request){
        $pregunta = new Pregunta;
        $pregunta->texto_pregunta = $request->texto_pregunta;
        $pregunta->save();
        return response()->json(['mensaje' => 'Pregunta Realizada con Exito', 'estado' => 'success']);
    }
    public function mostrarPregunta($id){
        $pregunta = Pregunta::findOrFail($id);
        return response()->json($pregunta);
    }

    public function listarPregunta(){
        $pregunta = Pregunta::orderBy('id_pregunta', 'asc')
                    ->where('estado_pregunta', 1)
                    ->with('preguntaRespuesta')
                    ->get();
        return response()->json($pregunta);
    }
    public function actualizarPregunta(Request $request, $id){
        $pregunta = Pregunta::where('id_pregunta', $id)->first();
        $pregunta->texto_pregunta = $request->texto_pregunta;
        // $pregunta->estado_pregunta = $request->estado_pregunta;
        $pregunta->save();
        return response()->json(['mensaje' => 'Pregunta modificada exitosamente', 'estado'=> 'success']);
    }
    public function elimarPregunta($id){
        //$pregunta = Pregunta::where('id_pregunta', $id)->first();
        $pregunta=Pregunta::find($id);
        if ($pregunta->estado_pregunta == 1) {
            $pregunta->estado_pregunta = 0;
            $respuesta=RespuestaPregunta::where('id_pregunta', $id)
                        ->where('estado_respuesta_pregunta', 1)
                        ->update(['estado_respuesta_pregunta' => 0]);
            //$respuesta->save();
        }else {
            $pregunta->estado_pregunta = 1;
            $respuesta=RespuestaPregunta::where('id_pregunta', $id)
                        ->where('estado_respuesta_pregunta', 0)
                        ->update(['estado_respuesta_pregunta' => 1]);
        }
        $pregunta->save();
        return response()->json(['mensaje' => 'Elimando', 'estado' => 'daner', 'Pregunta'=>$pregunta, 'respuesta' => $respuesta]);
    }
    /**
     * Descripción: Registra las respuestas de preguntas s
     * Tipo: POST
     * URL: api/respuesta-pregunta-/registrar
     * @Autor: @Nor-MN
     */
    public function registrarRespuestaPregunta(Request $request){
        // $pregunta = Pregunta::where('id_pregunta')->first();
        $respuestapregunta =  new RespuestaPregunta;
        $respuestapregunta->id_pregunta = $request->id_pregunta;
        $respuestapregunta->texto_respuesta_pregunta = $request->texto_respuesta_pregunta;
        // if ($request->id_pregunta == $pregunta) {
            $respuestapregunta->save();
            return response()->json(['mensaje' => 'Respuesta Registrada con Éxito', 'estado' => 'success']);
        // }else {
            // return response()->json(['mensaje' => 'Respuesta No Registrada']);
        // }
    }
    public function listarRespuestaPregunta(){
        $respuesta =RespuestaPregunta::where('id_respuesta_pregunta', 'asc')
                    ->where('estado_respuesta_pregunta', 1)
                    ->with('respuestaPregunta')
                    ->get();
        return response()->json($respuesta);
    }
    public function mostrarRespuestaPregunta($id){
        $respuesta = RespuestaPregunta::findOrFail($id)
                    ->where('estado_respuesta_pregunta', 1);
        return response()->json($respuesta);
    }
    public function actualizarRespuestaPregunta(Request $request, $id){
        $respuestapregunta = RespuestaPregunta::where('id_respuesta_pregunta', $id)->first();
        $respuestapregunta->texto_respuesta_pregunta = $request->texto_respuesta_pregunta;
        $respuestapregunta->save();
        return response()->json(['mensaje' => 'Respuesta modificada exitosamente', 'estado' => 'success']);
    }
    public function eliminarRespuestaPregunta($id){
        $respuestapregunta = RespuestaPregunta::where('id_respuesta_pregunta', $id)->first();
        $respuestapregunta->estado_respuesta_pregunta = 0;
        $respuestapregunta->save();
        return response()->json(['mensaje' => 'Elimando', 'estado' => 'daner']);
    }
}
