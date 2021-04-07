<?php

namespace App\Http\Controllers;

use App\Clase;
use App\Curso;
use App\CursoEtiqueta;
use App\Etiqueta;
use App\Modulo;
use App\Recurso;
use App\Usuario;
use App\UsuarioCurso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use PDF;
use Illuminate\Support\Carbon;

class CursoController extends Controller
{
    public $hostBackend;
    public $ruta = '/almacenamiento/imagenes/cursos';

    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
        //'http://back.academiacomarca.com'
        //http://127.0.0.1:8000
    }
    /**
     * Descripcion: La funcion devuelve todos los cursos aprobados ordenados por id descendentemente.
     * Tipo: GET
     * URL: api/cursos
     * @Autor: @AlexAguilarP
     */
    public function index()
    {
        $cursos = Curso::orderBy('id_curso', 'desc')
                        ->where('estado_curso', 'aprobado')
                        ->with('etiquetasCurso')->get();
        return response()->json($cursos);
    }

    public function estadoCursos()
    {
        $cursos = Curso::orderBy('id_curso', 'desc')
                        ->where('estado_curso', 'aprobado')
                        ->with('etiquetasCurso')->get();
        return response()->json($cursos);
    }

    /**
     * Descripcion: La funcion devuelve todos los cursos no aprobados ordenados por id descendentemente
     * Tipo: GET
     * URL: api/cursos-no-revisados
     * @Autor: @AlexAguilarP
     */
    public function listarCursosNoAprobados()
    {
        $cursos = Curso::orderBy('id_curso', 'desc')->where('estado_curso', 'no revisado')->with('etiquetasCurso')->get();
        return response()->json($cursos);
    }
    /**
     * Descripcion: esta funcion cambia el estado de un curso
     * Tipo: POST
     * URL: api/cursos/cambiar-estado
     * @Autor: @AlexAguilarP
     */
    public function cambiarEstadoCurso(Request $request)
    {
        $curso = Curso::findOrFail($request->id_curso);
        $curso->estado_curso = $request->estado_curso;
        $curso->usuario_revisor = $request->id_usuario;
        if ($request->mensaje != '') {
            $curso->mensaje = $request->mensaje;
        }
        $curso->save();
        return response()->json(['mensaje' => 'Cambio de estado exitoso', 'estado' => 'success']);
    }
    /**
     * Descripcion: La funcion devuelve todos los cursos ordenados por id descendentemente.
     * Tipo: GET
     * URL: api/cursos
     * @Autor: @AlexAguilarP
     */
    public function listadoDeCursoPorEtiqueta($id)
    {
        $cursos = Etiqueta::findOrFail($id)->cursosEtiqueta;
        return response()->json($cursos);
    }
    /**
     * Descripcion: La funcion devuelve todos los cursos de un docente que esten aprobados ordenados por id descendentemente.
     * Tipo: GET
     * URL: api/cursos-de-docente/{id}
     * @Autor: @AlexAguilarP
     */
    public function cursosDeDocente($id)
    {
        $cursos = Curso::orderBy('id_curso', 'desc')
            ->where('estado_curso', 'aprobado')
            ->where('id_usuario', $id)
            ->with('etiquetasCurso')
            ->get();
        return response()->json($cursos);
    }
    /**
     * Descripcion: esta funcion lista todas los cursos que tengan en el nombre o descripcion el texto recibido
     * Tipo: GET
     * URL: api/cursos/buscar/{texto}
     * Parametro: texto
     * @Autor: @AlexAguilarP
     */
    public function listadoDeCursoPorNombreDescripcion($texto)
    {
        $cursos = Curso::where('nombre_curso', 'like', '%' . $texto . '%')
            ->orWhere('descripcion_curso', 'like', '%' . $texto . '%')
            ->get();
        return response()->json($cursos);
    }
    /**
     * Descripcion: La funcion registra un curso.
     * Tipo: POST
     * URL: api/cursos/registrar
     * @Autor: @AlexAguilarP
     */
    public function registrarCurso(Request $request)
    {
        $curso =  new Curso;
        $curso->id_usuario = $request->id_usuario;
        $curso->nombre_curso = $request->nombre_curso;
        $curso->descripcion_curso = $request->descripcion_curso;
        $curso->precio = $request->precio;
        if ($request->hasFile('imagen_curso')) {
            // subir la imagen al servidor
            $archivo = $request->file('imagen_curso');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_imagen = $request->nombre_curso . '-' . $request->id_usuario  . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            // $archivo->move(public_path($this->ruta), $nombre_imagen);
            $archivo = $request->file('imagen_curso')->store('public' . $this->ruta);
            $curso->imagen_curso = $this->hostBackend . Storage::url($archivo);
        } else {
            $curso->imagen_curso = $this->hostBackend . $this->ruta . "/sin_imagen.jpg";
        }
        $curso->save();
        return response()->json(['mensaje' => 'Registro Realizado con Exito', 'estado' => 'success']);
    }
    /**
     * Descripcion: esta funcion registra las etiquetas (categorias) del curso
     * Tipo: PUT
     * URL: api/cursos/registrar-etiquetas/{id}
     * @Autor: @AlexAguilarP
     */
    public function registrarCursoEtiquetas(Request $request, $id)
    {
        $curso = CursoEtiqueta::where('id_etiqueta', $request->id_etiqueta)->where('id_curso', $id)->first();
        if ($curso) {
            return response()->json(['mensaje' => 'la etiqueta ya esta relacinada con el curso']);
        }
        $etiquetas = new CursoEtiqueta;
        $etiquetas->id_curso = $id;
        $etiquetas->id_etiqueta = $request->id_etiqueta;
        $etiquetas->save();
        return response()->json(['mensaje' => 'registro exitoso']);
    }
    /**
     * Descripcion: esta funcion elimina todas las etiquetas de un curso
     * Tipo: GET
     * URL: api/cursos/eliminar-etiquetas/{id}
     * @Autor: @AlexAguilarP
     */
    public function eliminarCursoEtiquetas($id)
    {
        CursoEtiqueta::where('id_curso', $id)->delete();
        return response()->json(['mensaje' => 'etiquetas eliminadas', 'estado' => 'success']);
    }

    /**
     * Descripcion: La funcion devuelve los datos de un curso
     * Tipo: GET
     * URL: api/cursos/mostrar/{id}
     * @Autor: @AlexAguilarP
     */
    public function mostrarCurso($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->etiquetasCurso;
        $modulos = Modulo::where('id_curso', $id)->with('clasesModulo')->get();
        $docente = Usuario::find($curso->id_usuario);
        $docente->datosDocente;
        return response()->json([
            'curso' => $curso,
            'modulos' => $modulos,
            'docente' => $docente
        ]);
    }
    public function cursarCurso($id)
    {
        $cursoUsuario = UsuarioCurso::findOrFail($id);
        $curso = Curso::find($cursoUsuario->id_curso);
        $modulos = Modulo::where('id_curso', $cursoUsuario->id_curso)->with('clasesModulo')->get();

        return response()->json([
            'cursoUsuario' => $cursoUsuario,
            'curso' => $curso,
            'modulos' => $modulos,
        ]);
    }
    public function eliminarCurso($id)
    {
        $curso = Curso::findOrFail($id);
        $curso->delete();
        return response()->json(['mensaje' => 'Borrado con Exito', 'estado' => 'success']);
    }

    public function inhabilitarCurso($id)
    {
        $curso = Curso::where('id_curso', $id)->first();
        $curso->estado = 0;
        $curso->save();
        return response()->json(['mensaje' => 'Inhabilitado', 'estado' => 'danger']);
    }

    public function habilitarCurso($id)
    {
        $curso = Curso::where('id_curso', $id)->first();
        $curso->estado = 1;
        $curso->save();
        return response()->json(['mensaje' => 'Habilitado', 'estado' => 'success']);
    }

    /**
     * Descripcion: La funcion actualiza los datos de un curso
     * Tipo: PUT
     * URL: api/cursos/actualizar/{id}
     * @Autor: @AlexAguilarP
     */
    public function actualizarCurso(Request $request, $id)
    {
        $curso = Curso::findOrFail($id);
        if ($request->hasFile('imagen_curso')) {
            // subir la imagen al servidor
            $archivo = $request->file('imagen_curso')->store('public' . $this->ruta);
            $curso->imagen_curso = $this->hostBackend . Storage::url($archivo);
        }
        $curso->nombre_curso = $request->nombre_curso;
        $curso->descripcion_curso = $request->descripcion_curso;
        $curso->precio = $request->precio;
        $curso->save();
        return response()->json(['mensaje' => 'Actualización Realizada con Exito', 'estado' => 'success']);
    }

    /**
     * Descripcion: La funcion cambia la imagen de un curso
     * Tipo: POST
     * URL: api/cursos/cambiar-imagen
     * @Autor: @AlexAguilarP
     */
    public function cambiarImagenCurso(Request $request)
    {
        $curso = Curso::findOrFail($request->id_curso);
        if ($request->hasFile('imagen_curso')) {
            // subir la imagen al servidor
            $archivo = $request->file('imagen_curso')->store('public' . $this->ruta);
            $curso->imagen_curso = $this->hostBackend . Storage::url($archivo);
        }
        $curso->save();
        return response()->json(['mensaje' => 'Cambio de imagen exitoso', 'estado' => 'success']);
    }
    /**
     * Descripcion: La funcion cambia el estado dela relacion de un usuario con un curso
     * Tipo: GET
     * URL: api/habiliar-curso/{id}
     * @Autor: @AlexAguilarP
     */
    public function habiliarCurso($id, $estado)
    {
        $usuarioCurso = UsuarioCurso::findOrFail($id);
        if ($estado == 'aprobado') {
            $curso = Curso::find($usuarioCurso->id_curso);
            $curso->modulosCurso;
            $progreso = array();
            foreach ($curso->modulosCurso as $modulo) {
                $arr = array(
                    'id_modulo' => $modulo->id_modulo,
                    'nombre_modulo' => $modulo->nombre_modulo,
                    'estado' => False
                );
                $mod = ($arr);
                array_push($progreso, $mod);
            }
            $usuarioCurso->progreso_curso = $progreso;
            $usuarioCurso->estado_usuario_curso = 'adquirido';
            $usuarioCurso->save();
            $solicitudesAnteriores =  UsuarioCurso::where('id_usuario', $usuarioCurso->id_usuario)
                ->where('id_curso', $usuarioCurso->id_curso)
                ->where('estado_usuario_curso', 'no confirmado')
                ->orWhere('estado_usuario_curso', 'rechazado')
                ->delete();
            return response()->json(['mensaje' => 'curso se a habilitado', 'curso' => $usuarioCurso]);
        } else if ($estado == 'rechazado') {
            $usuarioCurso->estado_usuario_curso = 'rechazado';
            $usuarioCurso->save();
            return response()->json(['mensaje' => 'la solicitud fue rechazada']);
        }
    }
    public function progresoCurso(Request $request, $id)
    {
        $cursoUsuario = UsuarioCurso::find($id);
        $cursoUsuario->progreso_curso = $request->progreso_curso;
        $cursoUsuario->save();
        return response()->json(['mensaje' => 'curso se a habilitado', 'curso' => $cursoUsuario]);
    }
    /**
     * Descripcion: la funcion lista las peticiones de una adquisicion de un curso por medio de deposito
     * Tipo: GET
     * URL: api/cursos-solicitados
     * @Autor: @AlexAguilarP
     */
    public function listarSolicitudes()
    {
        $solicitudes = UsuarioCurso::where('estado_usuario_curso', 'no confirmado')->with('cursoSolicitado', 'usuario')->get();
        return response()->json($solicitudes);
    }
    /**
     * Descripcion: la funcion crea un pdf con los datos del estudiante y el curso
     * Tipo: GET
     * URL: api/certificado/{idUsuarioCurso}
     * @Autor: @AlexAguilarP
     */
    public function certificado($idUsuarioCurso)
    {
        $datos = UsuarioCurso::find($idUsuarioCurso);
        if (!$datos) {
            return response()->json(['mensaje' => 'error', 'estado' => 'danger', 'user'=>$datos]);
        }
        $curso = Curso::find($datos->id_curso);
        $usuario = Usuario::find($datos->id_usuario);
        $fecha = Carbon::parse($datos->updated_at)
            ->format('d-m-Y');

        $data = [
            'nombre_curso' => $curso->nombre_curso,
            'nombre_usuario' => $usuario->nombre_usuario,
            'fecha_fin' => $fecha,
        ];
        $pdf = PDF::loadView('certificado', $data);
        return $pdf->stream('archivo.pdf');
    }
}
