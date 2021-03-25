<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Membresia;
use App\Docente;
use App\Usuario;


class MembresiaController extends Controller
{
    public function index(){
        $solicitud = MembresiaDocente::orderBy('id_membresia_docente', 'asc')
                                    ->with('membresiaSolicitada')
                                    ->with('docente')
                                    ->get();
    }
    public function mostrarMembresia($id){
        $membresia = Membresia::findOrFail($id);
        return response()->json($membresia);
    }
    public function listarMembresia(){
        $membresia = Membresia::orderBy('id_membresia', 'asc')
                    ->where('estado_membresia', 1)
                    ->get();
        return response()->json($membresia);
    }
	
    public function registrarMembresia(Request $request){
        $membresia = new Membresia;
        $membresia->nombre_membresia = $request->nombre_membresia;
        $membresia->texto_membresia = $request->texto_membresia;
        $membresia->precio_membresia = $request->precio_membresia;
        $membresia->save();
        return response()->json(['mensaje'=>'membresia registrada', 'estado'=>'success']);
    }
    public function actualizarMembresia(Request $request, $id){
        $membresia = Membresia::where('id_membresia', $id)->first();
        $membresia->nombre_membresia = $request->nombre_membresia;
        $membresia->texto_membresia = $request->texto_membresia;
        $membresia->precio_membresia = $request->precio_membresia;
        $membresia->save();
        return response()->json(['mensaje'=>'Membresia Modificada', 'estado' => 'success']);
    }
    /*public function deshabilitarMembresia($id){
        $membresia = Membresia::find($id);
        $membresia->membresia = 0;
        $membresia->save();
        return response()->json(['mensaje' => 'Membresia Deshabilitada', 'estado'=>'daner']);
    }*/
    public function habilitarMembresia($id){
		$membresia = Membresia::find($id);
		if($membresia->membresia == 1){
			$membresia->membresia = 0;
		}else {
			$membresia->membresia = 1;
		}
        $membresia->save();
        return response()->json(['mensaje'=>'Estado actualizada', 'estado'=>'success']);		
    }
    public function eliminarMembresia($id){
        $membresia = Membresia::find($id);
        $membresia->estado_membresia = 0;
        $membresia->save();
        return response()->json(['mensaje'=> 'Membresia eliminada', 'estado'=> 'daner']);
    }
}
