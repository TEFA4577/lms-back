<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;
use App\EncuestaRol;

class RolController extends Controller
{
    public function index()
    {
        $roles = Rol::orderBy('id_rol', 'desc')->get();
        return response()->json($roles);
    }
}
