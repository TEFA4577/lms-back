<?php

namespace App\Http\Controllers;

use App\Recurso;
use Illuminate\Http\Request;

class RecursoController extends Controller
{
    public $hostBackend;
    public $ruta = '/almacenamiento/recursos/';

    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
    }

    /**
     * Descripcion: La funcion registra un recurso.
     * Tipo: POST
     * URL: api/recursos/registrar
     * @Autor: @AlexAguilarP
     */
    public function registrarRecurso(Request $request)
    {
        $recurso =  new Recurso;
        $recurso->id_clase = $request->id_clase;
        $recurso->nombre_recurso = $request->nombre_recurso;
        $recurso->id_recurso_tipo = 1;
        $recurso->link_recurso = $request->link_recurso;
        /*if ($request->hasFile('link_recurso')) {
            // subir el archivo al servidor
            $archivo = $request->file('link_recurso');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_archivo = $request->nombre_recurso . '-' . $request->id_clase . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->ruta), $nombre_archivo);
            $recurso->link_recurso = $this->hostBackend . $this->ruta . $nombre_archivo;
        } else {
            return response()->json(['mensaje' => 'No se encontró el archivo', 'estado' => 'danger']);
        }*/
        $recurso->save();
        return response()->json(['mensaje' => 'Registro Realizado con éxito', 'estado' => 'success']);
    }

    /**
     * Descripcion: La funcion devuelve los datos de un recurso
     * Tipo: GET
     * URL: api/recursos/mostrar/{id}
     * @Autor: @AlexAguilarP
     */
    public function mostrarRecurso($id)
    {
        $recurso = Recurso::findOrFail($id);
        $tipoRecurso = Recurso::find($id)->tipoRecurso;
        return response()->json(['recurso' => $recurso, 'tipo' => $tipoRecurso]);
    }

    /**
     * Descripcion: La funcion actualiza los datos de un recurso
     * Tipo: PUT
     * URL: api/recursos/actualizar/{id}
     * @Autor: @AlexAguilarP
     */
    public function actualizarRecurso(Request $request, $id)
    {
        $recurso = Recurso::findOrFail($id);
        $recurso->nombre_recurso = $request->nombre_recurso;
        $recurso->id_recurso_tipo = $request->id_recruso_tipo;
        $recurso->save();
        return response()->json(['mensaje' => 'Actualización Realizada con Exito', 'estado' => 'success']);
    }

    /**
     * Descripcion: La funcion cambia la imagen de un recurso
     * Tipo: POST
     * URL: api/recursos/cambiar-imagen
     * @Autor: @AlexAguilarP
     */
    public function cambiarArchivoRecurso(Request $request)
    {
        $recurso = Recurso::findOrFail($request->id_recurso);
        if ($request->hasFile('link_recurso')) {
            // subir el archivo al servidor
            $archivo = $request->file('link_recurso');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_archivo = $request->nombre_recurso . '-' . $request->id_clase . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->ruta), $nombre_archivo);
            $recurso->imagen_recurso = $this->hostBackend . $this->ruta . $nombre_archivo;
        }
        $recurso->save();
        return response()->json(['mensaje' => 'Cambio de imagen exitoso', 'estado' => 'success']);
    }
    /**
     * Descripcion: La funcion devuelve los datos de un recurso
     * Tipo: GET
     * URL: api/recursos/eliminar/{id}
     * @Autor: @AlexAguilarP
     */
    public function eliminarRecurso($id)
    {
        $recurso = Recurso::find($id);
        $recurso->delete();
        return response()->json(['mensaje' => 'Eliminado con éxito', 'estado' => 'success']);
    }
}
