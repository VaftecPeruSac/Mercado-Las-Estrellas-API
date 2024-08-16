<?php

use App\Http\Controllers\PersonaController;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Models\Persona;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/personas', function () {
//     // Aquí puedes obtener y devolver los datos de la tabla personas
//     return Persona::all();
// })->withoutMiddleware([VerifyCsrfToken::class]);

// Route::post('/personas', function () {
//     // Aquí puedes obtener y devolver los datos de la tabla personas
//     return PersonaController::store();
// })->withoutMiddleware([VerifyCsrfToken::class]);
