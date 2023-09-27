<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MiPerfilController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\RecuperarClaveController;
use App\Http\Controllers\TemarioController;
use App\Http\Controllers\UsuarioController;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

goto b1wLA; b1wLA: Route::get("\x2f", function () { return view("\x77\x65\x6c\143\157\x6d\x65"); })->name("\x77\145\154\x63\157\x6d\145"); goto cwWWR; Irfwu: Route::get("\57\141\x63\x63\145\163\157", function () { return view("\x61\165\164\150\x2f\154\157\x67\151\156"); })->name("\x61\143\143\x65\163\x6f"); goto paZDu; cwWWR: Auth::routes(array("\x76\x65\162\151\146\171" => true)); goto Irfwu; paZDu: Route::get("\57\x68\x6f\x6d\145", array(App\Http\Controllers\HomeController::class, "\151\156\144\145\x78"))->name("\150\157\x6d\x65")->middleware("\x76\x65\x72\x69\146\151\145\x64");

//recuperar contraseña
Route::get("recuperar-clave", [RecuperarClaveController::class, "index"])->name('recuperar.index');
Route::POST("recuperarClaveEnviar", [RecuperarClaveController::class, "enviarCorreo"])->name('recuperar.enviar');


//ruta de busqueda
Route::resource("busqueda", BusquedaController::class);
Route::get('busquedaCertif', [BusquedaController::class, 'buscar'])->name('busqueda.buscar');
Route::get('enviarCorreo/{id}', [BusquedaController::class, 'enviar'])->name('correo.enviar');
Route::get('enviarCodigo/{participante}/{curso}/{codigo}', [BusquedaController::class, 'enviarCodigo'])->name('correo.enviarCodigo');
Route::get('vistaPrevia/{participante}/{codigo?}', [BusquedaController::class, 'verCertificado'])->name('busqueda.ver');
Route::get("verMiCertificado/{id_certificado}/{id_participante}", [BusquedaController::class, "verPDF"])->name('busqueda.miCertificado');
Route::get("verMiCertificadoQR/{id_participante}", [BusquedaController::class, "verPDFQR"])->name('busqueda.miCertificadoQR');



Route::resource("curso", CursoController::class)->middleware('verified');
Route::get('export/curso', [CursoController::class, 'exportCurso'])->name('exportCurso.index')->middleware('verified');
Route::get('buscar/curso/{id}', [CursoController::class, 'buscar'])->name('curso.buscar')->middleware('verified');
Route::get('curso/ver/curso/{id}', [CursoController::class, 'ver'])->name('curso.ver')->middleware('verified');


Route::resource("temario", TemarioController::class)->middleware('verified');
Route::get('export/temario', [TemarioController::class, 'exportTemario'])->name('exportTemario.index')->middleware('verified');

Route::resource("participante", ParticipanteController::class)->middleware('verified');
Route::get('export/participante', [ParticipanteController::class, 'exportParticipante'])->name('exportParticipante.index')->middleware('verified');
Route::get('exportModelo/participante', [ParticipanteController::class, 'exportModeloParticipante'])->name('exportModeloParticipante.index')->middleware('verified');
Route::POST('importParticipante', [ParticipanteController::class, 'importParticipante'])->name('importParticipante.index')->middleware('verified');
Route::PUT('modificarCertificadoParticipante/{id}', [ParticipanteController::class, 'modificarCert'])->name('participante.modCer')->middleware('verified');
Route::get('eliminarCertificadoParticipante/{id}', [ParticipanteController::class, 'eliminarCert'])->name('participante.eliCer')->middleware('verified');
Route::get('eliminarTodoParticipante', [ParticipanteController::class, 'eliminarTodo'])->name('participante.eliminarTodo')->middleware('verified');
Route::get('crearQR/{id_participante}', [ParticipanteController::class, 'crearQR'])->name('participante.crearQR')->middleware('verified');

//buscar y ver participante
Route::get('buscar/participante/{id}', [ParticipanteController::class, 'buscar'])->name('participante.buscar')->middleware('verified');
Route::get('participante/ver/participante/{id}', [ParticipanteController::class, 'ver'])->name('participante.ver')->middleware('verified');



Route::resource("usuario", UsuarioController::class)->middleware('verified');
Route::post("modificar/foto/{id}", [UsuarioController::class, "modificarFoto"])->name('modificarFoto.update')->middleware('verified');
Route::get("eliminar/foto/{id}", [UsuarioController::class, "eliminarFoto"])->name('eliminarFoto.delete')->middleware('verified');


Route::resource("certificado", CertificadoController::class)->middleware('verified');
Route::get("verPDF/{id}", [CertificadoController::class, "verPDF"])->name('certificado.verPDF')->middleware('verified');
Route::PUT("certificadoAdd/{id}", [CertificadoController::class, "add"])->name('certificado.add')->middleware('verified');
Route::get('buscar/certificado/{id}', [CertificadoController::class, 'buscar'])->name('certificado.buscar')->middleware('verified');
Route::get('certificado/ver/certificado/{id}', [CertificadoController::class, 'ver'])->name('certificado.ver')->middleware('verified');
Route::get('certificado/eliminarModelo/{id}', [CertificadoController::class, 'delete'])->name('certificado.eliminarModelo')->middleware('verified');

Route::resource("empresa", EmpresaController::class)->middleware('verified');
Route::post("updateImg", [EmpresaController::class, "updateImg"])->name('empresa.updateImg')->middleware('verified');
Route::get("eliminarImg/{id}", [EmpresaController::class, "eliminarImg"])->name('empresa.eliminarImg')->middleware('verified');


//cambiar datos mi perfil y password
Route::get("miPerfil", [MiPerfilController::class, "miPerfilIndex"])->name('perfil.index')->middleware('verified');
Route::post("miPerfilUpdate", [MiPerfilController::class, "miPerfilEditar"])->name('perfil.update')->middleware('verified');
Route::get("miPassword", [MiPerfilController::class, "miPasswordIndex"])->name('password.index')->middleware('verified');
Route::post("miPasswordUpdate", [MiPerfilController::class, "miPasswordEditar"])->name('password.update')->middleware('verified');
Route::post('perfil-update-perfil', [MiPerfilController::class, 'perfilUpdatePerfil'])->name('perfil.updatePerfil')->middleware('verified');
Route::get('perfil-delete-perfil-{id}', [MiPerfilController::class, 'perfilDeletePerfil'])->name('perfil.deletePerfil')->middleware('verified');
