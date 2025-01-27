<?php

namespace App\Http\Controllers;

use App\Filters\DeudaFilter;
use App\Models\Deuda;
use App\Http\Resources\DeudaCollection;
use App\Models\Cuota;
use App\Models\CuotaServicios;
use App\Models\DeudaCuota;
use App\Models\PuestoCuota;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeudaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new DeudaFilter();
        $queryItems = $filter->transform($request);
        $deudas = Deuda::where($queryItems)->paginate();
        return new DeudaCollection($deudas->appends($request->query()));
    }

    public function deudaPendientes(Request $request)
    {
        if(!isset($request->id_socio)){
            return response()->json(['error' => 'No se encontro el socio.'], 400);
        }
        if(!isset($request->id_puesto)){
            return response()->json(['error' => 'No se encontro el puesto.'], 400);
        }

        $paginate = DeudaCuota::join('deudas', 'deuda_cuotas.id_deuda', 'deudas.id_deuda')
        ->join('cuota_servicios', 'deuda_cuotas.id_cuota_servicio', 'cuota_servicios.id_cuota_servicio')
        ->join('servicios', 'cuota_servicios.id_servicio', 'servicios.id_servicio')
        ->leftJoin('detalle_pagos', 'detalle_pagos.id_deuda', DB::raw(" deuda_cuotas.id_deuda and detalle_pagos.id_servicio = cuota_servicios.id_servicio"))
        ->select(
            'deuda_cuotas.id_deuda_cuota',
            'deuda_cuotas.id_deuda',
            'servicios.nombre as nombre_servicio',
            DB::raw("max(year(deudas.fecha_registro)) as anio"),
            DB::raw("max((select nombre from setup_mes where setup_mes.id_mes = MONTH(deudas.fecha_registro))) AS mes"),
            DB::raw("max(deuda_cuotas.monto) as total"),
            DB::raw("max(deuda_cuotas.monto) - sum(coalesce(detalle_pagos.importe,0)) as por_pagar"),
            DB::raw('coalesce(sum(detalle_pagos.importe),0) as a_cuenta')
        )
        ->where('deudas.id_puesto', $request->id_puesto)
        ->groupBy('deuda_cuotas.id_deuda_cuota', 'deuda_cuotas.id_deuda', 'servicios.nombre')
        ->havingRaw("(max(deuda_cuotas.monto) - sum(coalesce(detalle_pagos.importe,0))) > 0")
        ->get();

        return response()->json(["data" => $paginate]);
    }

    public function registrarMultaInasistencia(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_socio' => 'required',
            'id_puesto' => 'required',
            'importe' => 'required|numeric|min:0|not_in:0',
        ], [
            'id_socio.required' => 'El socio es requerido.',
            'id_puesto.required' => 'El puesto es requerido.',
            'importe.required' => 'El importe es requerido.',
            'importe.not_in' => 'El importe no puede ser 0.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Verificar si existe el servicio
        $servicio = Servicio::where('nombre','Multa por inasistencia')->first();

        if (!$servicio) {
            // Crear el servicio
            $servicio = new Servicio();
            $servicio->nombre = 'Multa por inasistencia';
            $servicio->tipo_servicio = 2;
            $servicio->costo_unitario = $request->input('importe');
            $servicio->fecha_registro = date('Y-m-d');
            $servicio->save();
        } else {
            // Actualizar el costo unitario
            if ($servicio->costo_unitario != $request->input('importe')) {
                $servicio->costo_unitario = $request->input('importe');
                $servicio->save();
            }
        }

        $cuota = new Cuota();
        $cuota->fecha_emision = date('Y-m-d');
        // Establecemos la fecha de vencimiento 30 días después de la fecha de emisión
        $cuota->fecha_vencimiento = date('Y-m-d', strtotime($cuota->fecha_emision . ' + 30 days'));
        $cuota->importe = $request->input('importe');
        $cuota->global = false;
        $cuota->save();

        $puesto_cuota = new PuestoCuota();
        $puesto_cuota->id_puesto = $request->input('id_puesto');
        $puesto_cuota->id_cuota = $cuota->id_cuota;
        $puesto_cuota->estado = "Pendiente";
        $puesto_cuota->save();

        $cuota_servicio = new CuotaServicios();
        $cuota_servicio->id_cuota = $cuota->id_cuota;
        $cuota_servicio->id_servicio = $servicio->id_servicio;
        $cuota_servicio->save();

        // Buscamos la deuda del socio
        $deuda = Deuda::where('id_socio', $request->input('id_socio'))
            ->where('id_puesto', $request->input('id_puesto'))
            ->first();
        
        // Si no existe la deuda, la creamos
        if (!$deuda) {
            $deuda = new Deuda();
            $deuda->id_socio = $request->input('id_socio');
            $deuda->id_puesto = $request->input('id_puesto');
            $deuda->total_deuda = $request->input('importe');
            $deuda->save();
        } else {
            // Si existe la deuda, actualizamos el total de la deuda
            $deuda->total_deuda += $request->input('importe');
            $deuda->save();
        }

        // Registramos la deuda de la cuota
        $deuda_cuota = new DeudaCuota();
        $deuda_cuota->id_deuda = $deuda->id_deuda;
        $deuda_cuota->id_cuota_servicio = $cuota_servicio->id_cuota_servicio;
        $deuda_cuota->monto = $request->input('importe');
        $deuda_cuota->a_cuenta = 0;
        $deuda_cuota->estado = "Pendiente";
        $deuda_cuota->save();

        return response()->json(["message"=>"Multa por inasistencia registrada correctamente"]);
    }
}
