<?php

namespace App\Http\Controllers;

use App\PreguntaFrecuente;
use App\RespuestaPreguntaFrecuente;
use Illuminate\Http\Request;

class PreguntaFrecuenteController extends Controller
{
    public $hostBackend;
    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
    }
    public function registrarPreguntaFrecuente(Request $request){
        $preguntafrecuente = new PreguntaFrecuente;
        $preguntafrecuente->texto_pregunta_frecuente = $request->texto_pregunta_frecuente;
        $preguntafrecuente->save();
        return response()->json(['mensaje' => 'Pregunta Realizada con Exito', 'estado' => 'success']);
    }
    public function mostrarPreguntaFrecuente($id){
        $pregunta = PreguntaFrecuente::findOrFail($id);
        return response()->json($pregunta);
    }
    public function listarPreguntasFrecuentes(){
        $preguntasfrecuentes = PreguntaFrecuente::orderBy('id_pregunta_frecuente', 'asc')->get();
        return response()->json($preguntasfrecuentes);
    }
    public function actualizarPreguntaFrecuente(Request $request, $id){
        $pregunta = PreguntaFrecuente::where('id_pregunta_frecuente', $id)->first();
        $pregunta->texto_pregunta_frecuente = $request->texto_pregunta_frecuente;
        $pregunta->estado_pregunta_frecuente = $request->estado_pregunta_frecuente;
        $pregunta->save();
        return response()->json(['mensaje' => 'Pregunta modificada exitosamente', 'estado'=> 'success']);
    }
    public function elimarPreguntaFrecuente($id){
        $preguntafrecuente = PreguntaFrecuente::where('id_pregunta_frecuente', $id)->first();
        $preguntafrecuente->estado_pregunta_frecuente = 0;
        $preguntafrecuente->save();
        return response()->json(['mensaje' => 'Elimando', 'estado' => 'daner']);
    }
    /**
     * Descripción: Registra las respuestas de preguntas frecuentes
     * Tipo: POST
     * URL: api/respuesta-pregunta-frecuente/registrar
     * @Autor: @Nor-MN
     */
    public function registrarRespuestaPreguntaFrecuente(Request $request){
        // $preguntafrecuente = PreguntaFrecuente::where('id_pregunta_frecuente')->first();
        $respuestapreguntafrecuente =  new RespuestaPreguntaFrecuente;
        $respuestapreguntafrecuente->id_pregunta = $request->id_pregunta;
        $respuestapreguntafrecuente->texto_respuesta_pregunta = $request->texto_respuesta_pregunta;
        // if ($request->id_pregunta == $preguntafrecuente) {
            $respuestapreguntafrecuente->save();
            return response()->json(['mensaje' => 'Respuesta Registrada con Éxito', 'estado' => 'success']);
        // }else {
            // return response()->json(['mensaje' => 'Respuesta No Registrada']);
        // }
    }
    public function listarRespuestaPreguntaFrecuente(){
        $respuestapreguntafrecuente = RespuestaPreguntaFrecuente::orderBy('id_respuesta_pregunta', 'asc')->get();
        return response()->json($preguntafrecuente);
    }
    public function actualizarRespuestaPreguntaFrecuente(Request $request, $id){
        $respuestapregunta = RespuestaPreguntaFrecuente::where('id_respuesta_pregunta', $id)->first();
        $respuestapregunta->texto_respuesta_pregunta = $request->texto_respuesta_pregunta;
        $respuestapregunta->estado_respuesta_pregunta = $request->estado_respuesta_pregunta;
        // $respuestapregunta->id_pregunta = $request->id_pregunta;
        $respuestapregunta->save();
        return response()->json(['mensaje' => 'Respuesta modificada exitosamente', 'estado' => 'success']);
    }
    public function eliminarRespuestaPreguntaFrecuente($id){
        $respuestapregunta = RespuestaPreguntafrecuente::where('id_respuesta_pregunta', $id)->first();
        $respuestapregunta->estado_respuesta_pregunta = 0;
        $respuestapregunta->save();
        return response()->json(['mensaje' => 'Elimando', 'estado' => 'daner']);
    }
}
