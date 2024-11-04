<?php

namespace App\Http\Controllers;

use App\Exports\PDF\ServicioPDFExport;
use App\Exports\ServicioExport;
use App\Models\Servicio;
use App\Http\Resources\ServicioCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $paginate = Servicio::select('servicios.*');
        if (isset($request->buscar_texto)) {
            $texto = strtr(utf8_decode($request->buscar_texto), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = strtr(utf8_decode($texto), utf8_decode('àáâãäçèéêëìíîïññòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiin?ooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = str_replace(' ', '%', $texto);
            $paginate->whereRaw("upper(descripcion) LIKE upper( ? )", ['%'.$texto.'%']);
        }

        return new ServicioCollection($paginate->paginate());
    }

    public function create()
    {}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required',
            'tipo_servicio' => 'required',
            'costo_unitario' => 'required',
            'fecha_registro' => 'required',
            'estado' => 'required',
        ], [
            'descripcion.required' => 'El nombre del servicio es requerido.',
            'tipo_servicio.required' => 'El tipo de servicio es requerido.',
            'costo_unitario.required' => 'El costo unitario es requerido.',
            'fecha_registro.required' => 'La fecha de registro es requerida.',
            'estado.required' => 'El estado es requerido.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $servicio = new Servicio();
        $servicio->descripcion = $request->input('descripcion');
        $servicio->tipo_servicio = $request->input('tipo_servicio');
        $servicio->estado = $request->input('estado');
        $servicio->fecha_registro = $request->input('fecha_registro');

        // Verificamos que el tipo de servicio sea 3 y realizamos el calculo del costo unitario
        if ($request->input('tipo_servicio') == 3) {
            $puestoController = new PuestoController();
            $areaTotal = $puestoController->obtenerAreaTotal()->getData()->data;
            $costoUnitario = $request->input('costo_unitario');
            $servicio->costo_unitario = $areaTotal > 0 ? number_format($costoUnitario / $areaTotal, 2, '.', '') : 0;
        } else {
            $servicio->costo_unitario = $request->input('costo_unitario');
        }

        $servicio->save();

        return response()->json(["data"=>$servicio,"message"=>"Servicio Registrado correctamente"]);
    }

    public function export()
    {
        return Excel::download(new ServicioExport(), 'servicios.xlsx');
    }

    public function exportPDF()
    {
        $export = new ServicioPDFExport();
        return $export->generatePDF();
    }

    public function show(Servicio $servicio)
    {}

    public function edit(Servicio $servicio)
    {}

    public function update(Request $request,$id_servicio)
    {
        // $validated = $request->validate([
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required',
            'tipo_servicio' => 'required',
            'costo_unitario' => 'required',
            'fecha_registro' => 'required',
            'estado' => 'required',
        ], [
            'descripcion.required' => 'El nombre del servicio es requerido.',
            'tipo_servicio.required' => 'El tipo de servicio es requerido.',
            'costo_unitario.required' => 'El costo unitario es requerido.',
            'fecha_registro.required' => 'La fecha de registro es requerida.',
            'estado.required' => 'El estado es requerido.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $servicio = Servicio::findOrFail($id_servicio);
        $servicio->descripcion = $request->input('descripcion');
        $servicio->costo_unitario = $request->input('costo_unitario');
        $servicio->tipo_servicio = $request->input('tipo_servicio');
        $servicio->estado = $request->input('estado');
        $servicio->fecha_registro = $request->input('fecha_registro');
        $servicio->save();

        return response()->json(["data"=>$servicio,"message"=>"Los datos del servicio fueron actualizados correctamente"]);
    }

    public function destroy($id_servicio)
    {
        $servicio = Servicio::find($id_servicio);
        if(!$servicio){
            return response()->json(['error' => 'El servicio no existe.'], 400);
        }
        $servicio->delete();
        return response()->json(["data"=>[],"message"=>"El servicio se elimino correctamente"]);
    }
}
