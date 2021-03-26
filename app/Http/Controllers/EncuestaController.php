<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encuesta;
use App\EncuestaPregunta;
use App\EncuestaRespuesta;

class EncuestaController extends Controller
{
    public function registrarEncuesta(Request $request)
    {
        $encuesta = new Encuesta;
        $encuesta->id_rol = $request->id_rol;
        $encuesta->texto_encuesta = $request->texto_encuesta;
        $encuesta->save();
        return response()->json(['mensaje' => 'encuesta registrada', 'estado' => 'success']);
    }

    public function listarEncuestas()
    {
        $encuesta = Encuesta::orderBy('id_encuesta', 'desc')

            ->get();
        return response()->json($encuesta);

    }

    public function registrarPregunta(Request $request)
    {
        $pregunta = new EncuestaPregunta;
        $pregunta->id_encuesta = $request->id_encuesta;
        $pregunta->texto_encuesta_pregunta = $request->texto_encuesta_pregunta;
        $pregunta->save();
        return response()->json(['mensaje' => 'pregunta registrada', 'estado' => 'success']);
    }

    public function registrarRespuesta(Request $request)
    {
        $respuesta = new EncuestaRespuesta;
        $respuesta->id_encuesta_pregunta = $request->id_encuesta_pregunta;
        $respuesta->id_usuario = $request->id_usuario;
        $respuesta->texto_encuesta_respuesta = $request->texto_encuesta_respuesta;
        $respuesta->save();
        return response()->json(['mensaje' => 'respuesta registrada', 'estado' => 'success']);
    }

    public function listarPreguntasEncuesta()
    {
        $respuesta = EncuestaPregunta::orderBy('id_encuesta_pregunta', 'asc')
            ->with('preguntaEncuesta')
            ->with('respuestaEncuesta')
            ->get();
        return response()->json($respuesta);
    }

    public function actualizarEncuesta(Request $request, $id)
    {
        $encuesta = Encuesta::where('id_encuesta', $id)->first();
        $encuesta->texto_encuesta = $request->texto_encuesta;
        $encuesta->save();
        return response()->json(['mensaje' => 'Pregunta modificada exitosamente', 'estado' => 'success']);
    }

    public function actualizarPreguntaEncuesta(Request $request, $id)
    {
        $pregunta = EncuestaPregunta::where('id_encuesta_pregunta', $id)->first();
        $pregunta->texto_encuesta_pregunta = $request->texto_encuesta_pregunta;
        $pregunta->save();
        return response()->json(['mensaje' => 'Pregunta modificada exitosamente', 'estado' => 'success']);
    }

    public function eliminarEncuesta($id)
    {
        $encuesta = Encuesta::where('id_encuesta', $id)->first();
        $encuesta->estado_encuesta = 0;
        $encuesta->save();
        return response()->json(['mensaje' => 'Elimando', 'estado' => 'daner']);
    }

    public function eliminarPreguntaEncuesta($id)
    {
        $encuesta = EncuestaPregunta::where('id_encuesta_pregunta', $id)->first();
        $encuesta->estado_encuesta_pregunta = 0;
        $encuesta->save();
        return response()->json(['mensaje' => 'Elimando', 'estado' => 'daner']);
    }
}
