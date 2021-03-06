<?php

namespace App\Http\Controllers;

use App\Curso;
use App\Modulo;
use App\Clase;
use GuzzleHttp\Client;
use App\Mail\RecuperarPasswordMail;
use App\Mail\PagoMoneMail;
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
        //envio del correo electronico
        $correo = $usuario->correo_usuario;
        $data = [
            'nombre_usuario' => $usuario->nombre_usuario
        ];
        Mail::to($correo)->send(new RegistroUsuario($data));
        $usuario->save();
        return response()->json(['mensaje' => 'Registro creado exitosamente', 'estado' => 'success']);
    }


    public function enviarCorreo()
    {
        $data = [
            'nombre_usuario' => 'holaaaaaaaaaaaa'
        ];
        Mail::to('tefihvmoonwalker77746@gmail.com')
            ->cc('gonza.monti4@gmail.com')
            ->send(new RegistroUsuario($data));
        return response()->json(['mensaje' => 'Enviado', 'estado' => 'success']);
    }

    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        /*if ($request->foto_usuario) {
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
            $usuario->foto_usuario = $request->foto_usuario;
        }*/
        $usuario->nombre_usuario = $request->nombre_usuario;
        $usuario->correo_usuario = $request->correo_usuario;
        //$usuario->foto_usuario = $request->foto_usuario;
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

    public function actualizarFotoUsuario(Request $request, $id){
        $usuario = Usuario::findOrFail($id);
        $usuario->foto_usuario = $request->foto_usuario;
        $usuario->save();
        return response()->json([
            'mensaje' => 'actualizado con exito',
            'estado' => 'success',
            'datosUsuario' => $usuario
        ]);
    }

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

    public function sendEmailPass(Request $request)
    {
        //$usuario = Usuario::findOrFail($request->correo_usuario);
        //$usuario =  Usuario::where('correo_usuario', $request->correo_usuario)->first();

        //envio del correo electronico
        /*$correo = $usuario->correo_usuario;
        $data = [
            'nombre_usuario' => $usuario->nombre_usuario
        ];
        Mail::to($correo)->send(new RecuperarPasswordMail($data));

        $usuario = Usuario::findOrFail($correo);
        $usuario->password_usuario = bcrypt($request->nuevo_password);
        $usuario->save();
        return response()->json(['mensaje' => 'Contraseña modificada existosamente', 'estado' => 'success']);*/

        $verificarEmail = Usuario::Where('correo_usuario', $request->correo_usuario)->first();
        if (isset($verificarEmail) != null) {

            //envio del correo electronico
            $correo = $verificarEmail->correo_usuario;
            $data = [
                'nombre_usuario' => $verificarEmail->nombre_usuario
            ];
            Mail::to($correo)->send(new RecuperarPasswordMail($data));
            //envio del correo electronico

            return response()->json(['mensaje' => 'Revise su bandeja de entrada en su email.', 'estado' => 'success']);
        } else {
            return response()->json(['mensaje' => 'Este correo no se encuentra asociado a una cuenta dentro de la plataforma.', 'estado' => 'danger']);
        }
    }


    public function resetPass(Request $request)
    {
        $verificarEmail = Usuario::Where('correo_usuario', $request->correo_usuario)->first();
        if (isset($verificarEmail) != null) {
            //$usuario = Usuario::findOrFail($request->correo_usuario);
            $verificarEmail->password_usuario = bcrypt($request->password);
            $verificarEmail->update();
            return response()->json(['mensaje' => 'contraseña cambiada existosamente', 'estado' => 'success']);
        } else {
            return response()->json(['mensaje' => 'Este correo no se encuentra asociado a una cuenta dentro de la plataforma.', 'estado' => 'danger']);
        }
    }

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

    public function cursosCreados($id)
    {
        $usuario = Curso::where('id_usuario', $id)
            ->where('estado', 1)
            ->with('etiquetasCurso')->get();
        return response()->json($usuario);
    }

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
                if ($curso->precio != 0) {
                    $usuarioCurso->comprobante = $request->comprobante;
                    // subir la imagen al servidor
                    //$archivo = $request->file('comprobante');
                    // Nombre del archivo con el que se guardara en el servidor
                    //$nombre_foto = time() . "_" . $archivo->getClientOriginalName();
                    // ruta donde se guardara la imagen en el servidor
                    //$archivo->move(public_path($this->rutaComprobate), $nombre_foto);
                    // registrar los datos del usuario
                    //$usuarioCurso->comprobante = $this->hostBackend . $this->rutaComprobate . $nombre_foto;
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


    public function moneAdquirirCurso(Request $request)
    {
        //DATOS DEL CURSO Y DEL USUARIO
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

            //DATOS MONE
            $usuarioCurso->comprobante = $this->hostBackend . $this->rutaImagenComprobante . "/sin_imagen.jpg";
            $client = new Client();
            $response = $client->request('POST', "https://www.monepagos.com/testpago/generaToken", [
                'json' => [
                    "PK" => "13tDNs6liAesigu-_N-c8A16Y_ObBK3e",
                    "SK" => "9521aea6e011fc84629768d27faeb2e546045bf64cc111bc4b5900544c420bd9be513a8e9565a3c9957ea1f1118f5636032bce497b2f978d16c706b2a7f7fc11Gkkv4Ed1pC3yom1UY3z9f4WThEQRvG8hV1ux6ngyyL0="
                ]
            ]);
            $response_body = json_decode($response->getBody()->getContents());
            $values = get_object_vars($response_body);
            $token = $values["token"];
            $client2 = new Client();

            $response2 = $client2->request(
                'POST',
                "https://www.monepagos.com/testpago/registra",
                [
                    'json' => [
                        "Token" => $token,
                        "Monto" => $curso->precio,
                        //"Curso" => $curso->nombre_curso,
                        "Glosa" => $curso->nombre_curso,
                        "CodigoVenta" => 'ACAD',
                        "Correo" => 'Gracias por la compra de tu curso!'
                    ]
                ]
            );

            $response_body2 = json_decode($response2->getBody()->getContents());
            $values2 = get_object_vars($response_body2);
            $link = $values2["urlpago"];
            $idmone = $values2["idpago"];

            //CORREO PARA MANDAR EL LINK DE PAGO
            $usuario = Usuario::find($usuarioCurso->id_usuario);

            $correo = $usuario->correo_usuario;
            $data = [
                'nombre_curso' => $curso->nombre_curso,
                'nombre_usuario' => $usuario->nombre_usuario,
                'enlace' => $link
            ];

            Mail::to($correo)->send(new PagoMoneMail($data));

            $usuarioCurso->save();

            return response()->json(['mensaje' => 'curso añadido a su cuenta', 'token' => $token, 'values' => $values2, 'urlpago' => $link, 'idpago' => $idmone], 200);
        } else {
            return response()->json(['mensaje' => 'el curso se encuentra en proceso de confirmación o ya se encuentra adquirido']);
        }
    }

    public function misSolicitudes($id)
    {
        $solicitudes = UsuarioCurso::where('id_usuario', $id)->with('cursoSolicitado')->get();
        return response()->json($solicitudes);
    }
}
