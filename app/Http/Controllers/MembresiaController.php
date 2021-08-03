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
use App\Mail\AprobacionCompraMembresia;
use Illuminate\Support\Facades\Mail;

class MembresiaController extends Controller
{
    public $hostBackend;
    public $ruta = '/almacenamiento/imagenes/membresias/';
    public $rutaComprobate = '/almacenamiento/imagenes/comprobantes/';

    public function __construct()
    {
        //$this->middleware('auth');
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
    }

    public function index()
    {
        $solicitud = MembresiaDocente::orderBy('id_membresia_usuario', 'desc')
            ->with('membresiaSolicitada')
            ->with('usuario')
            ->get();
    }
    public function mostrarMembresia($id)
    {
        $membresia = Membresia::findOrFail($id);
        return response()->json($membresia);
    }
    public function listarMembresia($id)
    {
        $membresia = Membresia::orderBy('id_membresia', 'asc')
            ->where('estado_membresia', 1)
            ->get();
        $docente = MembresiaDocente::where('id_usuario', $id)
            ->where('estado_membresia_usuario', 'adquirido')
            ->get();
        return response()->json(['membresias' => $membresia, 'docenteMemb' => $docente]);
    }

    public function admMembresia()
    {
        $membresia = Membresia::orderBy('id_membresia', 'desc')
            ->get();
        return response()->json($membresia);
    }

    public function registrarMembresia(Request $request)
    {
        $membresia = new Membresia;
        $membresia->nombre_membresia = $request->nombre_membresia;
        $membresia->texto_membresia = $request->texto_membresia;
        $membresia->precio_membresia = $request->precio_membresia;
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        //$duracion = $this->calcularTiempo($date, $request->duracion_membresia);
        $membresia->duracion_membresia = $request->duracion_membresia;
        if ($request->hasFile('imagen_membresia')) {
            $archivo = $request->file('imagen_membresia');
            $nombre_foto = time() . "_" . $archivo->getClientOriginalName();
            $archivo->move(public_path($this->ruta), $nombre_foto);
            $membresia->imagen_membresia = $this->hostBackend . $this->ruta . $nombre_foto;
        } else {
            $membresia->imagen_membresia = $this->hostBackend . $this->ruta . "sin_imagen.jpg";
        }
        $membresia->save();
        return response()->json(['mensaje' => 'membresia registrada', 'estado' => 'success']);
    }
    function calcularTiempo($inicio, $fechaFin)
    {
        $time1 = date_create($inicio);
        $time2 = date_create($fechaFin);
        $interval = date_diff($time1, $time2);
        $tiempo = array();
        foreach ($interval as $valor) {
            $tiempo[] = $valor;
        }
        return $tiempo;
    }
    public function actualizarMembresia(Request $request, $id)
    {
        $membresia = Membresia::where('id_membresia', $id)->first();
        $membresia->nombre_membresia = $request->nombre_membresia;
        $membresia->texto_membresia = $request->texto_membresia;
        $membresia->precio_membresia = $request->precio_membresia;
        $membresia->save();
        return response()->json(['mensaje' => 'Membresia Modificada', 'estado' => 'success']);
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
    public function eliminarMembresia($id)
    {
        $membresia = Membresia::find($id);
        if ($membresia->estado_membresia == 1) {
            $membresia->estado_membresia = 0;
        } else {
            $membresia->estado_membresia = 1;
            $membresia->save();
            return response()->json(['mensaje' => 'Membresia habilitada', 'estado' => 'daner']);
        }
        $membresia->save();
        return response()->json(['mensaje' => 'Membresia deshabilitada', 'estado' => 'daner']);
    }
    public function membresiaDocente($id)
    {
        $docente = MembresiaDocente::where('id_usuario', $id)
            ->where('estado_membresia_usuario', 'adilquirido')
            ->get();
        return $docente;
    }
    public function misSolicitudes()
    {
        $solicitudes = MembresiaDocente::with('membresiaSolicitada', 'usuario')
            ->orderBy('estado_membresia_usuario', 'desc')
            ->get();
        return response()->json($solicitudes);
    }

    public function adquirirMembresia(Request $request)
    {
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
                $solAnterior = MembresiaDocente::where('id_usuario', $request->id_usuario)
                                    ->where('id_membresia', $request->id_membresia)
                                    ->first();
                if($solAnterior){
                    return response()->json(['mensaje' => 'Esta membresía solo se puede adquirir una vez', 'estado'=>'warning']);
                }else{
                    $time = $membresia->duracion_membresia;
                    $docenteMembresia->estado_membresia_usuario = 'adquirido';
                    $time1 = Carbon::now();
                    $date = $time1;
                    $time1 = $time1->format('Y-m-d');
                    $time2 = $date->addDay($time);
                    $time2 = $time2->format('Y-m-d');
                    $docenteMembresia->inicio_membresia_usuario = $time1;
                    $docenteMembresia->fin_membresia_usuario = $time2;
                    $curso = Curso::where('id_usuario', $docenteMembresia->id_usuario)
                        ->where('membresia_curso', 'FIN')
                        ->update(['membresia_curso' => 'INICIO']);
                    }
            } else {
                if ($request->hasFile('comprobante')) {
                    $archivo = $request->file('comprobante');
                    $nombre_foto = time() . "_" . $archivo->getClientOriginalName();
                    $archivo->move(public_path($this->rutaComprobate), $nombre_foto);
                    $docenteMembresia->comprobante = $this->hostBackend . $this->rutaComprobate . $nombre_foto;
                } else {
                    return response()->json(['mensaje' => 'Debe incluir comprobante', 'estado' => 'error']);
                }
            }
            $docenteMembresia->save();
            return response()->json(['mensaje' => 'membresia añadida a su cuenta', 'estado' => 'success']);
        } else {
            return response()->json(['mensaje' => 'la membresia está en proceso de confirmacion o ya se encuentra adquirido', 'estado'=>'warning']);
        }
    }
    public function habilitarMembresia($id, $estado)
    {
        $docenteMembresia = MembresiaDocente::findOrFail($id);
        if ($estado == 'aprobado') {
            $membresia = Membresia::find($docenteMembresia->id_membresia);
            $time = $membresia->duracion_membresia;
            $docenteMembresia->estado_membresia_usuario = 'adquirido';
            $time1 = Carbon::now();
            $date = $time1;
            $time1 = $time1->format('Y-m-d');
            $time2 = $date->addDay($time);
            $time2 = $time2->format('Y-m-d');
            $docenteMembresia->inicio_membresia_usuario = $time1;
            $docenteMembresia->fin_membresia_usuario = $time2;
            $curso = Curso::where('id_usuario', $docenteMembresia->id_usuario)
                ->where('membresia_curso', 'FIN')
                ->update(['membresia_curso' => 'INICIO']);
            //envio del correo electronico
            $usuario = Usuario::find($curso->id_usuario);
            $correo = $usuario->correo_usuario;
            $data = [
                'nombre_usuario' => $usuario->nombre_usuario
            ];
           Mail::to($correo)->send(new AprobacionCompraMembresia($data));
            $docenteMembresia->save();
            $solicitudesAnteriores =  MembresiaDocente::where('id_usuario', $docenteMembresia->id_usuario)
                ->where('id_membresia', $docenteMembresia->id_membresia)
                ->where('estado_membresia_usuario', 'no confirmado')
                ->orWhere('estado_membresia_usuario', 'rechazado')
                ->delete();
            return response()->json(['mensaje' => 'solicitud habilitado', 'curso' => $docenteMembresia, 'state' => 'success']);
        } else if ($estado == 'rechazado') {
            $docenteMembresia->estado_membresia_usuario = 'rechazado';
            $curso = Curso::where('id_usuario', $docenteMembresia->id_usuario)
                ->where('membresia_curso', 'INICIO')
                ->update(['membresia_curso' => 'FIN']);
            $docenteMembresia->save();
            return response()->json(['mensaje' => 'solicitud rechazada', 'state' => 'error']);
        } else if ($estado == 'finalizar') {
            $time1 = Carbon::now();
            $time1 = $time1->format('Y-m-d');
            if ($time1 >= $docenteMembresia->fin_membresia_usuario) {
                $docenteMembresia->estado_membresia_usuario = 'finalizado';
                $curso = Curso::where('id_usuario', $docenteMembresia->id_usuario)
                    ->where('membresia_curso', 'INICIO')
                    ->update(['membresia_curso' => 'FIN']);
                $docenteMembresia->save();
                return response()->json(['mensaje' => ' membresia finalizada', 'state' => 'success']);
            } else {
                return response()->json(['mensaje' => 'la membresia no puede finalizar debido a que no es la fecha indicada de finalización', 'state' => 'warning']);
            }
        }
    }
}
