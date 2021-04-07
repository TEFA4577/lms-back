<?php

namespace App\Http\Controllers;

use App\CursoEtiqueta;
use App\Etiqueta;
use Illuminate\Http\Request;

class EtiquetaController extends Controller
{
    public $hostBackend;
    public $ruta = '/almacenamiento/imagenes/etiquetas/';

    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');

        //http://back.academiacomarca.com
        //http://127.0.0.1:8000
    }

    /**
     * Descripcion: esta funcion lista todas las etiquetas
     * Tipo: GET
     * URL: api/etiquetas
     * @Autor: @AlexAguilarP
     */
    public function index()
    {
        $etiquetas = Etiqueta::orderBy('id_etiqueta', 'desc')->get();
        return response()->json($etiquetas);
    }
    /**
     * Descripcion: esta funcion realiza el registro de una nueva etiqueta
     * Tipo: POST
     * URL: api/etiquetas/registrar
     * @Autor: @AlexAguilarP
     */
    public function registrarEtiqueta(Request $request)
    {
        $etiqueta = new Etiqueta;
        $etiqueta->nombre_etiqueta = $request->nombre_etiqueta;
        $etiqueta->descripcion_etiqueta = $request->descripcion_etiqueta;
        if ($request->hasFile('imagen_etiqueta')) {
            // subir la imagen al servidor
            $archivo = $request->file('imagen_etiqueta');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_imagen = $etiqueta->nombre_etiqueta . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->ruta), $nombre_imagen);
            // registrar los datos del usuario
            $etiqueta->imagen_etiqueta = $this->hostBackend . $this->ruta . $nombre_imagen;
        } else {
            return response()->json(['mensaje' => 'error con el archivo', 'estado' => 'danger']);
        }
        $etiqueta->save();
        return response()->json(['mensaje' => 'etiqueta registrada exitosamente', 'estado' => 'success']);
    }
    /**
     * Descripcion: esta funcion actualiza los datos de una etiqueta
     * Tipo: PUT
     * URL: api/etiquetas/actualizar
     * @Autor: @AlexAguilarP
     */
    public function actualizarEtiqueta(Request $request, $id)
    {
        $etiqueta = Etiqueta::findOrFail($id);
        $etiqueta->nombre_etiqueta = $request->nombre_etiqueta;
        $etiqueta->descripcion_etiqueta = $request->descripcion_etiqueta;
        $etiqueta->estado_etiqueta = $request->estado_etiqueta;
        $etiqueta->save();
        return response()->json(['mensaje' => 'etiqueta actualizada exitosamente', 'estado' => 'success']);
    }
    /**
     * Descripcion: esta funcion actualiza la imagen de una etiqueta
     * Tipo: POST
     * URL: api/etiquetas/cambiar-imagen
     * @Autor: @AlexAguilarP
     */
    public function cambiarImagenEtiqueta(Request $request)
    {
        $etiqueta = Etiqueta::findOrFail($request->id_etiqueta);
        if ($request->hasFile('imagen_etiqueta')) {
            // subir la imagen al servidor
            $archivo = $request->file('imagen_etiqueta');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_imagen = $etiqueta->nombre_etiqueta . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->ruta), $nombre_imagen);
            // registrar los datos del usuario
            $etiqueta->imagen_etiqueta = $this->hostBackend . $this->ruta . $nombre_imagen;
            $etiqueta->save();
            return response()->json(['mensaje' => 'Registro Actualizado exitosamente', 'estado' => 'success']);
        } else {
            return response()->json(['mensaje' => 'Error Archivo no encontrado', 'estado' => 'daner']);
        }
    }
    /**
     * Descripcion: esta funcion elimina la etiqueta y todas sus relaciones con los cursos
     * Tipo: GET
     * URL: api/etiqueta/eliminar/{id}
     * @Autor: @AlexAguilarP
     */
    public function eliminarEtiqueta($id)
    {
        CursoEtiqueta::where('id_etiqueta', $id)->delete();
        Etiqueta::where('id_etiqueta', $id)->delete();
        return response()->json(['mensaje' => 'etiqueta eliminado exitosamente', 'estado' => 'success']);
    }
    /**
     * Descripcion: esta funcion lista los cursos que tengan relacion con una etiqueta
     * Tipo: GET
     * URL: api/etiqueta/cursos/{id}
     * @Autor: @AlexAguilarP
     */
    public function etiquetaCursos($id)
    {
        $cursos = Etiqueta::find($id)->cursosEtiqueta()->get();
        return response()->json($cursos);
    }

    /**
     * Descripcion: La funcion muestra la etiqueta.
     * Tipo: GET
     * URL: api/etiquetas/mostrar
     * @Autor: @AlexAguilarP
     */
    public function mostrarEtiqueta($id)
    {
        $etiqueta = Etiqueta::where('id_etiqueta', $id)->first();
        return response()->json($etiqueta);
    }
}
