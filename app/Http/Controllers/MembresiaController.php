<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Membresia;
use App\Docente;
use App\Usuario;
use App\MembresiaDocente;


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
    public function activarMembresia($id){
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
    public function misSolicitudes($id)
    {
        $solicitudes = MembresiaDocente::where('id_docente', $id)->with('membresiaSolicitada')->get();
        return response()->json($solicitudes);
    }
    public function adquirirMembresia(Request $request){
        $verificar = MembresiaDocente::where('id_docente', $request->id_docente)
                        ->where('id_membresia', $request->id_membresia)
                        ->where('estado_membresia_docente', 'no confirmado')
                        ->orWhere('estado_membresia_docente', 'aprobado')
                        ->first();
        if (!$verificar) {
            $docenteMembresia = new MembresiaDocente;
            $docenteMembresia->id_docente = $request->id_docente;
            $docenteMembresia->id_membresia = $request->id_membresia;
            $membresia = Membresia::find($request->id_membresia);
            if ($membresia->precio == 0) {
                $docenteMembresia->estado_membresia_docente = 'adquirido';
            } else {
                if ($request->hasFile('comprobante')) {
                    $archivo = $request->file('comprobante');
                    $nombre_foto = time() . "_" . $archivo->getClientOriginalName();
                    $archivo->move(public_path($this->rutaComprobate), $nombre_foto);
                    $docenteMembresia->comprobante = $this->hostBackend . $this->rutaComprobate . $nombre_foto;
                } else {
                    return response()->json(['mensaje' => 'error', 'estado' => 'danger']);
                }
            }
            $docenteMembresia->save();
            return response()->json(['mensaje' => 'membresia añadida a su cuenta']);
        }else{
            return response()->json(['mensaje' => 'la membresia está en proceso de confirmacion o ya se encuentra adquirido']);
        }
    }
    public function habilitarMembresia($id, $estado)
    {
        $docenteMembresia = MembresiaDocente::findOrFail($id);
        if ($estado == 'aprobado') {
            $membresia = Membresia::find($docenteMembresia->id_membresia);
            $docenteMembresia->estado_membresia_docente = 'adquirido';
            $docenteMembresia->save();
            $solicitudesAnteriores =  MembresiaDocente::where('id_docente', $docenteMembresia->id_docente)
                ->where('id_curso', $docenteMembresia->id_curso)
                ->where('estado_membresia_docente', 'no confirmado')
                ->orWhere('estado_membresia_docente', 'rechazado')
                ->delete();
            return response()->json(['mensaje' => 'curso se a habilitado', 'curso' => $docenteMembresia]);
        } else if ($estado == 'rechazado') {
            $docenteMembresia->estado_membresia_docente = 'rechazado';
            $docenteMembresia->save();
            return response()->json(['mensaje' => 'la solicitud fue rechazada']);
        }
    }
}
