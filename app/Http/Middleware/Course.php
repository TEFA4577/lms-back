<?php

namespace App\Http\Middleware;

use Closure;
use App\UsuarioCurso;

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
        $cursoUsuario = UsuarioCurso::findOrFail($id);
        if($cursoUsuario){
            return $next($request);
        }
        abort(403, "¡No puedes acceder al curso!.");
    }
}
