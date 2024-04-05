<?php

use App\Http\Controllers\API\AmbienteController;
use App\Http\Controllers\API\HorarioDisponibleController;
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

Route::get('ambientes', [AmbienteController::class, 'index']);
Route::get('list/ambientes', [AmbienteController::class, 'list']);
//Route::get('list/horarios-diponibles', [HorarioDisponibleController::class, 'list']);
Route::post('ambientes', [AmbienteController::class, 'store']);
Route::post('horariosDisponibles', [HorarioDisponibleController::class, 'guardarHorasDisponibles']);