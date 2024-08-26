<?php

namespace App\Http\Controllers;

use App\Models\Puesto;
use App\Http\Requests\StorePuestoRequest;
use App\Http\Requests\UpdatePuestoRequest;
use App\Http\Resources\PuestoCollection;
use App\Filters\PuestoFilter;
use App\Http\Resources\PuestoResource;
use App\Models\GiroNegocio;
use App\Models\Socio;
use Illuminate\Http\Request;

class PuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $filter = new PuestoFilter();
        $queryItems = $filter->transform($request);
        // $socios = Socio::all();
        if (count($queryItems) == 0) {
            return new PuestoCollection(Puesto::paginate());
        }else{
            $socios = Puesto::where($queryItems)->paginate();
            return new PuestoCollection($socios->appends($request->query())); 
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $puesto = new Puesto();
        $puesto->id_gironegocio = $request->input('id_gironegocio');
        $puesto->id_block = $request->input('id_block');
        $puesto->numero_puesto = $request->input('numero_puesto');
        $puesto->area = $request->input('area');
        // $puesto->estado = $request->input('estado');//por defecto 1
        $puesto->fecha_registro = $request->input('fecha_registro');// fecha registro
        $puesto->save();
        echo 'Datos del puesto:',$puesto;
        // new PuestoResource(Puesto::create($request->all()));
        return "Puesto Registrado correctamente";
    }

    /**
     * Display the specified resource.
     */
    public function show(Puesto $puesto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Puesto $puesto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePuestoRequest $request, Puesto $puesto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Puesto $puesto)
    {
        //
    }
}
