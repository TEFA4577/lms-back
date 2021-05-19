<?php

namespace App\Http\Controllers;

use App\Docente;
use App\Usuario;
use Illuminate\Http\Request;


class DocenteController extends Controller
{
    public $hostBackend;
    public $rutaVideo = '/almacenamiento/videos/presentacion/';
    public $rutaCv = '/almacenamiento/archivos/cv/';

    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
    }

    /**
     * Descripcion: La funcion devuelve todos los docentes ordenados por id descendentemente.
     * Tipo: GET
     * URL: api/docentes
     * @Autor: @AlexAguilarP
     */
    public function index()
    {
        $docentes = Usuario::where('id_rol', 2)->with('datosDocente','redesDocente')->get();
        return response()->json($docentes);
    }
	public function listaDocente(){
		$docentes = Docente::orderBy('id_docente', 'asc')
							->get();
		foreach( $docentes as $docente ) {
    		$usuario = Usuario::where('id_usuario', $docente->id_usuario)
							->with('datosDocente', 'redesDocente')
							->get();
			return response()->json($usuario);
        }
	}
	public function habiliarDocente($id){
		$docente = Docente::where('id_usuario', $id)->first();
		$usuario = Usuario::where('id_usuario',$id)->first();
		if ($docente->estado_docente == 0) {
			$usuario->id_rol = 2;
			$usuario->save();
			$docente->estado_docente = 1;
			$docente->save();
            return response()->json(['mensaje' => 'el usuario cambio a docente', 'estado' => 'danger']);
        }
		$usuario->id_rol = 3;
		$usuario->save();
		$docente->estado_docente = 0;
		$docente->save();
        return response()->json(['mensaje' => 'el usuario cambio a estudiante', 'estado' => 'danger']);
	}
    /**
     * Descripcion: La funcion registra a un docente
     * Tipo: POST
     * URL: api/docentes/registrar
     * @Autor: @AlexAguilarP
     */
    public function registrarDocente(Request $request)
    {
        //$usuario = Usuario::where('id_usuario',$request->id_usuario)->first();
        //if ($usuario->id_rol == 2) {
        //    return response()->json(['mensaje' => 'el usuario ya esta registro como docente', 'estado' => 'danger']);
        //}
        //$usuario->id_rol = 2;
        //$usuario->save();
        $docente = new Docente;
        $docente->id_usuario = $request->id_usuario;
        $docente->telefono_docente = $request->telefono_docente;
        $docente->descripcion_docente = $request->descripcion_docente;
        $docente->experiencia_docente = $request->experiencia_docente;
		$docente->estado_docente = 0;
		
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
        } else {
            $docente->video_presentacion = $this->hostBackend . $this->rutaVideo . "video_presentacion.mp4";
        }
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
        } else {
            $docente->cv_docente = $this->hostBackend . $this->rutaCv . "sin_cv.pdf";
        }
        $docente->save();
        return response()->json(['mensaje' => 'Registro creado exitosamente', 'estado' => 'success']);
    }

    /**
     * Descripcion: La funcion muestra los datos de un docente
     * Tipo: GET
     * URL: api/docentes/mostrar/{id}
     * @Autor: @AlexAguilarP
     */
    public function mostrarDocente($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->datosDocente;
        $usuario->redesDocente;
        return response()->json($usuario);
    }

    /**
     * Descripcion: La funcion actualiza la informacion de un docente
     * Tipo: PUT
     * URL: api/docentes/actualizar/{id}
     * @Autor: @AlexAguilarP
     */
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
    /**
     * Descripcion: esta funcion actualiza el cv del docente
     * Tipo: POST
     * URL: api/docentes/cambiar-cv
     * @Autor: @AlexAguilarP
     */
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
            return response()->json(['mensaje' => 'Error Archivo no encontrado', 'estado' => 'daner']);
        }
    }
    /**
     * Descripcion: esta funcion actualiza el video de presentacion del docente
     * Tipo: POST
     * URL: api/docentes/cambiar-video
     * @Autor: @AlexAguilarP
     */
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
            return response()->json(['mensaje' => 'Error Archivo no encontrado', 'estado' => 'daner']);
        }
    }
}
