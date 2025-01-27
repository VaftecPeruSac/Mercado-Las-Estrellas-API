<?php

namespace App\Http\Controllers;

use App\Exports\CuotaExport;
use App\Exports\PDF\CuotaPDFExport;
use App\Http\Resources\CuotaCollection;
use App\Models\Cuota;
use App\Models\Deuda;
use App\Models\Socio;
use App\Models\CuotaServicios;
use App\Models\DeudaCuota;
use App\Models\Puesto;
use App\Models\PuestoCuota;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Util\Util;
use Carbon\Carbon;

class CuotaController extends Controller
{
    public function index(Request $request)
    {
        $per_page = 15;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }
        $paginate = Cuota::select("*");
        if (isset($request->anio)) {
            $paginate->whereRaw(Util::compareDateYear('fecha_emision',$request->anio));
        }
        if (isset($request->mes)) {
            $paginate->whereRaw(Util::compareDateMonth('fecha_emision',$request->mes));
        }
        return new CuotaCollection($paginate->paginate($per_page));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_emision' => 'required',
            'fecha_vencimiento' => 'required',
            'servicios' => 'required|array|min:1'
        ], [
            'fecha_emision.required' => 'La fecha de emision es requerida.',
            'fecha_vencimiento.required' => 'La fecha de vencimiento es requerida.',
            'servicios.required' => 'No se han seleccionado servicios.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $listado = Socio::select('socios.id_socio','puestos.id_puesto','puestos.area')
            ->join('usuarios','usuarios.id_usuario','socios.id_usuario')
            ->join('puestos','puestos.id_socio','socios.id_socio')
            ->where('usuarios.estado', '1')
            ->where('puestos.estado', 2)
            ->get();

        if(count($listado) == 0){
            return response()->json(['error' => 'No se encontrarón socios con puestos.'], 400);
        }

        $servicios = Servicio::whereIn('id_servicio', $request->input('servicios'))
            ->where('activo', '1')->get();

        if(count($servicios) == 0){
            return response()->json(['error' => 'No se encontraron los servicios seleccionados.'], 400);
        }

        DB::beginTransaction();

        $importe_cuota = 0;

        $cuota = new Cuota();
        $cuota->fecha_emision = $request->input('fecha_emision');
        $cuota->fecha_vencimiento = $request->input('fecha_vencimiento');
        $cuota->global = true;

        // Se calcula el importe de la cuota
        foreach($servicios as $servicio){

            $costo_servicio = 0;

            // Si el tipo de servicio es 3, se calcula el costo del servicio por el area del puesto
            if ($servicio->tipo_servicio == 3){

                foreach($listado as $socio){
                    $costo_servicio += $servicio->costo_unitario * $socio->area;
                }

            } else {
                $costo_servicio = $servicio->costo_unitario;
            }

            // Se suma el costo del servicio al importe de la cuota
            $importe_cuota += $costo_servicio;
        }

        $cuota->importe = $importe_cuota;
        $cuota->save();

        // Se registran los servicios de la cuota
        foreach ($request->input('servicios') as $value) {

            // Se obtiene el servicio
            $servicio = Servicio::find($value);

            // Crear la relación entre cuota y servicio
            $cuota_servicios = new CuotaServicios();
            $cuota_servicios->id_cuota = $cuota->id_cuota;
            $cuota_servicios->id_servicio = $value;
            $cuota_servicios->save();
        }

        // Se registran las deudas
        foreach ($listado as $socio) {

            // Crear la deuda si no existe
            $deuda = new Deuda();
            $deuda->id_socio = $socio->id_socio;
            $deuda->id_puesto = $socio->id_puesto;
            $deuda->total_deuda = 0;
            $deuda->fecha_registro = Carbon::now();
            $deuda->save();

            $cuota_servicios = CuotaServicios::where('id_cuota', $cuota->id_cuota)->get();
            foreach ($cuota_servicios as $cuota_servicio) {
                $servicio = Servicio::find($cuota_servicio->id_servicio);

                // Calcular el costo del servicio
                $costo_servicio = ($servicio->tipo_servicio == 3)
                    ? $servicio->costo_unitario * $socio->area
                    : $servicio->costo_unitario;

                // Incrementar el total de la deuda
                $deuda->increment('total_deuda', $costo_servicio);

                // Registrar la cuota de la deuda
                $deuda_cuota = new DeudaCuota();
                $deuda_cuota->id_deuda = $deuda->id_deuda;
                $deuda_cuota->id_cuota_servicio = $cuota_servicio->id_cuota_servicio;
                $deuda_cuota->monto = $costo_servicio;
                $deuda_cuota->estado = "Pendiente";
                $deuda_cuota->a_cuenta = 0;
                $deuda_cuota->save();
            }
        }

        DB::commit();

        return response()->json(["data" => $cuota , "message" => "La cuota fue registrada correctamente"]);
    }

    public function storePorPuesto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fecha_emision' => 'required',
            'fecha_vencimiento' => 'required',
            'id_puesto' => 'required',
            'servicios' => 'required|array|min:1'
        ], [
            'fecha_emision.required' => 'La fecha de emision es requerida.',
            'fecha_vencimiento.required' => 'La fecha de vencimiento es requerida.',
            'id_puesto.required' => 'El puesto es requerido.',
            'servicios.required' => 'No se han seleccionado servicios.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $puesto = Puesto::find($request->input('id_puesto'));
        if(!$puesto){
            return response()->json(['error' => 'No se encontro el puesto seleccionado.'], 400);
        }
        if($puesto->estado == '0'){
            return response()->json(['error' => 'El puesto esta desabilitado.'], 400);
        }
        if(!$puesto->id_socio){
            return response()->json(['error' => 'El puesto no esta asignado a un socio.'], 400);
        }

        $socio = Socio::find($puesto->id_socio);
        if(!$socio){
            return response()->json(['error' => 'No se encontro el socio seleccionado.'], 400);
        }

        $servicios = Servicio::whereIn('id_servicio', $request->input('servicios'))
            ->where('activo', '1')->get();
        if(count($servicios) == 0){
            return response()->json(['error' => 'No se encontraron los servicios seleccionados.'], 400);
        }

        // Crear la cuota
        $cuota = new Cuota();
        $cuota->fecha_emision = $request->input('fecha_emision');
        $cuota->fecha_vencimiento = $request->input('fecha_vencimiento');
        $cuota->global = false;
        $cuota->importe = 0;
        $cuota->save();

        // Crear la deuda
        $deuda = new Deuda();
        $deuda->id_socio = $puesto->id_socio;
        $deuda->id_puesto = $puesto->id_puesto;
        $deuda->total_deuda = 0;
        $deuda->fecha_registro = Carbon::now();
        $deuda->save();

        // Se registran los servicios de la cuota
        foreach ($servicios as $servicio) {
            // Crear la relación entre cuota y servicio
            $cuota_servicios = new CuotaServicios();
            $cuota_servicios->id_cuota = $cuota->id_cuota;
            $cuota_servicios->id_servicio = $servicio->id_servicio;
            $cuota_servicios->importe = $servicio->costo_unitario;
            $cuota_servicios->save();

            // Incrementar el total de la cuota
            $cuota->increment('importe', $servicio->costo_unitario);

            // Calcular el costo del servicio
            $costo_servicio = ($servicio->tipo_servicio == 3)
                ? $servicio->costo_unitario * $socio->area
                : $servicio->costo_unitario;

            // Incrementar el total de la deuda
            $deuda->increment('total_deuda', $costo_servicio);

            // Registrar la cuota de la deuda
            $deuda_cuota = new DeudaCuota();
            $deuda_cuota->id_deuda = $deuda->id_deuda;
            $deuda_cuota->id_cuota_servicio = $cuota_servicios->id_cuota_servicio;
            $deuda_cuota->monto = $costo_servicio;
            $deuda_cuota->estado = "Pendiente";
            $deuda_cuota->a_cuenta = 0;
            $deuda_cuota->save();
        }

        return response()->json(["data" => $cuota , "message" => "La cuota fue registrada correctamente"]);
    }

    public function export()
    {
        return Excel::download(new CuotaExport(), 'cuotas.xlsx');
    }

    public function exportPDF()
    {
        $export = new CuotaPDFExport();
        return $export->generatePDF();
    }
}
