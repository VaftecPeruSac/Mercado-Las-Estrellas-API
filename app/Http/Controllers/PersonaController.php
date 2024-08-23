<?php

namespace App\Http\Controllers;

use App\Filters\PersonaFilter;
use App\Http\Resources\PersonaCollection;
use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
use App\Http\Resources\PersonaResource;
use Illuminate\Http\Request;

class PersonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new PersonaFilter();
        $queryItems = $filter->transform($request);
        $includeSocios = $request->query("socio");
        $personas = Persona::where($queryItems)->paginate();
        if ($includeSocios){
            $personas = Persona::with("socio")->where($queryItems)->paginate();
        }
        return new PersonaCollection($personas->appends($request->query()));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $persona = new Persona();
        $persona->nombre = $request->input('nombre');
        $persona->apellido_paterno = $request->input('apellido_paterno');
        $persona->apellido_materno = $request->input('apellido_paterno');
        $persona->dni = $request->input('dni');
        $persona->correo = $request->input('correo');
        $persona->telefono = $request->input('telefono');
        $persona->direccion = $request->input('direccion');
        $persona->sexo = $request->input('sexo');
        $persona->estado = $request->input('estado');
        $persona->fecha_registro = $request->input('fecha_registro');
        $persona->save();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonaRequest $request)
    {
        new PersonaResource(Persona::create($request->all()));
        return "Persona Registrada correctamente";
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
        $includeSocios = request()->query('socio');
        if ($includeSocios) {
            return new PersonaResource($persona->loadMissing('socio'));
            # code...
        }
       return new PersonaResource($persona);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Persona $persona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonaRequest $request, Persona $persona)
    {
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        //
    }
}
