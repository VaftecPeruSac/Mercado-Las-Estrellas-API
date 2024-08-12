<?php

namespace App\Http\Controllers;

use App\Filters\PersonaFilter;
use App\Http\Resources\PersonaCollection;
use App\Models\Persona;
use App\Http\Requests\StorePersonaRequest;
use App\Http\Requests\UpdatePersonaRequest;
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
            // $personas = $personas->with('socio');
            $personas = Persona::with("socio")->where($queryItems)->paginate();

        }
        return new PersonaCollection($personas->appends($request->query()));

        // return response()->json($personas);
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
    public function store(StorePersonaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Persona $persona)
    {
    //     $includeSocios = request()->query('id_socio');
    //     if ($includeSocios) {
    //         return new PersonaResource($persona->loadMissing('socio'));
    //         # code...
    //     }
    //    return new PersonaResource($persona);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Persona $persona)
    {
        //
    }
}
