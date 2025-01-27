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
        $paginate = Servicio::select('servicios.*')->where('servicios.activo', true);

        if (isset($request->buscar_texto)) {
            $texto = strtr(utf8_decode($request->buscar_texto), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = strtr(utf8_decode($texto), utf8_decode('àáâãäçèéêëìíîïññòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiin?ooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = str_replace(' ', '%', $texto);
            $paginate->whereRaw("upper(nombre) LIKE upper( ? )", ['%'.$texto.'%']);
        }

        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }

        return new ServicioCollection($paginate->paginate($per_page));
    }

    public function consultarImporteMultaInasistencia()
    {
        $servicio = Servicio::where('nombre', 'Multa por inasistencia')->first();

        if (!$servicio) {
            return response()->json(["data" => ["importe" => 0], "message" => "Importe de multa por inasistencia"]);
        }

        return response()->json(["data" => ["importe" => $servicio->costo_unitario], "message" => "Importe de multa por inasistencia"]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'tipo_servicio' => 'required',
            'costo_unitario' => 'required|numeric|min:0|not_in:0',
            'fecha_registro' => 'required',
        ], [
            'nombre.required' => 'El nombre del servicio es requerido.',
            'tipo_servicio.required' => 'El tipo de servicio es requerido.',
            'costo_unitario.required' => 'El costo unitario es requerido.',
            'fecha_registro.required' => 'La fecha de registro es requerida.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $servicio = new Servicio();
        $servicio->nombre = $request->input('nombre');
        $servicio->tipo_servicio = $request->input('tipo_servicio');
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

    public function update(Request $request,$id_servicio)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'tipo_servicio' => 'required',
            'costo_unitario' => 'required|numeric|min:0|not_in:0',
            'fecha_registro' => 'required',
        ], [
            'nombre.required' => 'El nombre del servicio es requerido.',
            'tipo_servicio.required' => 'El tipo de servicio es requerido.',
            'costo_unitario.required' => 'El costo unitario es requerido.',
            'costo_unitario.numeric' => 'El costo unitario debe ser numerico.',
            'costo_unitario.not_in' => 'El costo unitario debe ser mayor a 0.',
            'costo_unitario.min' => 'El costo unitario debe ser mayor a 0.',
            'fecha_registro.required' => 'La fecha de registro es requerida.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $servicio = Servicio::findOrFail($id_servicio);
        $servicio->nombre = $request->input('nombre');
        $servicio->costo_unitario = $request->input('costo_unitario');
        $servicio->tipo_servicio = $request->input('tipo_servicio');
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

        // Eliminamos el servicio
        $servicio->activo = false;
        $servicio->update();

        return response()->json(["message" => "El servicio se elimino correctamente"]);
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
}
