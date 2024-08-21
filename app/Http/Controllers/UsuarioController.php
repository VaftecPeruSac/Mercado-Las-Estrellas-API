<?php

namespace App\Http\Controllers;

use App\Filters\UsuarioFilter;
use App\Models\Usuario;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use App\Http\Resources\UsuarioCollection;
use App\Http\Resources\UsuarioResource;
use Illuminate\Http\Request;
class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new UsuarioFilter();
        $queryItems = $filter->transform($request);
        // $includeSocios = $request->query("socio");
        $usuarios = Usuario::where($queryItems)->paginate();
        // if ($includeSocios){
        //     $usuarios = Usuario::with("socio")->where($queryItems)->paginate();
        // }
        return new UsuarioCollection($usuarios->appends($request->query()));
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
    public function store(StoreUsuarioRequest $request)
    { // authorize=true en StoreUsuarioRequest
        new UsuarioResource(Usuario::create($request->all()));
        return "Usuario Registrado correctamente";
    }

    /**
     * Display the specified resource.
     */
    public function show(Usuario $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Usuario $usuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioRequest $request, Usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Usuario $usuario)
    {
        //
    }
}
