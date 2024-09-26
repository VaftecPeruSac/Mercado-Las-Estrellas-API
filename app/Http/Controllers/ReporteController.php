<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Deuda;
use App\Http\Resources\ReportePagoCollection;
use App\Http\Resources\ReporteDeudaCollection;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function pagos(Request $request)
    {
        $paginate = Pago::where('id_socio', $request->id_socio)
            ->paginate();
        $response = new ReportePagoCollection($paginate);

        return response()->json($response);
    }

    public function deudas(Request $request)
    {
        $paginate = Deuda::where('id_puesto', $request->id_puesto)
            ->paginate();
        $response = new ReporteDeudaCollection($paginate);

        return response()->json($response);
    }
}
