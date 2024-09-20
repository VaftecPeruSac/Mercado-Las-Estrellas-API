<?php

namespace App\Http\Controllers;

use App\Exports\SociosExport;
use App\Filters\SociosFilter;
use App\Models\Socio;
use App\Http\Requests\UpdateSocioRequest;
use App\Http\Resources\SocioCollection;
use App\Http\Resources\SocioConSinPuestos;
use App\Models\Persona;
use App\Models\Puesto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;


class SocioController extends Controller
{
    public function index(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        if (isset($request->buscar_texto)) {
            $texto = strtr(utf8_decode($request->buscar_texto), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = strtr(utf8_decode($texto), utf8_decode('àáâãäçèéêëìíîïññòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiin?ooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = str_replace(' ', '%', $texto);
            $paginate = Socio::select('socios.*')
                ->join('personas','socios.id_socio','personas.id_persona')
                ->whereRaw("concat(upper(nombre_completo),dni,correo,telefono) LIKE upper( ? )", ['%'.$texto.'%'])
                ->paginate($per_page);
            return new SocioCollection($paginate);
        } else {
            return new SocioCollection(Socio::paginate($per_page));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //registro de persona
        $persona = new Persona();
        $persona->nombre = $request->input('nombre');
        $persona->apellido_paterno = $request->input('apellido_paterno');
        $persona->apellido_materno = $request->input('apellido_materno');
        $persona->nombre_completo = $request->input('nombre').' '.$request->input('apellido_paterno').' '.$request->input('apellido_materno');
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
        // $usuario->id_persona = $persona->id;
        $usuario->id_usuario = $persona->id;
        $usuario->nombre_usuario = $request->input('nombre').' '.$request->input('apellido_paterno').' '.$request->input('apellido_materno');
        $usuario->contrasenia = Str::random(10);
        $usuario->estado = $request->input('estado'); // estado
        $usuario->rol = 0; //se va considerar como null
        $usuario->fecha_registro = $request->input('fecha_registro');
        $usuario->save(); //hasta aqui se crea
        // echo 'Datos del usuario:',$usuario;
        // registro de socio
        $socio = new Socio();
        // $socio->id_usuario = $usuario->id;
        $socio->id_socio = $persona->id;
        $socio->tipo_persona = "natural"; //tipo_persona
        $socio->saldo = 0;
        $socio->fecha_registro = $request->input('fecha_registro'); // fecha registro
        $socio->save();
        //registro de socio en el puesto
        $puesto = Puesto::where('id_puesto', $request->input('id_puesto'))->first();
        $puesto->id_socio = $socio->id;
        $puesto->update();
        return "Se Registro el socio correctamente";
        // return new SocioResource(Socio::create($request->all()));
    }
    public function export()
    {
        return Excel::download(new SociosExport(), 'socios.xlsx');
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

    public function consinPuestos(Request $request)
    {
        $filter = new SociosFilter();
        $queryItems = $filter->transform($request);
        // Socio::select('socio.*')->leftJoin('','','')->paginate();
        if (count($queryItems) == 0) {
            return new SocioConSinPuestos(
                Socio::select('socios.*')
                    ->leftJoin('puestos','puestos.id_socio','socios.id_socio')
                    ->paginate());
        } else {
            $socios = Socio::select('socios.*')
                ->leftJoin('puestos','puestos.id_socio','socios.id_socio')
                ->where($queryItems)->paginate();
            return new SocioConSinPuestos($socios->appends($request->query()));
        }
    }
}
