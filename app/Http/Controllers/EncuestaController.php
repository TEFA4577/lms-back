<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Encuesta;
use App\EncuestaPregunta;
use App\EncuestaRespuesta;
use Illuminate\Support\Facades\DB;

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
            ->where('estado_encuesta', 1)
            ->with('encuestaPregunta')
            //->where('estado_encuesta_pregunta', 1)
            ->get();
        return response()->json($encuesta);
    }

    public function mostrarEncuesta($id)
    {
        $encuesta = Encuesta::findOrFail($id);
        //->with('encuestaPregunta');
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

    public function mostrarPregunta($id)
    {
        $pregunta = EncuestaPregunta::findOrFail($id);
        return response()->json($pregunta);
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
            ->where('estado_encuesta_pregunta', 1)
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
        return response()->json(['mensaje' => 'Titulo modificado exitosamente', 'estado' => 'success']);
    }

    public function deshabilitarEncuesta($id)
    {
        $encuesta=Encuesta::find($id);
        if ($encuesta->estado_encuesta == 1) {
            $encuesta->estado_encuesta = 0;
            $pregunta=EncuestaPregunta::where('id_encuesta', $id)
                        ->where('estado_encuesta_pregunta', 1)
                        ->update(['estado_encuesta_pregunta' => 0]);
            //$respuesta->save();
        }else {
            $encuesta->estado_encuesta = 1;
            $pregunta=EncuestaPregunta::where('id_pregunta', $id)
                        ->where('estado_encuesta_pregunta', 0)
                        ->update(['estado_encuesta_pregunta' => 1]);
        }
        $encuesta->save();
        return response()->json(['mensaje' => 'Elimando', 'estado' => 'danger', 'Encuesta'=>$encuesta, 'Pregunta' => $pregunta]);
    }

    public function eliminarPreguntaEncuesta($id)
    {
        $encuesta = EncuestaPregunta::where('id_encuesta_pregunta', $id)->first();
        $encuesta->estado_encuesta_pregunta = 0;
        $encuesta->save();
        return response()->json(['mensaje' => 'Elimando', 'estado' => 'danger']);
    }
}
