<?php

namespace App\Http\Controllers;

use App\Models\GiroNegocio;
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
        $giros = GiroNegocio::all();
        return new GiroNegocioCollection($giros);
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

        // Registramos el giro de negocio
        $giroNegocio = new GiroNegocio();
        $giroNegocio->nombre = $request->input('nombre');
        $giroNegocio->save();

        return response()->json(["data"=>$giroNegocio, "message"=>"Giro de negocio registrado correctamente"]);
    }
}
