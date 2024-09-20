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
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }

        $paginate = Puesto::select('puestos.*');
        if (isset($request->id_gironegocio)) {
            $paginate->where('id_gironegocio',$request->id_gironegocio);
        }
        if (isset($request->id_block)) {
            $paginate->where('id_block',$request->id_block);
        }
        if (isset($request->id_socio)) {
            $paginate->where('id_socio',$request->id_socio);
        }
        if (isset($request->numero_puesto)) {
            $paginate->whereRaw("upper(numero_puesto) LIKE upper( ? )", ['%'.$request->numero_puesto.'%']);
        }
        if (isset($request->buscar_texto)) {
            $texto = strtr(utf8_decode($request->buscar_texto), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = strtr(utf8_decode($texto), utf8_decode('àáâãäçèéêëìíîïññòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiin?ooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = str_replace(' ', '%', $texto);
            $paginate->whereRaw("upper(numero_puesto) LIKE upper( ? )", ['%'.$texto.'%']);
        }

        return new PuestoCollection($paginate->paginate($per_page));
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
    public function update(Request $request,$id_puesto)
    {
        $validated = $request->validate([
            'area' => 'required|max:255',
            'fecha_registro' => 'required',
            'id_block' => 'required',
            'id_gironegocio' => 'required',
            'numero_puesto' => 'required|max:30',
        ]);

        $puesto = Puesto::findOrFail($id_puesto);
        $puesto->id_gironegocio = $validated['id_gironegocio'];
        $puesto->id_block = $validated['id_block'];
        $puesto->numero_puesto = $validated['numero_puesto'];
        $puesto->area = $validated['area'];
        $puesto->fecha_registro = $validated['fecha_registro'];
        $puesto->save();

        return "Puesto Editado correctamente";
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Puesto $puesto)
    {
        //
    }
}
