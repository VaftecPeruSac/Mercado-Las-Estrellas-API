<?php

namespace App\Http\Controllers;

use App\Exports\PuestosExport;
use App\Models\Puesto;
use App\Http\Requests\UpdatePuestoRequest;
use App\Http\Resources\PuestoCollection;
use App\Filters\PuestoFilter;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        } else {
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
        $puesto->fecha_registro = $request->input('fecha_registro'); // fecha registro
        $puesto->save();
        echo 'Datos del puesto:', $puesto;
        return "Puesto Registrado correctamente";
        // new PuestoResource(Puesto::create($request->all()));
    }

    public function asignar(Request $request)
    {
        $puesto = Puesto::where('id_puesto', $request->input('id_puesto'))->first();
        $puesto->id_socio = $request->input('id_socio');
        $puesto->update();
        return "Se Asigno el puesto a un socio correctamente";
    }

    public function select()
    {
        $puestos = Puesto::all(['id_puesto','id_block', 'numero_puesto']);
        return response()->json($puestos);
    }
    public function export()
    {
        return Excel::download(new PuestosExport(), 'puestos.xlsx');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function show(Puesto $puesto)
    {
        //
    }

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
