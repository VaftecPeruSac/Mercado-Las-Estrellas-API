<?php

namespace App\Http\Controllers;

use App\Models\Inquilino;
use App\Http\Requests\StoreInquilinoRequest;
use App\Http\Requests\UpdateInquilinoRequest;
use App\Models\Puesto;
use Illuminate\Http\Request;

class InquilinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $inquilino = new Inquilino();
        $inquilino->nombre_completo = $request->input('nombre_completo');
        $inquilino->apellido_paterno = $request->input('apellido_paterno');
        $inquilino->apellido_materno = $request->input('apellido_materno');
        $inquilino->dni = $request->input('dni');
        $inquilino->telefono = $request->input('telefono');// fecha registro
        $inquilino->save();
        $puesto = Puesto::where('id_puesto', $request->input('id_puesto'))->first();
        $puesto->id_inquilino = $inquilino->id_inquilino;
        $puesto->update();
        // echo 'Datos del inquilino:',$inquilino;
        return "Puesto Registrado correctamente";
    }

    /**
     * Display the specified resource.
     */
    public function show(Inquilino $inquilino)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inquilino $inquilino)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInquilinoRequest $request, Inquilino $inquilino)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inquilino $inquilino)
    {
        //
    }
}
