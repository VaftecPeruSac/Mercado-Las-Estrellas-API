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
use App\Models\PuestoCuota;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class CuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paginate = Cuota::paginate();
        return new CuotaCollection($paginate);
    }

    /**
     * Store a newly created resource in storage.
     */
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

        $servicios = Servicio::whereIn('id_servicio', $request->input('servicios'))->get();

        if(count($servicios) == 0){
            return response()->json(['error' => 'No se encontraron los servicios seleccionados.'], 400);
        }

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

            foreach ($listado as $socio) {

                // Crear la deuda si no existe
                $deuda = Deuda::firstOrCreate(
                    ['id_socio' => $socio->id_socio, 'id_puesto' => $socio->id_puesto],
                    ['total_deuda' => 0]
                );

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
        }

        foreach ($listado as $socio) {
            $puestoCuota = new PuestoCuota();
            $puestoCuota->id_cuota = $cuota->id_cuota;
            $puestoCuota->id_puesto = $socio->id_puesto;
            $puestoCuota->estado = 'Pendiente';
            $puestoCuota->save();
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
