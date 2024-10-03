<?php

namespace App\Http\Controllers;

use App\Exports\PagosExport;
use App\Models\Pago;
use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Http\Resources\PagoCollection;
use App\Models\Cuota;
use App\Models\DeudaCuota;
use App\Models\Puesto;
use App\Models\DetallePagos;
use App\Models\Deuda;
use App\Models\Documento;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pagos = Pago::all();
        return new PagoCollection($pagos);
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
    // public function store(Request $request)
    // {
    //     //el importe que se envie ya sera restado en el front
    //     $deudas_cuotas = $request->input('deudas_cuotas');

    //     // Depurar la lista (si es necesario)
    //     // print_r($deudas_cuotas); // Comenta o elimina esto en producción

    //     // Verifica que no esté vacío
    //     if (is_array($deudas_cuotas) && !empty($deudas_cuotas)) {
    //         // Verifica si la lista tiene solo un registro
    //         $is_single_record = count($deudas_cuotas) === 1;

    //         foreach ($deudas_cuotas as $deuda_cuota_data) {
    //             $estado = "Pendiente";
    //             $a_cuenta = $deuda_cuota_data['a_cuenta'];
    //             $total = $deuda_cuota_data['total'];

    //             // Si el monto a cuenta es igual al total, cambiar el estado a "Pagado"
    //             if ($a_cuenta == $total) {
    //                 $estado = "Pagado";
    //             }

    //             // Actualizar deuda_cuota
    //             $deuda_cuota = DeudaCuota::where('id_deuda_cuota', $deuda_cuota_data['id_deuda_cuota'])->first();
    //             if ($deuda_cuota) {
    //                 $deuda_cuota->a_cuenta = $a_cuenta;
    //                 $deuda_cuota->estado = $estado;
    //                 $deuda_cuota->update();

    //                 // Actualizar cuota relacionada
    //                 $cuota = Cuota::where('id_cuota', $deuda_cuota->id_cuota)->first();
    //                 if ($cuota) {
    //                     // Si es un solo registro, actualizar el importe considerando "monto_pagar"
    //                     if ($is_single_record && isset($deuda_cuota_data['monto_pagar'])) {
    //                         $cuota->importe -= $deuda_cuota_data['monto_pagar'];
    //                     } else {
    //                         $cuota->importe = $deuda_cuota_data['importe'];
    //                     }
    //                     $cuota->update();
    //                 }
    //             }
    //         }

    //         return response()->json(['message' => 'Deudas actualizadas correctamente'], 200);
    //     } else {
    //         return response()->json(['error' => 'No se recibieron deudas_cuotas válidas'], 400);
    //     }
    // }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_socio' => 'required',
            'deudas' => 'required|array|min:1',
            'deudas.*.id_deuda' => 'required',
            'deudas.*.importe' => 'required|numeric|min:0|not_in:0',
        ]);
        $documento = Documento::find(1);
        $numero_pago = Pago::max('numero_pago');
        $numero_pago_nueno = $numero_pago + 1;
        $no_validos = "";

        foreach ($validated['deudas'] as $deuda_value) {
            $deuda = Deuda::find($deuda_value['id_deuda']);
            $importe_a_cuenta = DetallePagos::where('id_deuda',$deuda_value['id_deuda'])
                ->sum("importe");
            $importe_a_cuenta = $importe_a_cuenta ? $importe_a_cuenta : 0;
            $resto_de_deuda = $deuda->total_deuda - $importe_a_cuenta;

            if(!($resto_de_deuda >= $deuda_value['importe'])){
                $no_validos .= "#".$deuda_value['id_deuda']." ".$deuda_value['importe']." ";
            }
        }
        if($no_validos != ""){
            return response()->json(['error' => 'No se recibieron deudas válidas ('.$no_validos.').'], 400);
        }

        DB::beginTransaction();
        $pago = new Pago();
        $pago->id_socio = $request->input('id_socio');
        $pago->id_documento = 1;
        $pago->numero_pago = $numero_pago_nueno;
        $pago->serie = $documento->serie;
        $pago->total_pago = 0;
        $pago->fecha_registro = Carbon::now();
        $pago->save();
        // $no_validos = "";
        foreach ($validated['deudas'] as $deuda_value) {
            $deuda = Deuda::find($deuda_value['id_deuda']);
            // $importe_a_cuenta = DetallePagos::where('id_deuda',$deuda_value['id_deuda'])
            //     ->sum("importe");
            // $importe_a_cuenta = $importe_a_cuenta ? $importe_a_cuenta : 0;
            // $resto_de_deuda = $deuda->total_deuda - $importe_a_cuenta;

            // if($resto_de_deuda >= $deuda_value['importe']){
                $detallePagos = new DetallePagos();
                $detallePagos->id_pago = $pago->id_pago;
                $detallePagos->id_cuota = $deuda->id_cuota;
                $detallePagos->id_deuda = $deuda->id_deuda;
                $detallePagos->id_puesto = $deuda->id_puesto;
                $detallePagos->importe = $deuda_value['importe'];
                $detallePagos->fecha_registro = null;
                $detallePagos->save();
            // } else {
            //     $no_validos .= "no valido #".$deuda_value['id_deuda']." ".$deuda_value['importe']." ";
            // }
        }
        $sumaImporte = DetallePagos::where('id_pago',$pago->id_pago)->sum('importe');
        $pago->total_pago = $sumaImporte;
        $pago->save();
        DB::commit();
        // DB::rollback();
        return response()->json(['message' => 'Deudas actualizadas correctamente'.'('.$no_validos.')'], 200);
    }



    public function ListaDeudaCuotas($id_puesto)
    {
        // Obtener las deudas cuotas asociadas al id_puesto
        $deuda_cuota = DeudaCuota::select('deuda_cuotas.id_deuda_cuota', 'deuda_cuotas.a_cuenta', 'cuotas.fecha_registro', 'cuotas.importe')
            ->join('cuotas', 'deuda_cuotas.id_cuota', '=', 'cuotas.id_cuota')
            ->join('puesto_cuotas', 'cuotas.id_cuota', '=', 'puesto_cuotas.id_cuota') // Tabla pivote
            ->where('puesto_cuotas.id_puesto', $id_puesto)
            ->get();

        return response()->json($deuda_cuota);
    }

    public function export()
    {
        return Excel::download(new PagosExport(), 'pagos.xlsx');
    }
    // public function reportePagos()
    // {
    //     $repotepagos = DeudaCuota::select('deuda_cuotas.id_deuda_cuota', 'deuda_cuotas.a_cuenta', 'cuotas.fecha_registro', 'cuotas.importe')
    //     ->join('cuotas', 'deuda_cuotas.id_cuota', '=', 'cuotas.id_cuota')
    //     ->join('puesto_cuotas', 'cuotas.id_cuota', '=', 'puesto_cuotas.id_cuota') // Tabla pivote
    //     ->where('puesto_cuotas.id_puesto', $id_puesto)
    //     ->get();

    // return response()->json($deuda_cuota);
    // }


    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePagoRequest $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        //
    }
}
