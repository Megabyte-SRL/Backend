<?php

use App\Http\Controllers\API\AmbienteController;
use App\Http\Controllers\API\HorarioDisponibleController;
use App\Http\Controllers\API\SolicitudAmbienteController;
use App\Http\Controllers\API\UsuarioController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [UsuarioController::class, 'login']);
Route::post('usuarios', [UsuarioController::class, 'store']);
//Route::get('ambientes', [AmbienteController::class, 'index']);
Route::post('ambientes', [AmbienteController::class, 'store']);
Route::delete('ambientes/{id}', [AmbienteController::class, 'eliminar']);
Route::get('list/ambientes', [AmbienteController::class, 'list']);
//Route::get('list/horarios-diponibles', [HorarioDisponibleController::class, 'list']);
Route::post('horariosDisponibles', [HorarioDisponibleController::class, 'guardarHorasDisponibles']);
Route::get('list/horariosDisponibles', [HorarioDisponibleController::class, 'list']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('solicitudesAmbientes', [SolicitudAmbienteController::class, 'guardarSolicitudAmbiente']);
});