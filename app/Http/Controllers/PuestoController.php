<?php

namespace App\Http\Controllers;

use App\Exports\PDF\PuestosPDFExport;
use App\Exports\PuestosExport;
use App\Models\Puesto;
use App\Http\Resources\PuestoCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PuestoController extends Controller
{
    public function index(Request $request)
    {
        $per_page = 15;

        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }

        $paginate = Puesto::select(
                'puestos.*',
                DB::raw("left(numero_puesto, 1) as npuesto_letra"),
                DB::raw("lpad(substring_index(numero_puesto, '-', -1), 2, '0') as npuesto_numero")
            )
            ->where('puestos.activo', true);

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
        $paginate->orderBy('npuesto_letra', 'asc');
        $paginate->orderBy('npuesto_numero', 'asc');

        return new PuestoCollection($paginate->paginate($per_page));
    }

    public function puestosSinSocio(Request $request)
    {
        $puestos = Puesto::select('id_puesto', 'numero_puesto')
        ->where('id_block', $request->id_block)
        ->where('estado', 1)
        ->get();

        return response()->json($puestos);
    }

    public function puestosSinInquilino(Request $request)
    {
        $puestos = Puesto::select('id_puesto', 'numero_puesto')
        ->where('id_block', $request->id_block)
        ->whereNull('id_inquilino')
        ->get();

        return response()->json($puestos);
    }

    public function seleccionarPuesto()
    {
        $puestos = Puesto::select('id_puesto', 'numero_puesto')->get();
        return response()->json($puestos);
    }

    public function obtenerTotalPuestos()
    {
        $total_puestos = Puesto::count();
        return response()->json(["data"=>$total_puestos]);
    }

    public function obtenerAreaTotal()
    {
        $area_total = Puesto::sum('area');
        return response()->json(["data"=>$area_total]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_block' => 'required',
            'numero_puesto' => 'required|unique:puestos,numero_puesto',
            'area' => 'required',
            'id_gironegocio' => 'required',
            'fecha_registro' => 'required',
        ], [
            'id_block.required' => 'No se ha seleccionado ningun bloque.',
            'numero_puesto.required' => 'El campo numero de puesto es obligatorio.',
            'numero_puesto.unique' => 'El numero de puesto ya existe.',
            'area.required' => 'El campo area es obligatorio.',
            'id_gironegocio.required' => 'No se ha seleccionado ningun giro de negocio.',
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
        $validator = Validator::make($request->all(), [
            'id_puesto' => 'required',
            'id_socio' => 'required',
        ], [
            'id_puesto.required' => 'No se ha seleccionado ningun puesto.',
            'id_socio.required' => 'No se ha seleccionado ningun socio.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $puesto = Puesto::where('id_puesto', $request->input('id_puesto'))->first();
        $puesto->id_socio = $request->input('id_socio');
        $puesto->estado = '2';
        $puesto->update();

        return response()->json(["data"=>$puesto, "message"=>"El puesto fue asignado al socio"]);
    }

    public function update(Request $request,$id_puesto)
    {
        $validator = Validator::make($request->all(), [
            'id_block' => 'required',
            'numero_puesto' => 'required|unique:puestos,numero_puesto',
            'area' => 'required',
            'id_gironegocio' => 'required',
            'fecha_registro' => 'required',
        ], [
            'id_block.required' => 'No se ha seleccionado ningun bloque.',
            'numero_puesto.required' => 'El campo numero de puesto es obligatorio.',
            'numero_puesto.unique' => 'El numero de puesto ya existe.',
            'area.required' => 'El campo area es obligatorio.',
            'id_gironegocio.required' => 'No se ha seleccionado ningun giro de negocio.',
            'fecha_registro.required' => 'El campo fecha de registro es obligatorio.',
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

        // Eliminar el puesto
        $puesto->activo = 0;
        $puesto->update();

        return response()->json(["message" => "El puesto se elimino correctamente"]);
    }

    public function export()
    {
        return Excel::download(new PuestosExport(), 'puestos.xlsx');
    }

    public function exportPDF()
    {
        $export = new PuestosPDFExport();
        return $export->generatePDF();
    }

}
