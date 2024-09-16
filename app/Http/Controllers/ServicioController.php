<?php

namespace App\Http\Controllers;

use App\Exports\ServicioExport;
use App\Models\Servicio;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use App\Http\Resources\ServicioCollection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ServicioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicio::all();
        return new ServicioCollection($servicios);
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
        $servicio = new Servicio();
        $servicio->descripcion = $request->input('descripcion');
        $servicio->costo_unitario = $request->input('costo_unitario');
        $servicio->tipo_servicio = $request->input('tipo_servicio');
        $servicio->estado = $request->input('estado');
        $servicio->fecha_registro = $request->input('fecha_registro');
        $servicio->save();
        return "Servicio Registrado correctamente";
    }
    public function export()
    {
        return Excel::download(new ServicioExport(), 'servicios.xlsx');
    }
    /**
     * Display the specified resource.
     */
    public function show(Servicio $servicio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servicio $servicio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServicioRequest $request, Servicio $servicio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servicio $servicio)
    {
        //
    }
}
