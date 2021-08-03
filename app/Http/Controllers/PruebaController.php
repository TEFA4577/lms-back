<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prueba;
use App\PruebaOpcion;
use App\Curso;
use App\Usuario;
use App\UsuarioEvaluacion;
use Carbon\Carbon;

class PruebaController extends Controller
{
    public $hostBackend;

    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
    }

    public function index()
    {
        $prueba = Prueba::where('estado_prueba', 1)
            ->with('pruebaOpcion')
            ->get();
        return $prueba;
    }

    public function mostrarPrueba($id)
    {
        $prueba = Prueba::findOrFail($id);
        $opcion = Prueba::findOrFail($id)->pruebaOpcion;
        return response()->json(['prueba' => $prueba, 'opciones' => $opcion]);
    }

    public function mostrarOpcion($id)
    {
        $opcion = PruebaOpcion::findOrFail($id);
        return $opcion;
    }

    public function registrarPrueba(Request $request)
    {
        $prueba = new Prueba;
        $prueba->id_curso = $request->id_curso;
        $prueba->texto_prueba = $request->texto_prueba;
        $prueba->save();
    }
    public function actualizarPrueba(Request $request, $id)
    {
        $prueba = Prueba::findOrFail($id);
        $prueba->texto_prueba = $request->texto_prueba;
        $prueba->save();
        return response()->json(['mensaje' => 'Actualización Realizada con Exito', 'estado' => 'success']);
    }

    public function eliminarPrueba($id)
    {
        $prueba = Prueba::find($id);
        $prueba->delete();
        return response()->json(['mensaje' => 'Eliminado con éxito', 'estado' => 'success']);
        /*if ($prueba->estado_prueba == 1) {
            $prueba->estado_prueba = 0;
            $opcion = PruebaOpcion::where('id_prueba', $id)
                ->where('estado_prueba_opcion', 1)
                ->update(['estado_prueba_opcion' => 0]);
        } else {
            $prueba->estado_prueba = 1;
            $opcion = PruebaOpcion::where('id_prueba', $id)
                ->where('estado_prueba_opcion', 0)
                ->update(['estado_prueba_opcion' => 1]);
        }
        $prueba->save();*/

    }
    public function registrarOpcion(Request $request)
    {
        $opcion = new PruebaOpcion;
        $opcion->id_prueba = $request->id_prueba;
        $opcion->texto_prueba_opcion = $request->texto_prueba_opcion;
        if ($request->respuesta_opcion == true) {
            $opcion->respuesta_opcion = 1;
        } else {
            $opcion->respuesta_opcion = 0;
        }
        $opcion->estado_prueba_opcion = 1;
        $opcion->save();
        return response()->json(['mensaje' => 'Actualización Realizada con Exito', 'estado' => 'success']);
    }
    public function actualizarOpcion(Request $request, $id)
    {
        $opcion = PruebaOpcion::findOrFail($id);
        $opcion->texto_prueba_opcion = $request->texto_prueba_opcion;
        if ($request->respuesta_opcion == true) {
            $opcion->respuesta_opcion = 1;
        } else {
            $opcion->respuesta_opcion = 0;
        }
        $opcion->save();
        return response()->json(['mensaje' => 'Actualización Realizada con Exito', 'estado' => 'success']);
    }
    public function eliminarOpcion($id)
    {
        $opcion = PruebaOpcion::find($id);
        $opcion->delete();
        return response()->json(['mensaje' => 'Eliminado con éxito', 'estado' => 'success']);
        /*if ($opcion->estado_prueba_opcion == 1) {
            $opcion->estado_prueba_opcion = 0;
        } else {
            $opcion->estado_prueba_opcion = 1;
        }
        $opcion->save();*/
    }
    public function darExamen($id, $datos)
    {
        $examen = UsuarioEvaluacion::where('id_curso', $id)
            ->where('id_usuario', $datos)
            ->first();

        $prueba = Prueba::where('id_curso', $id)
            ->where('estado_prueba', 1)
            ->with('pruebaOpcion')
            ->get();

        $date = date('Y-m-d');
        $date2 = $examen->updated_at;
        $date2 = $date2->format('Y-m-d');

        if ($examen->progreso_evaluacion > 0) {
            if ($date2 < $date) {
                $examen->progreso_evaluacion = json_encode(0);
                $examen->save();
                return response()->json(['prueba' => $prueba, 'mensaje' => 'Iniciando exámen', 'estado' => 'success']);
            } else {
                return response()->json(['prueba' => 'ok', 'mensaje' => 'Vuelva a intentar mañana', 'estado' => 'warning']);
            }
        } else {
            return response()->json(['prueba' => $prueba, 'mensaje' => 'Iniciando exámen. Recibe tu certificado tras completar la ronda con éxito ', 'estado' => 'success']);
        }
    }

    public function evaluarExamen($id, $idC, $idU)
    {
        $prueba = Prueba::where('id_curso', $idC)->get();
        $valor = count($prueba);
        $result = 100 / $valor;
        //$result = ($valor * 100) / $valor;
        $opcion = PruebaOpcion::find($id);
        if ($opcion->respuesta_opcion == 0) {
            return response()->json(['mensaje' => 'incorrecta']);
        } elseif ($opcion->respuesta_opcion == 1) {
            $examen = UsuarioEvaluacion::where('id_curso', $idC)
                ->where('id_usuario', $idU)
                ->first();
            $examen->progreso_evaluacion = json_encode($examen->progreso_evaluacion + $result);
            $examen->update();
            return response()->json(['mensaje' => 'correcta']);
        }
    }
    public function inicioExamen(Request $request)
    {
        $examen = UsuarioEvaluacion::where('id_usuario', $request->id_usuario)
            ->where('id_curso', $request->id_curso)
            ->get();
        return response()->json('Empezando Exámen');
    }
    public function resultExamen($id, $datos)
    {
        $result = UsuarioEvaluacion::where('id_curso', $id)
            ->where('id_usuario', $datos)
            ->first();
        if ($result->progreso_evaluacion > 50) {
            return response()->json(['mensaje' => 'Felicitaciones, terminaste el exámen. Puedes acceder a tu certificado', 'estado' => 'success']);
        } else {
            if ($result->progreso_evaluacion == 0) {
                $result->progreso_evaluacion = json_encode(1);
                $result->save();
            }
            return response()->json(['mensaje' => 'Vuelva a intentar mañana. Una vez terminado con éxito podrás acceder a tu certificado.', 'estado' => 'warning']);
        }
    }
}
