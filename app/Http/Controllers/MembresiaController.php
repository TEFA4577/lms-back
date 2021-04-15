<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Membresia;
use App\Usuario;
use App\MembresiaDocente;
use App\Curso;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MembresiaController extends Controller
{
    public $hostBackend;
    public $ruta = '/almacenamiento/imagenes/membresias/';
    public $rutaComprobate = '/almacenamiento/imagenes/comprobantes/';
    public $tiempoBloqueado = 1;

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
            $archivo = $request->file('imagen_membresia');
            $nombre_foto = time() . "_" . $archivo->getClientOriginalName();
            $archivo->move(public_path($this->ruta), $nombre_foto);
            $membresia->imagen_membresia = $this->hostBackend . $this->ruta . $nombre_foto;
        } else {
            $membresia->imagen_membresia = $this->hostBackend . $this->ruta . "sin_imagen.jpg";
        }
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
    public function cambiarImagenMem(Request $request)
    {
        $membresia = Membresia::findOrFail($request->id_membresia);
        if ($request->hasFile('imagen_membresia')) {
            $archivo = $request->file('imagen_membresia');
            $extension = $archivo->getClientOriginalExtension();
            $nombre_imagen = $membresia->nombre_membresia . '.' . $extension;
            $archivo->move(public_path($this->ruta), $nombre_imagen);
            $membresia->imagen_membresia = $this->hostBackend . $this->ruta . $nombre_imagen;
            $membresia->save();
            return response()->json(['mensaje' => 'Registro Actualizado exitosamente', 'estado' => 'success']);
        } else {
            return response()->json(['mensaje' => 'Error Archivo no encontrado', 'estado' => 'daner']);
        }
    }
    public function eliminarMembresia($id){
        $membresia = Membresia::find($id);
        if ($membresia->estado_membresia == 1) {
            $membresia->estado_membresia = 0;
        }else {
            $membresia->estado_membresia = 1;
            $membresia->save();
            return response()->json(['mensaje'=> 'Membresia habilitada', 'estado'=> 'daner']);
        }
        $membresia->save();
        return response()->json(['mensaje'=> 'Membresia deshabilitada', 'estado'=> 'daner']);
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
            if ($membresia->precio_membresia == 0) {
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
            $curso=Curso::where('id_usuario', $docenteMembresia->id_usuario)
                        ->where('membresia_curso', 'FIN')
                        ->update(['membresia_curso' => 'INICIO']);
            $docenteMembresia->save();
            $solicitudesAnteriores =  MembresiaDocente::where('id_usuario', $docenteMembresia->id_usuario)
                ->where('id_membresia', $docenteMembresia->id_membresia)
                ->where('estado_membresia_usuario', 'no confirmado')
                ->orWhere('estado_membresia_usuario', 'rechazado')
                ->delete();
            return response()->json(['mensaje' => 'curso se a habilitado', 'curso' => $docenteMembresia]);
        } else if ($estado == 'rechazado') {
            $docenteMembresia->estado_membresia_usuario = 'rechazado';
            $curso=Curso::where('id_usuario', $docenteMembresia->id_usuario)
                        ->where('membresia_curso', 'INICIO')
                        ->update(['membresia_curso' => 'FIN']);
            $docenteMembresia->save();
            return response()->json(['mensaje' => 'la solicitud fue rechazada']);
        }
    }
}
