<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\GiroNegocioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PagoDetalleController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\SocioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' =>'v1', 'namespace'=>'App\Http\Controllers'], function(){
    Route::apiResource('personas',PersonaController::class);
    Route::apiResource('socios',SocioController::class);
    Route::apiResource('puestos',PuestoController::class);
    Route::apiResource('giro-negocios',GiroNegocioController::class);
    Route::apiResource('blocks',BlockController::class);
    Route::apiResource('cuotas',CuotaController::class);
    Route::apiResource('documentos',DocumentoController::class);
    Route::apiResource('deudas',DeudaController::class);
    Route::apiResource('servicios',ServicioController::class);
    Route::apiResource('pago',PagoController::class);
    Route::apiResource('pago_detalle',PagoDetalleController::class);
});
Route::get('/csrf-token', function () {
    return response()->json([
        'token' => csrf_token()
    ]);
});