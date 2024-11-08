<?php

namespace App\Http\Controllers;

use App\Filters\DeudaFilter;
use App\Models\Deuda;
use App\Http\Requests\StoreDeudaRequest;
use App\Http\Requests\UpdateDeudaRequest;
use App\Http\Resources\DeudaCollection;
use App\Models\Servicio;
use Illuminate\Http\Request;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeudaRequest $request)
    {
        //
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
