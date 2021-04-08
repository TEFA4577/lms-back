<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Membresia;
use App\Usuario;
use App\MembresiaDocente;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class MembresiaController extends Controller
{
    public $hostBackend;
    public $ruta = '/almacenamiento/imagenes/membresias';

    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
    }

    public function index(){
        $solicitud = MembresiaDocente::orderBy('id_membresia_usuario', 'asc')
                                    ->with('membresiaSolicitada')
                                    ->with('usuario')
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

    public function admMembresia(){
        $membresia = Membresia::orderBy('id_membresia', 'asc')
                    ->get();
        return response()->json($membresia);
    }

    public function registrarMembresia(Request $request){
        $membresia = new Membresia;
        $membresia->nombre_membresia = $request->nombre_membresia;
        $membresia->texto_membresia = $request->texto_membresia;
        $membresia->precio_membresia = $request->precio_membresia;
        if ($request->hasFile('imagen_membresia')) {
            $archivo = $request->hasFile('imagen_membresia');
            $extension = $archivo->getClientOriginalExtension();
            $nombre_imagen = $request->nombre_membresia . '-' . $request->id_usuario  . '.' . $extension;
            $archivo = $request->file('imagen_membresia')->store('public' . $this->ruta);
            $membresia->imagen_membresia = $this->hostBackend . Storage::url($archivo);
        } else {
            $membresia->imagen_membresia = $this->hostBackend . $this->ruta . "/sin_imagen.jpg";
        }
        $membresia->save();
        return response()->json(['mensaje'=>'membresia registrada', 'estado'=>'success']);
    }
    public function actualizarMembresia(Request $request, $id){
        $membresia = Membresia::where('id_membresia', $id)->first();
        if ($request->hasFile('imagen_membresia')) {
            // subir la imagen al servidor
            $archivo = $request->file('imagen_membresia')->store('public' . $this->ruta);
            $membresia->imagen_membresia = $this->hostBackend . Storage::url($archivo);
        }
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
    public function eliminarMembresia($id){
        $membresia = Membresia::find($id);
        if ($membresia->estado_membresia == 1) {
            $membresia->estado_membresia = 0;
        }else {
            $membresia->estado_membresia = 1;
        }
        $membresia->save();
        return response()->json(['mensaje'=> 'Membresia eliminada', 'estado'=> 'daner']);
    }
    public function misSolicitudes()
    {
        $solicitudes = MembresiaDocente::where('estado_membresia_usuario', 'no confirmado')->with('membresiaSolicitada', 'usuario')->get();
        return response()->json($solicitudes);
    }
    public function adquirirMembresia(Request $request){
        $verificar = MembresiaDocente::where('id_usuario', $request->id_usuario)
                        ->where('id_membresia', $request->id_membresia)
                        ->where('estado_membresia_usuario', 'no confirmado')
                        ->orWhere('estado_membresia_usuario', 'aprobado')
                        ->first();
        if (!$verificar) {
            $docenteMembresia = new MembresiaDocente;
            $docenteMembresia->id_usuario = $request->id_usuario;
            $docenteMembresia->id_membresia = $request->id_membresia;
            $membresia = Membresia::find($request->id_membresia);
            if ($membresia->precio == 0) {
                $docenteMembresia->estado_membresia_usuario = 'adquirido';
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
            $docenteMembresia->estado_membresia_usuario = 'adquirido';
            $docenteMembresia->save();
            $solicitudesAnteriores =  MembresiaDocente::where('id_docente', $docenteMembresia->id_docente)
                ->where('id_membresia', $docenteMembresia->id_membresia)
                ->where('estado_membresia_usuario', 'no confirmado')
                ->orWhere('estado_membresia_usuario', 'rechazado')
                ->delete();
            return response()->json(['mensaje' => 'curso se a habilitado', 'curso' => $docenteMembresia]);
        } else if ($estado == 'rechazado') {
            $docenteMembresia->estado_membresia_usuario = 'rechazado';
            $docenteMembresia->save();
            return response()->json(['mensaje' => 'la solicitud fue rechazada']);
        }
    }
}
