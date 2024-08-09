<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\GiroNegocioController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PuestoController;
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
});
