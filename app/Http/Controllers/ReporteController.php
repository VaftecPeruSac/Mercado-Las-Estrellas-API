<?php

namespace App\Http\Controllers;

use App\Exports\ReporteCuotasMetradoExport;
use App\Exports\ReporteCuotasPuestoExport;
use App\Exports\ReporteDeudasExport;
use App\Exports\ReportePagosExport;
use App\Exports\ReporteResumenExport;
use App\Models\Pago;
use App\Models\DetallePagos;
use App\Models\Deuda;
use App\Http\Resources\ReportePagoCollection;
use App\Http\Resources\ReporteDeudaCollection;
use App\Http\Resources\ReporteCuotaPorMetroCollection;
use App\Http\Resources\ReporteCuotaPorPuestoCollection;
use App\Http\Resources\ReporteResumenPorPuestoCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReporteController extends Controller
{
    public function pagos(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Pago::where('id_socio', $request->id_socio)
            ->paginate($per_page);

        return new ReportePagoCollection($paginate);
    }

    public function exportReportePagos(Request $request)
    {
        return Excel::download(new ReportePagosExport($request->id_socio), 'reporte_pagos.xlsx');
    }

    public function deudas(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Deuda::where('id_puesto', $request->id_puesto)
            ->paginate($per_page);

        return new ReporteDeudaCollection($paginate);
    }

    public function exportReporteDeudas(Request $request)
    {
        return Excel::download(new ReporteDeudasExport($request->id_puesto), 'reporte_deudas.xlsx');
    }

    public function cuotaPorMetros(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Deuda::where('id_cuota', $request->id_cuota)
            ->paginate($per_page);

        return new ReporteCuotaPorMetroCollection($paginate);
    }

    public function exportReporteCuotasMetrado(Request $request)
    {
        return Excel::download(new ReporteCuotasMetradoExport($request->id_cuota), 'reporte_cuotas_metrado.xlsx');
    }

    public function cuotaPorPuestos(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Deuda::where('id_puesto', $request->id_puesto)
            ->paginate($per_page);

        return new ReporteCuotaPorPuestoCollection($paginate);
    }

    public function exportReporteCuotasPuesto(Request $request)
    {
        return Excel::download(new ReporteCuotasPuestoExport($request->id_puesto), 'reporte_cuotas_puesto.xlsx');
    }

    public function dashboard(Request $request)
    {
        $cantidadSociosActivos = DB::table('socios')
            ->where('estado', '1')->count('*');
        $acumulacionPagos = DB::table('pagos')
            ->whereRaw("month(fecha_registro) = month(now())")->sum('total_pago');
        $acumulacionDeudas = DB::table('deudas')
            ->whereRaw("month(fecha_registro) = month(now())")->sum('total_deuda');

        $acumulacionPagos = number_format($acumulacionPagos, 2, '.', ',');
        $acumulacionDeudas = number_format($acumulacionDeudas, 2, '.', ',');

        $response = [
            'acumulacion_deuda' => $acumulacionDeudas,
            'acumulacion_pago' => $acumulacionPagos,
            'cantidad_socios_activos' => $cantidadSociosActivos,
        ];
        return response()->json($response);
    }

    public function resumenPorPuestos(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = DetallePagos::select('detalle_pagos.*')
            ->join('pagos','detalle_pagos.id_pago','pagos.id_pago')
            ->where('detalle_pagos.id_puesto', $request->id_puesto)
            ->paginate($per_page);

        return new ReporteResumenPorPuestoCollection($paginate);
    }

    public function exportReporteResumenPorPuesto(Request $request)
    {
        return Excel::download(new ReporteResumenExport($request->id_puesto), 'reporte_resumen_puesto.xlsx');
    }
}
