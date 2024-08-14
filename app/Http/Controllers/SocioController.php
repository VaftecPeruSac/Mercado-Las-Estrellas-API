<?php

namespace App\Http\Controllers;

use App\Filters\SociosFilter;
use App\Models\Socio;
use App\Http\Requests\StoreSocioRequest;
use App\Http\Requests\UpdateSocioRequest;
use App\Http\Resources\SocioCollection;
use App\Http\Resources\SocioResource;
use Illuminate\Http\Request;

class SocioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //opcion 1
        
        $filter = new SociosFilter();
        $queryItems = $filter->transform($request);
        if (count($queryItems) == 0) {
            return new SocioCollection(Socio::paginate());

        }else{
            $socios = Socio::where($queryItems)->paginate();
            return new SocioCollection($socios->appends($request->query())); 
        }
        //opcion 2
        // $filter = new SociosFilter();
        // $queryItems = $filter->transform($request);
        // $includepuestos = $request->query("puesto");
        // $socios = Socio::where($queryItems)->paginate();
        // if ($includepuestos){
        //     $socios = Socio::with("puesto")->where($queryItems)->paginate();
        // }
        // return new SocioCollection($socios->appends($request->query()));
        
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
    public function store(StoreSocioRequest $request)
    {
       
        return new SocioResource(Socio::create($request->all()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Socio $socio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Socio $socio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocioRequest $request, Socio $socio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Socio $socio)
    {
        //
    }
}
