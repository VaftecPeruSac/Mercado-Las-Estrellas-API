<?php

namespace App\Http\Controllers;

use App\Exports\PuestosExport;
use App\Models\Puesto;
use App\Http\Requests\UpdatePuestoRequest;
use App\Http\Resources\PuestoCollection;
use App\Filters\PuestoFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PuestoController extends Controller
{
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

    public function create()
    {}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_gironegocio' => 'required',
            'id_block' => 'required',
            'numero_puesto' => 'required',
            'area' => 'required',
            'fecha_registro' => 'required',
        ], [
            'id_gironegocio.required' => 'El campo giro de negocio es obligatorio.',
            'id_block.required' => 'El campo block es obligatorio.',
            'numero_puesto.required' => 'El campo numero de puesto es obligatorio.',
            'area.required' => 'El campo area es obligatorio.',
            'fecha_registro.required' => 'El campo fecha de registro es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $puesto = new Puesto();
        $puesto->id_gironegocio = $request->input('id_gironegocio');
        $puesto->id_block = $request->input('id_block');
        $puesto->numero_puesto = $request->input('numero_puesto');
        $puesto->area = $request->input('area');
        $puesto->fecha_registro = $request->input('fecha_registro');
        $puesto->save();

        return response()->json(["data"=>$puesto,"message"=>"Puesto registrado correctamente"]);
    }

    public function asignar(Request $request)
    {
        $puesto = Puesto::where('id_puesto', $request->input('id_puesto'))->first();
        $puesto->id_socio = $request->input('id_socio');
        $puesto->estado = '2';
        $puesto->update();

        return response()->json(["data"=>$puesto,"message"=>"Se Asigno el puesto a un socio correctamente"]);
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

    public function show(Puesto $puesto)
    {}

    public function edit(Puesto $puesto)
    {}

    public function update(Request $request,$id_puesto)
    {
        // $validated = $request->validate([
        $validator = Validator::make($request->all(), [
            'area' => 'required|max:255',
            'fecha_registro' => 'required',
            'id_block' => 'required',
            'id_gironegocio' => 'required',
            'numero_puesto' => 'required|max:30',
        ], [
            'area.required' => 'El campo area es obligatorio.',
            'fecha_registro.required' => 'El campo fecha de registro es obligatorio.',
            'id_block.required' => 'El campo block es obligatorio.',
            'id_gironegocio.required' => 'El campo giro de negocio es obligatorio.',
            'numero_puesto.required' => 'El campo numero de puesto es obligatorio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $puesto = Puesto::findOrFail($id_puesto);
        $puesto->id_gironegocio = $request->input('id_gironegocio');
        $puesto->id_block = $request->input('id_block');
        $puesto->numero_puesto = $request->input('numero_puesto');
        $puesto->area = $request->input('area');
        $puesto->fecha_registro = $request->input('fecha_registro');
        $puesto->save();

        return response()->json(["data"=>$puesto,"message"=>"Los datos del puesto fueron actualizados correctamente"]);
    }

    public function destroy($id_puesto)
    {
        $puesto = Puesto::find($id_puesto);
        if(!$puesto){
            return response()->json(['error' => 'El puesto no existe.'], 400);
        }
        $puesto->delete();
        return response()->json(["data"=>[],"message"=>"El puesto se elimino correctamente"]);
    }

    public function indexLibre(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Puesto::select('puestos.*')
            ->whereNull('id_socio');
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
}
