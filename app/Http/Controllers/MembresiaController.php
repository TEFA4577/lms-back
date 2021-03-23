<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Membresia;
use App\Docente;
use App\Usuario;


class MembresiaController extends Controller
{
    public function index()
    {
        $membresia = Membresia::orderBy('id_membresia', 'asc')
                    ->get();
        return response()->json($membresia);
    }

    public function habilitarMembresia($id, $estado){
        $membresia = Membresia::findOrFail($id);
        if ($estado == 'aprobado') {

        }
    }
}
