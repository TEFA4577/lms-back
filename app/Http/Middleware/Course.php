<?php

namespace App\Http\Middleware;

use Closure;
use App\UsuarioCurso;
use Illuminate\Support\Facades\Auth;

class Course
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = $request->id;
		$user=Auth::user()->id_usuario;
        $cursoUsuario = UsuarioCurso::findOrFail($id);
        if($cursoUsuario->id_usuario == $user){
            return $next($request);
        }
		return route('cursos');
    }
}
