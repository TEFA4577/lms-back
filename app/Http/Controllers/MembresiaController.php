<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Membresia;
use App\Docente;
use App\Usuario;


class MembresiaController extends Controller
{
    public function index(){
        $solicitud = MembresiaDocente::orderBy('id_membresia_docente', 'asc')
                                    ->with('membresiaSolicitada')
                                    ->with('docente')
                                    ->get();
    }
    public function membresia()
    {
        $membresia = Membresia::orderBy('id_membresia', 'asc')
                    ->with('membresiaDocente')
                    ->get();
        return response()->json($membresia);
    }
    public function registrarMembresia(){
        // $membresia = Membresia::
    }
    public function habilitarMembresia($id, $estado){
        $membresia = Membresia::findOrFail($id);
        if ($estado == 'aprobado') {

        }
    }
}
