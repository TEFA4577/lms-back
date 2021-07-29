<?php

namespace App\Http\Controllers;

use App\Curso;
use App\Usuario;
use App\UsuarioCurso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\RegistroUsuario;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public $hostBackend;
    public $ruta = '/almacenamiento/imagenes/usuarios/foto-perfil/';
    public $rutaImagenComprobante = '/almacenamiento/imagenes/cursos';
    public $rutaComprobate = '/almacenamiento/imagenes/comprobantes/';
    public $tiempoBloqueado = 1;

    public function __construct()
    {
        $this->hostBackend = env("HOST_BACKEND", 'http://back.academiacomarca.com');
    }
    /**
     * Descripcion: esta funcion realiza el inicio de sesion y devuelve un token
     * Tipo: POST
     * URL: api/login
     * @Autor: @AlexAguilarP
     */
    public function login(Request $request)
    {
        $usuario = Usuario::where('correo_usuario', $request->correo_usuario)->first();
        if (!is_null($usuario)) {
            if (Hash::check($request->password, $usuario->password_usuario)) {
                $token = $usuario->createToken('LMS-BACKEND')->accessToken;
                $usuario->intentos = 0;
                $usuario->fecha_intento = null;
                $usuario->save();
                $usuario->rolUsuario;
                $usuario->datosDocente;
                $usuario->redesDocente;
                return response()->json([
                    'token' => $token,
                    'usuario' => $usuario,
                    'mensaje' => 'Bienvenido al sistema',
                ]);
            } elseif ($usuario->intentos < 5) {
                $usuario->intentos += 1;
                $usuario->save();
                return response()->json(['error' => 'Contraseña incorrecta.'], 401);
            } else {
                if (!$usuario->fecha_intento) {
                    $usuario->fecha_intento = Carbon::now()->addMinutes($this->tiempoBloqueado);
                    $usuario->save();
                    return response()->json(['error' => 'Demasiados intentos, fuiste bloqueado por ' . $this->tiempoBloqueado . ' minutos.'], 401);
                } else {
                    if (Carbon::now() <= $usuario->fecha_intento) {
                        $date = new Carbon($usuario->fecha_intento);
                        $minutes = Carbon::now()->diffInMinutes($date);
                        if ($minutes > 0) {
                            return response()->json(['error' => 'Demasiados intentos, intente nuevamente en ' . $minutes . ' minutos.'], 401);
                        } else {
                            $seconds = Carbon::now()->diffInSeconds($date);
                            return response()->json(['error' => 'Demasiados intentos, intente nuevamente en ' . $seconds . ' segundos.'], 401);
                        }
                    } else {
                        $usuario->intentos = 1;
                        $usuario->fecha_intento = null;
                        $usuario->save();
                        return response()->json(['error' => 'Contraseña incorrecta.'], 401);
                    }
                }
            }
        } else {
            return response()->json([
                'mensaje' => 'usuario no registrado'
            ]);
        }
    }
    /**
     * Descripcion: esta funcion realiza el cierre de sesion y elimina todos los tokens del usuario
     * Tipo:GET
     * URL: api/logout
     * @Autor: @AlexAguilarP
     */
    public function logout()
    {
        $user = auth()->user();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });
        $user->save;
        return response()->json([
            'mensaje' => 'Saliste de la plataforma'
        ]);
    }
    /**
     * Descripcion: La funcion registra al usuario
     * Tipo: POST
     * URL: api/usuario/registrar
     * @Autor: @AlexAguilarP
     */
    public function registrarUsuario(Request $request)
    {
        $verificarEmail = Usuario::Where('correo_usuario', $request->correo_usuario)->first();
        if (isset($verificarEmail) != null) {
            return response()->json(['mensaje' => 'El Correo Ya esta registrado', 'estado' => 'danger']);
        }
        $usuario = new Usuario();
        $usuario->nombre_usuario = $request->nombre_usuario;
        $usuario->id_rol = 3;
        $usuario->correo_usuario = $request->correo_usuario;
        $usuario->password_usuario = bcrypt($request->password);
        if ($request->hasFile('foto')) {
            // subir la imagen al servidor
            $archivo = $request->file('foto');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_foto = $request->correo_usuario . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->ruta), $nombre_foto);
            // registrar los datos del usuario
            $usuario->foto_usuario = $this->hostBackend . $this->ruta . $nombre_foto;
        } else {
            if ($request->foto == '') {
                $usuario->foto_usuario = $this->hostBackend . $this->ruta . 'avatar.jpg';
            } else {
                $usuario->foto_usuario = $request->foto;
            }
        }
        $correo = $usuario->correo_usuario;
        //envio del correo electronico
        $data = ['name' => 'hola'];
        Mail::to($correo)->send(new RegistroUsuario($data));
        $usuario->save();
        return response()->json(['mensaje' => 'Registro creado exitosamente', 'estado' => 'success']);
    }
    /**
     * Descripcion: La funcion actualiza los datos basicos del usuario
     * Tipo: PUT
     * URL: api/usuario/actualizar/{id}
     * @Autor: @AlexAguilarP
     */
    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        if ($request->hasFile('foto')) {
            // subir la imagen al servidor
            $archivo = $request->file('foto');
            // $archivoNombre = $archivo->getClientOriginalName();
            $extension = $archivo->getClientOriginalExtension();
            // Nombre del archivo con el que se guardara en el servidor
            $nombre_foto = $usuario->correo_usuario . '.' . $extension;
            // ruta donde se guardara la imagen en el servidor
            $archivo->move(public_path($this->ruta), $nombre_foto);
            // registrar los datos del usuario
            $usuario->foto_usuario = $this->hostBackend . $this->ruta . $nombre_foto;
        }
        $usuario->nombre_usuario = $request->nombre_usuario;
        $usuario->correo_usuario = $request->correo_usuario;
        $usuario->save();
        $usuario->rolUsuario;
        $usuario->datosDocente;
        $usuario->redesDocente;
        return response()->json([
            'mensaje' => 'actualizado con exito',
            'estado' => 'success',
            'datosUsuario' => $usuario
        ]);
    }
    /**
     * Descripcion: La funcion lista los datos del usuario
     * Tipo: GET
     * URL: api/informacion-usuario/{id}
     * @Autor: @AlexAguilarP
     */
    public function informacionUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->rolUsuario;
        $usuario->datosDocente;
        $usuario->redesDocente;
        return response()->json([
            'datosUsuario' => $usuario
        ]);
    }
    /**
     * Descripcion: La funcion cambia la contraseña de un usuario
     * Tipo: POST
     * URL: api/usuario/cambiar-password
     * @Autor: @AlexAguilarP
     */
    public function cambiarPassword(Request $request)
    {
        $usuario = Usuario::findOrFail($request->id_usuario);
        $usuario->password_usuario = bcrypt($request->nuevo_password);
        $usuario->save();
        return response()->json(['mensaje' => 'contraseña cambiada existosamente', 'estado' => 'success']);
    }
    /**
     * Descripcion: esta funcion lista los cursos del usuario que esta registrado
     * Tipo: GET
     * URL: api/usuario/mis-cursos/{id}
     * @Autor: @AlexAguilarP
     */
    public function misCursos($id)
    {
        $usuario = UsuarioCurso::where('id_usuario', $id)
            ->with('cursoSolicitado')
            ->orderBy('id_curso', 'desc')
            ->get();
        return response()->json($usuario);
    }

    public function misEstudiantes($id)
    {
        $curso = Curso::where('id_usuario', $id)
            ->with('cursoEstudiante')
            ->with('cursoEvaluacion')
            ->get();
        // $prueba = UsuarioEvaluacion::orderBy('id_usuario_evaluacion', 'asc')
        //                 ->where('id_curso', $curso->id_curso)
        //                 ->get();
        return response()->json($curso);
    }

    /**
     * Descripcion: esta funcion lista los cursos creados por el usuario
     * Tipo: GET
     * URL: api/usuario/cursos-creados/{id}
     * @Autor: @AlexAguilarP
     */
    public function cursosCreados($id)
    {
        $usuario = Curso::where('id_usuario', $id)
            ->where('estado', 1)
            ->with('etiquetasCurso')->get();
        return response()->json($usuario);
    }
    /**
     * Descripcion: La funcion registra la adquisicion de un usuario con un curso
     * Tipo: POST
     * URL: api/adquirir-curso
     * @Autor: @AlexAguilarP
     */
    public function adquirirCurso(Request $request)
    {
        $verificar = UsuarioCurso::where('id_usuario', $request->id_usuario)
            ->where('id_curso', $request->id_curso)
            ->where('estado_usuario_curso', 'no confirmado')
            ->orWhere('estado_usuario_curso', 'aprobado')
            ->first();
        if (!$verificar) {
            $usuarioCurso = new UsuarioCurso;
            $usuarioCurso->id_usuario = $request->id_usuario;
            $usuarioCurso->id_curso = $request->id_curso;
            $curso = Curso::find($request->id_curso);
            if ($curso->precio == 0) {
                $usuarioCurso->estado_usuario_curso = 'no confirmado';
                $usuarioCurso->comprobante = $this->hostBackend . $this->rutaImagenComprobante . "/sin_imagen.jpg";
            } else {
                if ($request->hasFile('comprobante')) {
                    // subir la imagen al servidor
                    $archivo = $request->file('comprobante');
                    // Nombre del archivo con el que se guardara en el servidor
                    $nombre_foto = time() . "_" . $archivo->getClientOriginalName();
                    // ruta donde se guardara la imagen en el servidor
                    $archivo->move(public_path($this->rutaComprobate), $nombre_foto);
                    // registrar los datos del usuario
                    $usuarioCurso->comprobante = $this->hostBackend . $this->rutaComprobate . $nombre_foto;

                    //Mail::to($usuarioCurso)

                } else {
                    return response()->json(['mensaje' => 'error', 'estado' => 'danger']);
                }
            }
            $usuarioCurso->save();
            return response()->json(['mensaje' => 'curso añadido a su cuenta']);
        } else {
            return response()->json(['mensaje' => 'el curso se encuentra en proceso de confirmación o ya se encuentra adquirido']);
        }
    }

    public function enviarCorreo()
    {
    }

    public function misSolicitudes($id)
    {
        $solicitudes = UsuarioCurso::where('id_usuario', $id)->with('cursoSolicitado')->get();
        return response()->json($solicitudes);
    }
}
