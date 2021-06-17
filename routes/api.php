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
Route::post('/video/add', 'ClaseController@registrarVideo');
Route::post('/video/add', 'DocenteController@registrarVideo');

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
//GRUPO DE RUTAS PARA EVALUACIÓN USUARIO - CURSO
Route::get('prueba', 'PruebaController@index');
Route::get('prueba/mostrar/{id}', 'PruebaController@mostrarPrueba');
Route::post('prueba/registrar', 'PruebaController@registrarPrueba');
Route::put('prueba/actualizar/{id}', 'PruebaController@actualizarPrueba');
Route::get('prueba/eliminar/{id}', 'PruebaController@eliminarPrueba');
Route::post('opcion/registrar', 'PruebaController@registrarOpcion');
Route::get('opcion/mostrar/{id}', 'PruebaController@mostrarOpcion');
Route::put('opcion/actualizar/{id}', 'PruebaController@actualizarOpcion');
Route::get('opcion/eliminar/{id}', 'PruebaController@eliminarOpcion');
Route::get('prueba-evaluar/{id}/{datos}', 'PruebaController@darExamen');
Route::get('prueba/evaluando/{id}/{idC}/{idU}', 'PruebaController@evaluarExamen');
Route::post('inicio/evaluacion', 'PruebaController@inicioExamen');
Route::put('evaluacion-progreso/{id}', 'PruebaController@resultExamen');
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
Route::get('docentes-adm', 'DocenteController@listaDocente');
Route::get('docente/habilitar/{id}', 'DocenteController@habiliarDocente');
Route::post('docentes/registrar', 'DocenteController@registrarDocente');
Route::put('docentes/actualizar/{id}', 'DocenteController@actualizarDocente');
Route::get('docentes/mostrar/{id}', 'DocenteController@mostrarDocente');
Route::post('docentes/cambiar-video', 'DocenteController@actualizarVideo')->middleware('auth:api');
Route::post('docentes/cambiar-cv', 'DocenteController@actualizarCv')->middleware('auth:api');

// RUTAS PARA CURSOS
Route::get('cursos', 'CursoController@index');
Route::get('estado-cursos', 'CursoController@estadoCursos');
Route::get('cursos-no-revisados', 'CursoController@listarCursosNoAprobados');
Route::post('cursos/cambiar-estado', 'CursoController@cambiarEstadoCurso');
Route::get('cursos/{id}', 'CursoController@listadoDeCursoPorEtiqueta');
Route::get('cursos/buscar/{texto}', 'CursoController@listadoDeCursoPorNombreDescripcion');
Route::post('cursos/registrar', 'CursoController@registrarCurso')->middleware('auth:api');
Route::put('cursos/actualizar/{id}', 'CursoController@actualizarCurso')->middleware('auth:api');
Route::put('cursos/registrar-etiqueta/{id}', 'CursoController@registrarCursoEtiquetas')->middleware('auth:api');
Route::post('cursos/cambiar-imagen', 'CursoController@cambiarImagenCurso')->middleware('auth:api');
Route::get('cursos/mostrar/{id}', 'CursoController@mostrarCurso');
Route::get('cursar-curso/{id}', 'CursoController@cursarCurso')->middleware('auth:api');
Route::get('cursos/eliminar/{id}', 'CursoController@eliminarCurso')->middleware('auth:api');
Route::get('cursos/habilitar/{id}', 'CursoController@habilitarCurso')->middleware('auth:api');
Route::get('cursos/inhabilitar/{id}', 'CursoController@inhabilitarCurso')->middleware('auth:api');
Route::get('cursos/eliminar-etiquetas/{id}', 'CursoController@eliminarCursoEtiquetas');
Route::get('cursos-solicitados', 'CursoController@listarSolicitudes')->middleware('auth:api');
Route::put('progreso-curso/{id}', 'CursoController@progresoCurso');
Route::get('cursos-de-docente/{id}', 'CursoController@cursosDeDocente');
Route::get('certificado/{idUsuarioCurso}', 'CursoController@certificado');

// RUTAS PARA MODULOS
Route::post('modulos/registrar', 'ModuloController@registrarModulo');
Route::put('modulos/actualizar/{id}', 'ModuloController@actualizarModulo');
Route::get('modulos/mostrar/{id}', 'ModuloController@mostrarModulo');
Route::get('modulos/eliminar/{id}', 'ModuloController@eliminarModulo');

// RUTAS PARA CLASES
Route::post('clases/registrar', 'ClaseController@registrarClase')->middleware('auth:api');
Route::put('clases/actualizar/{id}', 'ClaseController@actualizarClase')->middleware('auth:api');
Route::get('clases/mostrar/{id}', 'ClaseController@mostrarClase');
Route::get('clases/eliminar/{id}', 'ClaseController@eliminarClase')->middleware('auth:api');
Route::post('clases/cambiar-video', 'ClaseController@cambiarVideo')->middleware('auth:api');

// RUTAS PARA RECURSOS
Route::post('recursos/registrar', 'RecursoController@registrarRecurso')->middleware('auth:api');
Route::put('recursos/actualizar/{id}', 'RecursoController@actualizarRecurso')->middleware('auth:api');
Route::get('recursos/mostrar/{id}', 'RecursoController@mostrarRecurso');
Route::get('recursos/eliminar/{id}', 'RecursoController@eliminarRecurso')->middleware('auth:api');

// RUTAS PARA ETIQUETAS
Route::get('etiquetas', 'EtiquetaController@index');
Route::post('etiquetas/registrar', 'EtiquetaController@registrarEtiqueta');
Route::put('etiquetas/actualizar/{id}', 'EtiquetaController@actualizarEtiqueta');
Route::get('etiquetas/mostrar/{id}', 'EtiquetaController@mostrarEtiqueta');
Route::put('etiquetas/cambiar-imagen', 'EtiquetaController@cambiarImagenEtiqueta');
Route::get('etiquetas/cursos/{id}', 'EtiquetaController@etiquetaCursos');
Route::get('etiquetas/eliminar/{id}', 'EtiquetaController@eliminarEtiqueta');
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
Route::put('encuesta/registrar-rol/{id}', 'EncuestaController@registrarEncuestaRoles');
Route::post('encuesta/registrar-pregunta', 'EncuestaController@registrarPregunta');
Route::post('encuesta/registrar-respuesta', 'EncuestaController@registrarRespuesta');
Route::get('encuestas/list', 'EncuestaController@listarEncuestas');
Route::get('encuesta/mostrar/{id}', 'EncuestaController@mostrarEncuesta');
Route::get('encuesta-preguntas/list', 'EncuestaController@listarPreguntasEncuesta');
Route::get('encuesta-preguntas/mostrar/{id}', 'EncuestaController@mostrarPregunta');
Route::get('encuesta-respuestas/cantidad/{id}', 'EncuestaController@cantRes');
Route::put('encuesta/actualizar/{id}', 'EncuestaController@actualizarEncuesta');
Route::put('encuesta/actualizar-pregunta/{id}', 'EncuestaController@actualizarPreguntaEncuesta');
Route::get('encuesta/eliminar/{id}', 'EncuestaController@DeshabilitarEncuesta');
Route::get('encuesta/eliminar-pregunta/{id}', 'EncuestaController@EliminarPreguntaEncuesta');
//RUTAS PARA ROLES Y ENCUESTA ROLES
Route::get('roles', 'RolController@index');
Route::put('encuesta/registrar-rol/{id}', 'EncuestaController@registrarEncuestaRoles');
Route::get('encuesta/eliminar-rol/{id}', 'EncuestaController@eliminarEncuestaRoles');


//RUTAS PARA MEMBRESIA
Route::get('membresias/{id}', 'MembresiaController@mostrarMembresia');
Route::get('membresia/docente/{id}', 'MembresiaController@membresiaDocente');
Route::get('membresias/{id}', 'MembresiaController@listarMembresia');
Route::get('membresias-administrar', 'MembresiaController@admMembresia');
Route::post('membresias/registrar', 'MembresiaController@registrarMembresia');
Route::put('membresias/actualizar/{id}', 'MembresiaController@actualizarMembresia');
Route::put('membresias/cambiar-imagen', 'MembresiaController@cambiarImagenMem');
Route::get('membresias/eliminar/{id}', 'MembresiaController@eliminarMembresia');
Route::get('membresias-solicitada', 'MembresiaController@misSolicitudes');
Route::post('membresias/adquirir', 'MembresiaController@adquirirMembresia');
Route::get('membresia-habilitar/{id}/{estado}', 'MembresiaController@habilitarMembresia');
