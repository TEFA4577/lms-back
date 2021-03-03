<?php

namespace App\Http\Controllers;

use App\RedSocial;
use Illuminate\Http\Request;

class RedSocialController extends Controller
{
    /**
     * Descripcion: La funcion realiza el registro de una nueva red social para un usuario
     * Tipo: POST
     * URL: usuario-red-social/registrar
     * @autor: @AlexAguilarP
     */
    public function registrarRedSocial(Request $request)
    {
        $redSocial = new RedSocial();
        $redSocial->id_usuario = $request->id_usuario;
        $redSocial->url_red = $request->url_red;
        $redSocial->tipo_red = $request->tipo_red;
        $redSocial->save();
        return response()->json(['mensaje' => 'Registro Creado Exitosamente']);
    }
    /**
     * Descripcion: La funcion realiza la actualizacion de datos de una red social de un usuario
     * Tipo: PUT
     * URL: usuario-red-social/actualizar/{id}
     * @autor: @AlexAguilarP
     */
    public function actualizarRedSocial(Request $request, $id)
    {
        $redSocial = RedSocial::findOrFail($id);
        $redSocial->url_red = $request->url_red;
        $redSocial->tipo_red = $request->tipo_red;
        $redSocial->save();
        return response()->json(['mensaje' => 'Registro Actualizado Exitosamente']);
    }
    /**
     * Descripcion: La funcion realiza el borrado de una red social
     * Tipo: GET
     * URL: usuario-red-social/eliminar/{id}
     * @autor: @AlexAguilarP
     */
    public function eliminarRedSocial($id)
    {
        RedSocial::where('id_usuario_red', $id)->delete();
        return response()->json(['mensaje' => 'Registro Eliminado Exitosamente']);
    }
}
