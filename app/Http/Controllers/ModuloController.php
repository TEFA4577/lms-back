<?php

namespace App\Http\Controllers;

use App\Clase;
use App\Modulo;
use App\Recurso;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    /**
     * Descripcion: La funcion registra un modulo.
     * Tipo: POST
     * URL: api/modulos/registrar
     * @Autor: @AlexAguilarP
     */
    public function registrarModulo(Request $request)
    {
        $modulo =  new Modulo;
        $modulo->id_curso = $request->id_curso;
        $modulo->nombre_modulo = $request->nombre_modulo;
        $modulo->descripcion_modulo = $request->descripcion_modulo;
        $modulo->save();
        return response()->json(['mensaje' => 'Registro Realizado con Exito', 'estado' => 'success']);
    }

    /**
     * Descripcion: La funcion muestra el modulo de un curso.
     * Tipo: GET
     * URL: api/modulos/mostrar
     * @Autor: @AlexAguilarP
     */
    public function mostrarModulo($id)
    {
        $modulo = Modulo::findOrFail($id)->where('estado_modulo', 1);
        $clases = Modulo::findOrFail($id)->clasesModulo;
        return response()->json(['modulo' => $modulo, 'clases' => $clases]);
    }

    /**
     * Descripcion: La funcion actualiza los datos de un modulo.
     * Tipo: PUT
     * URL: api/modulos/actualizar/{id}
     * @Autor: @AlexAguilarP
     */
    public function actualizarModulo(Request $request, $id)
    {
        $modulo = Modulo::findOrFail($id);
        $modulo->nombre_modulo = $request->nombre_modulo;
        $modulo->descripcion_modulo = $request->descripcion_modulo;
        $modulo->save();
        return response()->json(['mensaje' => 'Actualización Realizada con Exito', 'estado' => 'success']);
    }
    /**
     * Descripcion: La funcion actualiza los datos de un modulo.
     * Tipo: GET
     * URL: api/modulos/eliminar/{id}
     * @Autor: @AlexAguilarP
     */
    public function eliminarModulo($id)
    {
        $modulo = Modulo::findOrFail($id);
		$clases = Clase::where('id_modulo', $id)
					->where('estado_clase', 1)
					->update(['estado_clase' => 0]);
        $modulo->save();
        return response()->json(['mensaje' => 'Borrado con Exito', 'estado' => 'success']);
    }
}
