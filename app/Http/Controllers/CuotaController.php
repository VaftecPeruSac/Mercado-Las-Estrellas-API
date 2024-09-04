<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Http\Requests\StoreCuotaRequest;
use App\Http\Requests\UpdateCuotaRequest;
use App\Http\Resources\CuotaCollection;
use App\Models\Servicio;
use Illuminate\Http\Request;

class CuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cuotas = Cuota::all();
        return new CuotaCollection($cuotas);
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
    public function store(Request $request)
    {
        
        $servicio = Servicio::where('id_servicio', $request->input('id_servicio'))->first();
        $cuota = new Cuota();
        $cuota->importe = $request->input('importe');
        $cuota->fecha_registro = $request->input('fecha_registro');
        $cuota->fecha_vencimiento = $request->input('fecha_vencimiento');
        $cuota->save();
        echo $cuota;
        $servicio->cuotas()->attach($cuota);
        return "Cuota Registrado correctamente";
    }
    
    public function listardeudacuota(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Cuota $cuota)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cuota $cuota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCuotaRequest $request, Cuota $cuota)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cuota $cuota)
    {
        //
    }
}
