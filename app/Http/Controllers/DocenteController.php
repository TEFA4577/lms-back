<?php

namespace App\Http\Controllers;

use App\Docente;
use App\Usuario;
use Illuminate\Http\Request;
use App\Mail\AprobacionSolicitudDocente;
use Illuminate\Support\Facades\Mail;


class DocenteController extends Controller
{
    public $hostBackend;
    public $rutaVideo = '/almacenamiento/videos/presentacion/';
    public $rutaCv = '/almacenamiento/archivos/cv/';

    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com'/*'http://127.0.0.1:8000/api'*/);
    }

    public function index()
    {
        $docentes = Usuario::where('id_rol', 2)->with('datosDocente', 'redesDocente')->get();
        return response()->json($docentes);
    }
    public function listaDocente()
    {
        $docentes = Docente::with('datosUsuario')->get();
        /*$docentes = Docente::orderBy('id_docente', 'asc')
							->get();
		foreach( $docentes as $docente ) {
    		$usuario = Usuario::where('id_usuario', $docente->id_usuario)
							->with('datosDocente', 'redesDocente')
							->get();*/
        return response()->json($docentes);
    }


    public function habiliarDocente($id)
    {
        $docente = Docente::where('id_usuario', $id)->first();
        $usuario = Usuario::where('id_usuario', $id)->first();
        if ($docente->estado_docente == 0) {
            $usuario->id_rol = 2;
            $usuario->save();
            $docente->estado_docente = 1;
            $docente->save();
            //envio del correo electronico
            $correo = $usuario->correo_usuario;
            $data = [
                'nombre_usuario' => $usuario->nombre_usuario
            ];
            Mail::to($correo)->send(new AprobacionSolicitudDocente($data));
            return response()->json(['mensaje' => 'el usuario cambio a docente', 'estado' => 'danger']);
        }
        $usuario->id_rol = 3;
        $usuario->save();
        $docente->estado_docente = 0;
        $docente->save();
        return response()->json(['mensaje' => 'el usuario cambio a estudiante', 'estado' => 'danger']);
    }

    public function registrarDocente(Request $request)
    {
        $usuario = Usuario::where('id_usuario', $request->id_usuario)->first();
        if ($usuario->id_rol == 2) {
            return response()->json(['mensaje' => 'el usuario ya esta registro como docente', 'estado' => 'warning']);
        }

        $verificar = Docente::where('id_usuario', $request->id_usuario)
            ->get();
        if (count($verificar) >= 1) {
            return response()->json(['mensaje' => 'Ya se envió su solicitud docente', 'estado' => 'warning']);
        } else {
            $docente = new Docente;
            $docente->id_usuario = $request->id_usuario;
            $docente->telefono_docente = $request->telefono_docente;
            $docente->descripcion_docente = $request->descripcion_docente;
            $docente->experiencia_docente = $request->experiencia_docente;
            $docente->video_presentacion = $request->video_presentacion;
            $docente->cv_docente = $request->cv_docente;
            $docente->estado_docente = 0;

            /*if ($request->hasFile('video')) {
				// subir la imagen al servidor
				$archivo = $request->file('video');
				// $archivoNombre = $archivo->getClientOriginalName();
				$extension = $archivo->getClientOriginalExtension();
				// Nombre del archivo con el que se guardara en el servidor
				$nombre_video = $usuario->correo_usuario . '.' . $extension;
				// ruta donde se guardara la imagen en el servidor
				$archivo->move(public_path($this->rutaVideo), $nombre_video);
				// registrar los datos del usuario
				$docente->video_presentacion = $this->hostBackend . $this->rutaVideo . $nombre_video;
			} else {
				$docente->video_presentacion = $this->hostBackend . $this->rutaVideo . "video_presentacion.mp4";
			}*/
            /*if ($request->hasFile('cv_docente')) {
                // subir la imagen al servidor
                $archivo = $request->file('cv_docente');
                // $archivoNombre = $archivo->getClientOriginalName();
                $extension = $archivo->getClientOriginalExtension();
                // Nombre del archivo con el que se guardara en el servidor
                $nombre_cv = $usuario->nombre_usuario . '.' . $extension;
                // ruta donde se guardara la imagen en el servidor
                $archivo->move(public_path($this->rutaCv), $nombre_cv);
                // registrar los datos del usuario
                $docente->cv_docente = $this->hostBackend . $this->rutaCv . $nombre_cv;
            } else {
                $docente->cv_docente = $this->hostBackend . $this->rutaCv . "sin_cv.pdf";
            }*/
            /*if ($request->hasFile('cv_docente')) {
                // subir el archivo al servidor
                $archivo = $request->file('cv_docente');
                // $archivoNombre = $archivo->getClientOriginalName();
                $extension = $archivo->getClientOriginalExtension();
                // Nombre del archivo con el que se guardara en el servidor
                $nombre_archivo = $request->correo_usuario . '-' . $extension;
                // ruta donde se guardara la imagen en el servidor
                $archivo->move(public_path($this->rutaCv), $nombre_archivo);

                $docente->cv_docente = $this->hostBackend . $this->rutaCv . $nombre_archivo;
            } else {
                $docente->cv_docente = $this->hostBackend . $this->rutaCv . "sin_cv.pdf";
                return response()->json(['mensaje' => 'No se encontró el archivo', 'estado' => 'danger']);
            }*/
            $docente->save();
            return response()->json(['mensaje' => 'Registro creado exitosamente', 'estado' => 'success']);
        }
    }

    public function registrarVideo(Request $request)
    {
        $archivo = $request->file('video_presentacion');
        // $archivoNombre = $archivo->getClientOriginalName();
        $extension = $archivo->getClientOriginalExtension();
        // Nombre del archivo con el que se guardara en el servidor
        $nombre_video = 3 . '-' . 'hola' . '.' . $extension;
        // ruta donde se guardara la imagen en el servidor
        $archivo->move(public_path($this->ruta), $nombre_video);
        $video_clase = $this->hostBackend . $this->ruta . $nombre_video;

        return response()->json(['mensaje' => 'Registro Realizado con Exito', 'estado' => 'success', 'data' => $video_clase]);
    }


    public function mostrarDocente($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->datosDocente;
        $usuario->redesDocente;
        return response()->json($usuario);
    }

    public function actualizarDocente(Request $request, $id)
    {
        $docente = Docente::where('id_usuario', $id)->first();
        $docente->telefono_docente = $request->telefono_docente;
        $docente->descripcion_docente = $request->descripcion_docente;
        $docente->experiencia_docente = $request->experiencia_docente;
        $docente->save();
        $usuario = Usuario::find($docente->id_usuario);
        $usuario->rolUsuario;
        $usuario->datosDocente;
        $usuario->redesDocente;
        return response()->json(['mensaje' => 'Registro actualizado exitosamente', 'estado' => 'success', 'datosUsuario' => $usuario]);
    }

    public function actualizarCv(Request $request)
    {
        $docente = Docente::findOrFail($request->id_docente);
        $usuario = Usuario::where('id_usuario', $docente->id_usuario)->first();
        if ($request->hasFile('cv')) {
            // subir la imagen al servidor
            $archivo = $request->file('cv');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_cv = $usuario->correo_usuario . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->rutaCv), $nombre_cv);
            // registrar los datos del usuario
            $docente->cv_docente = $this->hostBackend . $this->rutaCv . $nombre_cv;
            $docente->save();
            return response()->json(['mensaje' => 'Registro creado exitosamente', 'estado' => 'success']);
        } else {
            return response()->json(['mensaje' => 'Error Archivo no encontrado', 'estado' => 'error']);
        }
    }

    public function actualizarVideo(Request $request)
    {
        $docente = Docente::findOrFail($request->id_docente);
        $usuario = Usuario::where('id_usuario', $docente->id_usuario)->first();
        if ($request->hasFile('video')) {
            // subir la imagen al servidor
            $archivo = $request->file('video');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_video = $usuario->correo_usuario . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->rutaVideo), $nombre_video);
            // registrar los datos del usuario
            $docente->video_presentacion = $this->hostBackend . $this->rutaVideo . $nombre_video;
            $docente->save();
            return response()->json(['mensaje' => 'Registro creado exitosamente', 'estado' => 'success']);
        } else {
            return response()->json(['mensaje' => 'Error Archivo no encontrado', 'estado' => 'error']);
        }
    }
}
