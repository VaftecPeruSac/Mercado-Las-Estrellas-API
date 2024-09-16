<?php

namespace App\Http\Controllers;

use App\Exports\CuotaExport;
use App\Models\Cuota;
use App\Models\Deuda;
use App\Models\Socio;
use App\Http\Requests\StoreCuotaRequest;
use App\Http\Requests\UpdateCuotaRequest;
use App\Http\Resources\CuotaCollection;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        foreach($request->input('servicios') as $value){
            // $servicio = Servicio::where('id_servicio', $request->input('id_servicio'))->first();
            $cuota = new Cuota();
            $cuota->importe = $request->input('importe');
            $cuota->id_servicio = $value;
            $cuota->fecha_registro = $request->input('fecha_registro');
            $cuota->fecha_vencimiento = $request->input('fecha_vencimiento');
            $cuota->save();
            // echo $cuota;
            // $servicio->cuotas()->attach($cuota);
            $listado = Socio::where('estado',1)->get();
            foreach($listado as $valu){
                // Deuda
                $deuda = new Deuda();
                $deuda->id_socio = $valu->id_socio;
                $deuda->id_cuota = $cuota->id;
                $deuda->total_deuda = $request->input('importe');
                $deuda->fecha_registro = $request->input('fecha_registro');
                $deuda->save();
            }
        }
        return "Cuota Registrado correctamente";
    }
    public function export()
    {
        return Excel::download(new CuotaExport(), 'cuotas.xlsx');
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
