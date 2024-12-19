<?php

namespace App\Http\Controllers;

use App\Filters\DeudaFilter;
use App\Models\Deuda;
use App\Http\Requests\UpdateDeudaRequest;
use App\Http\Resources\DeudaCollection;
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

        $paginate = Deuda::leftJoin('detalle_pagos','deudas.id_deuda','detalle_pagos.id_deuda')
        ->leftJoin('servicios', 'deudas.id_servicio', 'servicios.id_servicio')
        ->select(
            'deudas.id_deuda',
            'deudas.total_deuda as total',
            'servicios.nombre as servicio_nombre',
            DB::raw("max(year(deudas.fecha_registro)) as anio"),
            DB::raw("max(CASE WHEN MONTH(deudas.fecha_registro) = 1 THEN 'Enero'
                WHEN MONTH(deudas.fecha_registro) = 2 THEN 'Febrero'
                WHEN MONTH(deudas.fecha_registro) = 3 THEN 'Marzo'
                WHEN MONTH(deudas.fecha_registro) = 4 THEN 'Abril'
                WHEN MONTH(deudas.fecha_registro) = 5 THEN 'Mayo'
                WHEN MONTH(deudas.fecha_registro) = 6 THEN 'Junio'
                WHEN MONTH(deudas.fecha_registro) = 7 THEN 'Julio'
                WHEN MONTH(deudas.fecha_registro) = 8 THEN 'Agosto'
                WHEN MONTH(deudas.fecha_registro) = 9 THEN 'Septiembre'
                WHEN MONTH(deudas.fecha_registro) = 10 THEN 'Octubre'
                WHEN MONTH(deudas.fecha_registro) = 11 THEN 'Noviembre'
                WHEN MONTH(deudas.fecha_registro) = 12 THEN 'Diciembre'
                ELSE '-' END) AS mes")
        )
        ->selectRaw('coalesce(sum(detalle_pagos.importe),0) as a_cuenta, (deudas.total_deuda - coalesce(sum(detalle_pagos.importe),0)) as deuda')
        ->where('deudas.id_socio', $request->id_socio)
        ->where('deudas.id_puesto', $request->id_puesto)
        ->groupBy('deudas.id_deuda', 'deudas.total_deuda', 'servicios.nombre')
        ->havingRaw("deudas.total_deuda - coalesce(sum(detalle_pagos.importe),0) > 0")
        ->paginate();
        return $paginate;
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
        $servicio = Servicio::where('descripcion','Multa por inasistencia')->first();

        if (!$servicio) {
            // Crear el servicio
            $servicio = new Servicio();
            $servicio->descripcion = 'Multa por inasistencia';
            $servicio->tipo_servicio = 2;
            $servicio->costo_unitario = $request->input('importe');
            $servicio->estado = 1;
            $servicio->fecha_registro = date('Y-m-d');
            $servicio->save();
        } else {
            // Actualizar el costo unitario
            if ($servicio->costo_unitario != $request->input('importe')) {
                $servicio->costo_unitario = $request->input('importe');
                $servicio->save();
            }
        }

        $deuda = new Deuda();
        $deuda->id_socio = $request->input('id_socio');
        $deuda->id_puesto = $request->input('id_puesto');
        $deuda->id_servicio = $servicio->id_servicio;
        $deuda->fecha_registro = date('Y-m-d');
        $deuda->total_deuda = $servicio->costo_unitario;
        $deuda->save();

        return response()->json(["data"=>[],"message"=>"Multa por inasistencia registrada correctamente"]);
    }

    public function consultarImporteMultaInasistencia()
    {
        $servicio = Servicio::where('descripcion','Multa por inasistencia')->first();

        if (!$servicio) {
            return response()->json(["data"=>["importe"=>0],"message"=>"Importe de multa por inasistencia"]);
        }

        return response()->json(["data"=>["importe"=>$servicio->costo_unitario],"message"=>"Importe de multa por inasistencia"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Deuda $deuda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deuda $deuda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeudaRequest $request, Deuda $deuda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deuda $deuda)
    {
        //
    }
}
