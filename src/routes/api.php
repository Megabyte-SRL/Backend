<?php

use App\Http\Controllers\API\AmbienteController;
use App\Http\Controllers\API\HorarioDisponibleController;
use App\Http\Controllers\API\HorarioMateriasController;
use App\Http\Controllers\API\DocenteController;
use App\Http\Controllers\API\SolicitudAmbienteController;
use App\Http\Controllers\API\UsuarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarCorreo;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|

Route::post('enviar-correo', function(){
    Mail::to('cokodx@gmail.com')->send(new EnviarCorreo);
    return "correo enviado exitosamente";
})->name('enviar-correo');

*/

Route::post('login', [UsuarioController::class, 'login']);
Route::post('usuarios', [UsuarioController::class, 'store']);
Route::post('ambientes', [AmbienteController::class, 'store']);
Route::post('ambientes-archivo', [AmbienteController::class, 'subirArchivo']);
Route::delete('ambientes/{id}', [AmbienteController::class, 'eliminar']);
Route::get('list/ambientes', [AmbienteController::class, 'list']);
Route::post('horariosDisponibles', [HorarioDisponibleController::class, 'guardarHorasDisponibles']);
Route::get('list/horariosDisponibles', [HorarioDisponibleController::class, 'list']);
Route::post('horariosMateriasArchivo', [HorarioMateriasController::class, 'subirArchivo']);
Route::get('list/docentesMateria/{materia_id}', [DocenteController::class, 'obtenerDocentesPorMateria']);
Route::get('list/solicitudesAmbientes', [SolicitudAmbienteController::class, 'list']);
Route::post('reservarAmbiente/{solicitud_id}', [SolicitudAmbienteController::class, 'reservar']);


Route::middleware('auth:sanctum')->group(function() {
    Route::get('informacionUsuario', [UsuarioController::class, 'obtenerInformacion']);
    Route::post('actualizarUsuario', [UsuarioController::class, 'actualizarInformacion']);
    Route::get('list/materiasGrupos', [DocenteController::class, 'obtenerMateriasGrupos']);
    Route::post('solicitudesAmbientes', [SolicitudAmbienteController::class, 'guardarSolicitudAmbiente']);
});







// Ruta para enviar correo
Route::post('enviar-correo', function (Request $request) {
    $solicitudId = $request->input('solicitudId');
    $email = 'kevinfernandezcoca@gmail.com'; // Correo fijo
    
    // Opcional: validación para asegurar que la solicitud existe
    $solicitud = \App\Models\SolicitudAmbiente::find($solicitudId);
    if (!$solicitud) {
        return response()->json(['message' => 'Solicitud no encontrada'], 404);
    }

    try {
        Mail::to($email)->send(new EnviarCorreo($solicitudId));
        return response()->json(['message' => 'Correo enviado exitosamente'], 200);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Error al enviar el correo', 'error' => $e->getMessage()], 500);
    }
})->name('enviar-correo');

// Ruta para obtener una solicitud por ID
Route::get('/solicitud/{id}', [SolicitudAmbienteController::class, 'obtenerSolicitud']);