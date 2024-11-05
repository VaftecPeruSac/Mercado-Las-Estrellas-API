<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Authorization,Origin, Content-Type, X-Auth-Token, X-XSRF-TOKEN');

use App\Http\Controllers\BlockController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\GiroNegocioController;
use App\Http\Controllers\InquilinoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PagoDetalleController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('change-password', [LoginController::class, 'changePassword']);
    Route::get('validaciones', [LoginController::class, 'validaciones']);
    Route::get('ventanas', [LoginController::class, 'ventanas']);
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers'], function () {
    Route::apiResource('personas', PersonaController::class);
    Route::apiResource('usuarios', UsuarioController::class);
    Route::get('socios/exportar', [SocioController::class, 'export']);
    Route::get('socios/exportar-pdf', [SocioController::class, 'exportPDF']);
    Route::apiResource('socios', SocioController::class);
    Route::apiResource('inquilinos', InquilinoController::class);
    Route::get('puestos/libre', [PuestoController::class, 'indexLibre']);
    Route::get('puestos/select', [PuestoController::class, 'select']); //1
    Route::get('puestos/totalPuestos', [PuestoController::class, 'obtenerTotalPuestos']);
    Route::get('puestos/areaTotal', [PuestoController::class, 'obtenerAreaTotal']);
    Route::get('puestos/exportar', [PuestoController::class, 'export']);
    Route::get('puestos/exportar-pdf', [PuestoController::class, 'exportPDF']);
    Route::apiResource('puestos', PuestoController::class); //2
    Route::post('puestos/asignar', [PuestoController::class, 'asignar']); //3
    Route::get('cuotas/pendientes', [CuotaController::class, 'deudaPendientes']);
    Route::get('cuotas/exportar', [CuotaController::class, 'export']);
    Route::get('cuotas/exportar-pdf', [CuotaController::class, 'exportPDF']);
    Route::apiResource('cuotas', CuotaController::class);
    Route::get('block/select', [BlockController::class, 'select']);
    Route::get('deudacuota/{id_puesto}', [PagoController::class,'ListaDeudaCuotas']);
    Route::apiResource('blocks', BlockController::class);
    Route::apiResource('giro-negocios', GiroNegocioController::class);
    Route::apiResource('documentos', DocumentoController::class);
    Route::apiResource('deudas', DeudaController::class);
    Route::get('servicios/exportar', [ServicioController::class, 'export']);
    Route::get('servicios/exportar-pdf', [ServicioController::class, 'exportPDF']);
    Route::apiResource('servicios', ServicioController::class);
    Route::get('pagos/exportar', [PagoController::class, 'export']);
    Route::get('pagos/exportar-pdf', [PagoController::class, 'exportPDF']);
    Route::apiResource('pagos', PagoController::class);
    Route::apiResource('deudas', DeudaController::class);
    Route::apiResource('pago_detalle', PagoDetalleController::class);
    Route::get('reportes/pagos', [ReporteController::class, 'pagos']);
    Route::get('reportes/deudas', [ReporteController::class, 'deudas']);
    Route::get('reportes/cuota-por-metros', [ReporteController::class, 'cuotaPorMetros']);
    Route::get('reportes/cuota-por-puestos', [ReporteController::class, 'cuotaPorPuestos']);
    Route::get('reportes/dashboard', [ReporteController::class, 'dashboard']);
    Route::get('reportes/resumen-por-puestos', [ReporteController::class, 'resumenPorPuestos']);
});

Route::get('/csrf-token', function () {
    return response()->json([
        'token' => csrf_token()
    ]);
});
