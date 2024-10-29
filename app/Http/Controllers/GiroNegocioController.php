<?php

namespace App\Http\Controllers;

use App\Models\GiroNegocio;
use App\Http\Requests\UpdateGiroNegocioRequest;
use App\Http\Resources\GiroNegocioCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GiroNegocioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $giros = GiroNegocio::all();
        return new GiroNegocioCollection($giros);
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
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre del giro de negocio es requerido.',
        ]);

        if ($validator -> fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        $giroNegocio = new GiroNegocio();
        $giroNegocio->nombre = $request->input('nombre');
        $giroNegocio->save();
        // return "Giro de negocio registrado correctamente";
        return response()->json(["data"=>$giroNegocio,"message"=>"Giro de negocio registrado correctamente"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(GiroNegocio $giroNegocio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GiroNegocio $giroNegocio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGiroNegocioRequest $request, GiroNegocio $giroNegocio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GiroNegocio $giroNegocio)
    {
        //
    }
}
