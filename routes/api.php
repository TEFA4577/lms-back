<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// RUTAS PARA USUARIOS
Route::post('login', 'UsuarioController@login');
Route::post('usuario/registrar', 'UsuarioController@registrarUsuario');
Route::get('usuario/mis-cursos/{id}', 'UsuarioController@misCursos');
Route::get('usuario/cursos-creados/{id}', 'UsuarioController@cursosCreados');
Route::get('usuario/cursos-estudiantes/{id}', 'UsuarioController@misEstudiantes');
Route::post('adquirir-curso', 'UsuarioController@adquirirCurso');
Route::get('habilitar-curso/{id}/{estado}', 'CursoController@habiliarCurso');
Route::post('usuario-red-social/registrar', 'RedSocialController@registrarRedSocial');
Route::put('usuario-red-social/actualizar/{id}', 'RedSocialController@actualizarRedSocial');
Route::get('usuario-red-social/eliminar/{id}', 'RedSocialController@eliminarRedSocial');
// GRUPO DE RUTAS POR AUTENTICACION
Route::group(['middleware' => 'auth:api'], function () {
    Route::put('usuario/actualizar/{id}', 'UsuarioController@actualizarUsuario');
    Route::post('usuario/cambiar-password', 'UsuarioController@cambiarPassword');
    Route::get('informacion-usuario/{id}', 'UsuarioController@informacionUsuario');
    Route::get('logout', 'UsuarioController@logout');
    /** TODAS LAS RUTAS QUE SE DEBEN PROTEGER */
});

// RUTAS PARA DOCENTE
Route::get('docentes', 'DocenteController@index');
Route::post('docentes/registrar', 'DocenteController@registrarDocente');
Route::put('docentes/actualizar/{id}', 'DocenteController@actualizarDocente');
Route::get('docentes/mostrar/{id}', 'DocenteController@mostrarDocente');
Route::post('docentes/cambiar-video', 'DocenteController@actualizarVideo');
Route::post('docentes/cambiar-cv', 'DocenteController@actualizarCv');

// RUTAS PARA CURSOS
Route::get('cursos', 'CursoController@index');
Route::get('cursos-no-revisados', 'CursoController@listarCursosNoAprobados');
Route::post('cursos/cambiar-estado', 'CursoController@cambiarEstadoCurso');
Route::get('cursos/{id}', 'CursoController@listadoDeCursoPorEtiqueta');
Route::get('cursos/buscar/{texto}', 'CursoController@listadoDeCursoPorNombreDescripcion');
Route::post('cursos/registrar', 'CursoController@registrarCurso');
Route::put('cursos/actualizar/{id}', 'CursoController@actualizarCurso');
Route::put('cursos/registrar-etiqueta/{id}', 'CursoController@registrarCursoEtiquetas');
Route::post('cursos/cambiar-imagen', 'CursoController@cambiarImagenCurso');
Route::get('cursos/mostrar/{id}', 'CursoController@mostrarCurso');
Route::get('cursar-curso/{id}', 'CursoController@cursarCurso');
Route::get('cursos/eliminar/{id}', 'CursoController@eliminarCurso');
Route::get('cursos/eliminar-etiquetas/{id}', 'CursoController@eliminarCursoEtiquetas');
Route::get('cursos-solicitados', 'CursoController@listarSolicitudes');
Route::put('progreso-curso/{id}', 'CursoController@progresoCurso');
Route::get('cursos-de-docente/{id}', 'CursoController@cursosDeDocente');
Route::get('certificado/{idUsuarioCurso}', 'CursoController@certificado');

// RUTAS PARA MODULOS
Route::post('modulos/registrar', 'ModuloController@registrarModulo');
Route::put('modulos/actualizar/{id}', 'ModuloController@actualizarModulo');
Route::get('modulos/mostrar/{id}', 'ModuloController@mostrarModulo');
Route::get('modulos/eliminar/{id}', 'ModuloController@eliminarModulo');

// RUTAS PARA CLASES
Route::post('clases/registrar', 'ClaseController@registrarClase');
Route::put('clases/actualizar/{id}', 'ClaseController@actualizarClase');
Route::get('clases/mostrar/{id}', 'ClaseController@mostrarClase');
Route::get('clases/eliminar/{id}', 'ClaseController@eliminarClase');
Route::post('clases/cambiar-video', 'ClaseController@cambiarVideo');

// RUTAS PARA RECURSOS
Route::post('recursos/registrar', 'RecursoController@registrarRecurso');
Route::put('recursos/actualizar/{id}', 'RecursoController@actualizarRecurso');
Route::get('recursos/mostrar/{id}', 'RecursoController@mostrarRecurso');
Route::get('recursos/eliminar/{id}', 'RecursoController@eliminarRecurso');

// RUTAS PARA ETIQUETAS
Route::get('etiquetas', 'EtiquetaController@index');
Route::post('etiquetas/registrar', 'EtiquetaController@registrarEtiqueta');
Route::put('etiquetas/actualizar/{id}', 'EtiquetaController@actualizarEtiqueta');
Route::get('etiquetas/mostrar/{id}', 'EtiquetaController@mostrarEtiqueta');
Route::put('etiquetas/cambiar-imagen', 'EtiquetaController@cambiarImagenEtiqueta');
Route::get('etiquetas/cursos/{id}', 'EtiquetaController@etiquetaCursos');
Route::put('etiquetas/eliminar/{id}', 'EtiquetaController@eliminarEtiqueta');
Route::get('usuario/mis-solicitudes/{id}', 'UsuarioController@misSolicitudes');

//RUTAS PARA FORO
Route::get('comentarios', 'ForoController@index');
Route::get('comentarios/clase/{id}', 'ForoController@comentariosRespuestasClase');
Route::post('comentarios/registrar', 'ForoController@registrarComentario');
Route::post('comentarios/respuesta/registrar', 'ForoController@registrarRespuestaComentario');

//RUTAS PARA PREGUNTAS
Route::post('pregunta/registrar', 'PreguntaController@registrarPregunta');
Route::get('pregunta/mostrar/{id}', 'PreguntaController@mostrarPregunta');
Route::get('pregunta/list', 'PreguntaController@listarPregunta');
Route::put('pregunta/actualizar/{id}', 'PreguntaController@actualizarPregunta');
Route::get('pregunta/eliminar/{id}', 'PreguntaController@elimarPregunta');
//RUTAS PARA RESPUESTAS PREGUNTAS
Route::get('respuesta-pregunta/mostrar/{id}', 'PreguntaController@mostrarRespuestaPregunta');
Route::post('respuesta-pregunta/registrar', 'PreguntaController@registrarRespuestaPregunta');
Route::get('respuesta-pregunta/list', 'PreguntaController@listarRespuestaPregunta');
Route::put('respuesta-pregunta/actualizar/{id}', 'PreguntaController@actualizarRespuestaPregunta');
Route::get('respuesta-pregunta/eliminar/{id}', 'PreguntaController@eliminarRespuestaPregunta');
//RUTAS PARA ENCUESTAS
Route::post('encuesta/registrar', 'EncuestaController@registrarEncuesta');
Route::post('encuesta/registrar-pregunta', 'EncuestaController@registrarPregunta');
Route::post('encuesta/registrar-respuesta', 'EncuestaController@registrarRespuesta');
Route::get('encuesta/list', 'EncuestaController@listarPreguntasEncuesta');
Route::put('encuesta/actualizar/{id}', 'EncuestaController@actualizarEncuesta');
Route::put('encuesta/actualizar-pregunta/{id}', 'EncuestaController@actualizarPreguntaEncuesta');
Route::put('encuesta/eliminar/{id}', 'EncuestaController@EliminarEncuesta');
Route::put('encuesta/eliminar-pregunta/{id}', 'EncuestaController@EliminarPreguntaEncuesta');
//RUTAS PARA MEMBRESIA
Route::get('membresias/{id}', 'MembresiaController@mostrarMembresia');
Route::get('membresias', 'MembresiaController@listarMembresia');
Route::post('membresias/registrar', 'MembresiaController@registrarMembresia');
Route::put('membresias/actualizar/{id}', 'MembresiaController@actualizarMembresia');
Route::get('membresias/activar-desactivar/{id}', 'MembresiaController@habilitarMembresia');
//Route::put('membresias/deshabilitar/{id}', 'MembresiaController@deshabilitarMembresia');
Route::get('membresias/eliminar/{id}', 'MembresiaController@eliminarMembresia');
