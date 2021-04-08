<?php

namespace App\Http\Controllers;

use App\Clase;
use App\Recurso;
use Illuminate\Http\Request;

class ClaseController extends Controller
{
    public $hostBackend;
    public $ruta = '/almacenamiento/videos/clases/';

    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
    }

    /**
     * Descripcion: La funcion registra un clase.
     * Tipo: POST
     * URL: api/clases/registrar
     * @Autor: @AlexAguilarP
     */
    public function registrarClase(Request $request)
    {
        $clase =  new Clase;
        $clase->id_modulo = $request->id_modulo;
        $clase->titulo_clase = $request->titulo_clase;
        $clase->descripcion_clase = $request->descripcion_clase;
        if ($request->hasFile('video_clase')) {
            // subir la imagen al servidor
            $archivo = $request->file('video_clase');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_video = $request->id_modulo . '-' . $request->titulo_clase . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->ruta), $nombre_video);
            $clase->video_clase = $this->hostBackend . $this->ruta . $nombre_video;
        }
        $clase->save();
        return response()->json(['mensaje' => 'Registro Realizado con Exito', 'estado' => 'success']);
    }

    /**
     * Descripcion: La funcion muestra el clase de un curso.
     * Tipo: GET
     * URL: api/clase/mostrar
     * @Autor: @AlexAguilarP
     */
    public function mostrarClase($id)
    {
        $clase = Clase::findOrFail($id);
        $recursos = Clase::findOrFail($id)->recursosClase;
        return response()->json(['clase' => $clase, 'recursos' => $recursos]);
    }

    /**
     * Descripcion: La funcion actualiza los datos de un clase.
     * Tipo: PUT
     * URL: api/clases/actualizar/{id}
     * @Autor: @AlexAguilarP
     */
    public function actualizarClase(Request $request, $id)
    {
        $clase = Clase::findOrFail($id);
        $clase->titulo_clase = $request->titulo_clase;
        $clase->descripcion_clase = $request->descripcion_clase;
        $clase->save();
        return response()->json(['mensaje' => 'Actualización Realizada con Exito', 'estado' => 'success']);
    }
    /**
     * Descripcion: La funcion actualiza el video de un clase.
     * Tipo: POST
     * URL: api/clases/cambiar-video
     * @Autor: @AlexAguilarP
     */
    public function cambiarVideo(Request $request)
    {
        $clase = Clase::findOrFail($request->id_clase);
        if ($request->hasFile('video_clase')) {
            // subir la imagen al servidor
            $archivo = $request->file('video_clase');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_video = $clase->id_modulo . '-' . $clase->titulo_clase  . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->ruta), $nombre_video);
            $clase->video_clase = $this->hostBackend . $this->ruta . $nombre_video;
        }
        $clase->save();
        return response()->json(['mensaje' => 'Video cambiado con Exito', 'estado' => 'success']);
    }
    /**
     * Descripcion: La funcion muestra el clase de un curso.
     * Tipo: GET
     * URL: api/clase/eliminar/{id}
     * @Autor: @AlexAguilarP
     */
    public function eliminarClase($id)
    {
        $recursos = Recurso::where('id_clase', $id)->get();
        foreach ($recursos as $recurso) {
            $rutaRecurso = explode("/", $recurso->link_recurso);
            $image_path = public_path() . "/" . $rutaRecurso[3] . "/" . $rutaRecurso[4];
            if (filesize($image_path)) {
                unlink($image_path);
            }
        }
        Recurso::where('id_clase', $id)->delete();
        $clase = Clase::find($id);
        $rutaVideo = explode("/", $clase->video_clase);
        $image_path = public_path() . "/" . $rutaVideo[3] . "/" . $rutaVideo[4] . "/" . $rutaVideo[5];
        if (filesize($image_path)) {
            unlink($image_path);
        }
        $clase->delete();
        return response()->json(['mensaje' => 'Borrado con Exito', 'estado' => 'success']);
    }
}
