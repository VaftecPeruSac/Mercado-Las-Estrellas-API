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

    // Autenticación
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('change-password', [LoginController::class, 'changePassword']);
    Route::get('validaciones', [LoginController::class, 'validaciones']);
    Route::get('ventanas', [LoginController::class, 'ventanas']);

    // Usuarios
    Route::apiResource('usuarios', UsuarioController::class);

    // Socios
    Route::get('socios/exportar', [SocioController::class, 'export']);
    Route::get('socios/exportar-pdf', [SocioController::class, 'exportPDF']);
    Route::apiResource('socios', SocioController::class);

    // Inquilinos
    Route::apiResource('inquilinos', InquilinoController::class);

    // Puestos
    Route::get('puestos/select', [PuestoController::class, 'select']);
    Route::get('puestos/libre', [PuestoController::class, 'indexLibre']);
    Route::get('puestos/totalPuestos', [PuestoController::class, 'obtenerTotalPuestos']);
    Route::get('puestos/areaTotal', [PuestoController::class, 'obtenerAreaTotal']);
    Route::get('puestos/exportar', [PuestoController::class, 'export']);
    Route::get('puestos/exportar-pdf', [PuestoController::class, 'exportPDF']);
    Route::post('puestos/asignar', [PuestoController::class, 'asignar']);
    Route::apiResource('puestos', PuestoController::class);

    // Bloques
    Route::get('block/select', [BlockController::class, 'select']);
    Route::apiResource('blocks', BlockController::class);

    // Giros de negocio
    Route::apiResource('giro-negocios', GiroNegocioController::class);

    // Servicios
    Route::get('servicios/exportar', [ServicioController::class, 'export']);
    Route::get('servicios/exportar-pdf', [ServicioController::class, 'exportPDF']);
    Route::apiResource('servicios', ServicioController::class);

    // Cuotas
    Route::get('cuotas/pendientes', [CuotaController::class, 'deudaPendientes']);
    Route::get('cuotas/exportar', [CuotaController::class, 'export']);
    Route::get('cuotas/exportar-pdf', [CuotaController::class, 'exportPDF']);
    Route::apiResource('cuotas', CuotaController::class);

    // Deudas
    Route::get('deudacuota/{id_puesto}', [PagoController::class,'ListaDeudaCuotas']);
    Route::get('consultarImporteMultaInasistencia', [DeudaController::class, 'consultarImporteMultaInasistencia']);
    Route::post('registrarMultaInasistencia', [DeudaController::class, 'registrarMultaInasistencia']);
    Route::apiResource('deudas', DeudaController::class);

    // Pagos
    Route::get('pagos/exportar', [PagoController::class, 'export']);
    Route::get('pagos/exportar-pdf', [PagoController::class, 'exportPDF']);
    Route::apiResource('pagos', PagoController::class);

    // Detalle de pagos
    Route::apiResource('pago_detalle', PagoDetalleController::class);

    // Documentos
    Route::apiResource('documentos', DocumentoController::class);
    
    // Reporte de pagos
    Route::get('reportes/pagos', [ReporteController::class, 'pagos']);
    Route::get('reportes/pagos/exportar', [ReporteController::class, 'exportReportePagos']);
    Route::get('reportes/pagos/exportar-pdf', [ReporteController::class, 'exportReportePagosPDF']);

    // Reporte de deudas
    Route::get('reportes/deudas', [ReporteController::class, 'deudas']);
    Route::get('reportes/deudas/exportar', [ReporteController::class, 'exportReporteDeudas']);
    Route::get('reportes/deudas/exportar-pdf', [ReporteController::class, 'exportReporteDeudasPDF']);

    // Reporte de cuotas por metros
    Route::get('reportes/cuota-por-metros', [ReporteController::class, 'cuotaPorMetros']);
    Route::get('reportes/cuota-por-metros/exportar', [ReporteController::class, 'exportReporteCuotasMetrado']);
    Route::get('reportes/cuota-por-metros/exportar-pdf', [ReporteController::class, 'exportReporteCuotasMetradoPDF']);

    // Reporte de cuotas por puestos
    Route::get('reportes/cuota-por-puestos', [ReporteController::class, 'cuotaPorPuestos']);
    Route::get('reportes/cuota-por-puestos/exportar', [ReporteController::class, 'exportReporteCuotasPuesto']);
    Route::get('reportes/cuota-por-puestos/exportar-pdf', [ReporteController::class, 'exportReporteCuotasPuestoPDF']);

    // Reporte de resumen por puestos
    Route::get('reportes/resumen-por-puestos', [ReporteController::class, 'resumenPorPuestos']);
    Route::get('reportes/resumen-por-puestos/exportar', [ReporteController::class, 'exportReporteResumenPorPuesto']);
    Route::get('reportes/resumen-por-puestos/exportar-pdf', [ReporteController::class, 'exportReporteResumenPorPuestoPDF']);

    // Dashboard
    Route::get('reportes/dashboard', [ReporteController::class, 'dashboard']);

});

Route::get('/csrf-token', function () {
    return response()->json([
        'token' => csrf_token()
    ]);
});
