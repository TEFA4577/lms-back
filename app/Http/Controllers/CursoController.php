<?php

namespace App\Http\Controllers;

use App\Clase;
use App\Curso;
use App\CursoEtiqueta;
use App\Docente;
use App\Etiqueta;
use App\Modulo;
use App\Recurso;
use App\Usuario;
use App\UsuarioCurso;
use App\Membresia;
use App\MembresiaDocente;
use App\Prueba;
use App\UsuarioEvaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Carbon;
use App\Mail\AprobarCursoSolicitud;
use App\Mail\AprobacionCompraCursoMail;
use Illuminate\Support\Facades\Mail;

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
            ->where('membresia_curso', '!=', 'FIN')
            ->where('estado', 1)
            ->with('etiquetasCurso')
            ->with('cursoEstudiante')
            ->with('modulosCurso')
            // ->with(['membresiaDocente'=> function($q){
            //     $q->where('estado_membresia_usuario', 'adquirido');}])
            ->get();
        return response()->json($cursos);
    }


    public function estadoCursos()
    {
        $cursos = Curso::orderBy('id_curso', 'desc')
            ->where('estado_curso', '=', 'aprobado')
            ->get();
        //->with('id_usuario')->get();
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

        // $membresia = MembresiaDocente::where('id_usuario', $curso->id_usuario)
        //     ->where('estado_membresia_usuario', 'adquirido')
        //     ->first();
        // if ($curso->membresia_curso == 'FIN') {
        //     return response()->json(['mensaje' => 'Necesita adquirir una membresia, para enviar su curso a revisi??n', 'estado' => 'error']);
        // } elseif ($curso->membresia_curso == 'INICIO') {
        //     $memb = Membresia::find($membresia->id_membresia);
        //     if ($memb->precio_membresia == 0) {
        //         $usuario = Curso::where('id_usuario', $curso->id_usuario)
        //             ->get('id_curso');

        //         $numUc = count($usuario);
        //         if ($numUc > 3) {
        //             return response()->json(['mensaje' => 'La cantidad de cursos permitidos en la membresia gratuita lleg?? a su l??mite', 'estado' => 'warning']);
        //         }
        //     }
        // }

        $modulos = Modulo::where('id_curso', $curso->id_curso)
            ->with('clasesModulo')
            ->get('id_modulo');
        $numM = count($modulos);
        if ($numM > 0) {
            foreach ($modulos as $value) {
                if (!count($value['clasesModulo'])) {
                    return response()->json(['mensaje' => 'Su m??dulo necesita clases', 'estado' => 'error']);
                }
            }
        } else {
            return response()->json(['mensaje' => 'Su curso necesita m??dulo', 'estado' => 'error']);
        }
        $prueba = Prueba::where('id_curso', $curso->id_curso)
            ->with('opcionCorrecta')
            ->get('id_prueba');
        $numP = count($prueba);
        if ($numP > 0) {
            foreach ($prueba as $value) {
                if (!count($value['opcionCorrecta'])) {
                    return response()->json(['mensaje' => 'Las preguntas de ex??men necesitan una respuesta correcta', 'estado' => 'error']);
                }
            }
        } else {
            return response()->json(['mensaje' => 'Su curso necesita preguntas para el ex??men', 'estado' => 'error']);
        }
        $curso->save();

         //envio de correo electronico
         $usuario = Usuario::find($curso->id_usuario);
         $correo = $usuario->correo_usuario;
         $curso = Curso::find($curso->id_curso);
         $curso->modulosCurso;
         $progreso = array();

         $data = [
             'nombre_curso' => $curso->nombre_curso,
             'nombre_usuario' => $curso->nombre_usuario
         ];
         Mail::to($correo)->send(new AprobarCursoSolicitud($data));
         //nevio de correo electronico
        return response()->json(['mensaje' => 'Solicitud realizada con exito', 'estado' => 'success']);
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
            ->where('estado', 1)
            ->where('id_usuario', $id)
            ->where('membresia_curso', '!=', 'FIN')
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
        $curso->imagen_curso = $request->imagen_curso;
        $membresia = MembresiaDocente::where('id_usuario', $curso->id_usuario)
            ->where('estado_membresia_usuario', 'adquirido')
            ->first();
        if ($curso->precio == 0) {
            $curso->membresia_curso = 'GRATIS';
        } elseif ($membresia) {
            $curso->membresia_curso = 'INICIO';
        } else {
            $curso->membresia_curso = 'FIN';
            return response()->json(['mensaje' => 'Necesita adquirir una membresia, para registrar su curso', 'estado' => 'error']);
        }
        //validando membresia seg??n precio
        $memb = Membresia::find($membresia->id_membresia);
        if ($memb->precio_membresia == 0) {
            $usuario = Curso::where('id_usuario', $curso->id_usuario)
                ->get('id_curso');

            $numUc = count($usuario);
            if ($numUc > 2) {
                return response()->json(['mensaje' => 'La cantidad de cursos permitidos de su membresia actual lleg?? a su l??mite', 'estado' => 'error']);
            }
        }
        //fin
        /*if ($request->hasFile('imagen_curso')) {
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
        }*/
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
            return response()->json(['mensaje' => 'la etiqueta ya esta relacionada con el curso']);
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
        $prueba = Prueba::where('id_curso', $id)->with('pruebaOpcion')->get();
        $docente->datosDocente;
        $usuarioCurso = UsuarioCurso::where('id_curso', $curso->id_curso)->with('usuario')->get();
        //$estudiante = UsuarioCurso::where('id_curso' , $id)->with('usuarioCursos')->get();
        return response()->json([
            'curso' => $curso,
            'modulos' => $modulos,
            'docente' => $docente,
            'prueba' => $prueba,
            'usuarioCurso' => $usuarioCurso,
            //'estudiante' => $estudiante
        ]);
    }
    public function cursarCurso($id)
    {
        $cursoUsuario = UsuarioCurso::findOrFail($id);
        $curso = Curso::find($cursoUsuario->id_curso);
        $modulos = Modulo::where('id_curso', $cursoUsuario->id_curso)->with('clasesModulo')->get();
        $evaluacion = UsuarioEvaluacion::where('id_curso', $cursoUsuario->id_curso)
            ->where('id_usuario', $cursoUsuario->id_usuario)->first();
        return response()->json([
            'cursoUsuario' => $cursoUsuario,
            'curso' => $curso,
            'modulos' => $modulos,
            'evaluacion' => $evaluacion,
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
        $membresia = MembresiaDocente::where('id_usuario', $curso->id_usuario)
            ->where('estado_membresia_usuario', 'adquirido')
            ->first();
        if ($curso->precio == 0) {
            $curso->membresia_curso = 'GRATIS';
        } elseif ($membresia) {
            $curso->membresia_curso = 'INICIO';
        } else {
            $curso->membresia_curso = 'FIN';
        }
        $curso->save();
        return response()->json(['mensaje' => 'Actualizaci??n Realizada con Exito', 'estado' => 'success']);
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
            //envio de correo electronico
            $usuario = Usuario::find($usuarioCurso->id_usuario);
            $correo = $usuario->correo_usuario;
            $curso = Curso::find($usuarioCurso->id_curso);
            $curso->modulosCurso;
            $progreso = array();

            $data = [
                'nombre_curso' => $curso->nombre_curso,
                'nombre_usuario' => $usuarioCurso->nombre_usuario
            ];
            Mail::to($correo)->send(new AprobacionCompraCursoMail($data));
            //nevio de correo electronico

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

            $examen = UsuarioEvaluacion::where('id_usuario', $usuarioCurso->id_usuario)
                ->where('id_curso', $usuarioCurso->id_curso)
                ->get();
            if (count($examen) == 0) {
                $result = new UsuarioEvaluacion;
                $result->id_curso = $usuarioCurso->id_curso;
                $result->id_usuario = $usuarioCurso->id_usuario;
                $result->progreso_evaluacion = json_encode(0);
                $result->save();
            }

            $solicitudesAnteriores =  UsuarioCurso::where('id_usuario', $usuarioCurso->id_usuario)
                ->where('id_curso', $usuarioCurso->id_curso)
                ->where('estado_usuario_curso', 'no confirmado')
                ->orWhere('estado_usuario_curso', 'rechazado')
                ->delete();
            return response()->json(['mensaje' => 'El curso se ha habilitado', 'curso' => $usuarioCurso]);
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
        return response()->json(['mensaje' => 'curso se ha habilitado', 'curso' => $cursoUsuario]);
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
            return response()->json(['mensaje' => 'error', 'estado' => 'error', 'user' => $datos]);
        }
        $curso = Curso::find($datos->id_curso);
        $docente = Usuario::where('id_usuario', $curso->id_usuario)->pluck('nombre_usuario')->first();
        $usuario = Usuario::find($datos->id_usuario);
        $fecha = Carbon::parse($datos->updated_at)
            ->format('d-m-Y');

        $data = [
            'nombre_curso' => $curso->nombre_curso,
            'nombre_usuario' => $usuario->nombre_usuario,
            'docente' => $docente,
            'fecha_fin' => $fecha,
        ];
        $pdf = PDF::loadView('certificado', $data);
        return $pdf->stream('archivo.pdf');
    }
}
