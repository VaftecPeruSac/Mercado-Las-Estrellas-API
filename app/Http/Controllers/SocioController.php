<?php

namespace App\Http\Controllers;

use App\Filters\SociosFilter;
use App\Models\Socio;
use App\Http\Requests\StoreSocioRequest;
use App\Http\Requests\UpdateSocioRequest;
use App\Http\Resources\SocioCollection;
use App\Http\Resources\SocioResource;
use App\Models\Block;
use App\Models\Persona;
use App\Models\Puesto;
use App\Models\Usuario;
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
            return new SocioCollection(Socio::paginate());//se sa el modelo Puesto porque lista los registros uno por uno
        } else {
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
    public function store(Request $request)
    {
        
        //registro de persona
        $persona = new Persona();
        $persona->nombre = $request->input('nombre');
        $persona->apellido_paterno = $request->input('apellido_paterno');
        $persona->apellido_materno = $request->input('apellido_materno');
        $persona->dni = $request->input('dni');
        $persona->correo = $request->input('correo');
        $persona->telefono = $request->input('telefono');
        $persona->direccion = $request->input('direccion');
        $persona->sexo = $request->input('sexo');
        $persona->estado = $request->input('estado');
        $persona->fecha_registro = $request->input('fecha_registro');
        $persona->save();
        // registro de usuario
        $usuario = new Usuario();
        $usuario->id_persona = $persona->id;
        $usuario->nombre_usuario = $request->input('nombre');
        $usuario->contrasenia = $request->input('contrasenia');
        $usuario->estado = $request->input('estado');// estado
        $usuario->rol = 0;//se va considerar como null
        $usuario->fecha_registro = $request->input('fecha_registro');
        $usuario->save();//hasta aqui se crea
        // echo 'Datos del usuario:',$usuario;
         // registro de socio
         $socio = new Socio();
         $socio->id_usuario = $usuario->id;
         $socio->tipo_persona = $request->input('tipo_persona');//tipo_persona
         $socio->saldo = $request->input('saldo');
         $socio->fecha_registro = $request->input('fecha_registro');// fecha registro
         $socio->save();
         //registro de socio en el puesto
         $puesto = Puesto::where('id_puesto', $request->input('id_puesto'))->first();
         $puesto->id_socio = $socio->id;
         $puesto->update();
        return "Se Registro el socio correctamente";
        // return new SocioResource(Socio::create($request->all()));
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
