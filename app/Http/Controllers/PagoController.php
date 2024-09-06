<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Http\Resources\PagoCollection;
use App\Models\Cuota;
use App\Models\DeudaCuota;
use App\Models\Puesto;
use Illuminate\Http\Request;

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
public function store(Request $request)
{
    //el importe que se envie ya sera restado en el front
    $deudas_cuotas = $request->input('deudas_cuotas');

    // Depurar la lista (si es necesario)
    // print_r($deudas_cuotas); // Comenta o elimina esto en producción

    // Verifica que no esté vacío
    if (is_array($deudas_cuotas) && !empty($deudas_cuotas)) {
        // Verifica si la lista tiene solo un registro
        $is_single_record = count($deudas_cuotas) === 1;

        foreach ($deudas_cuotas as $deuda_cuota_data) {
            $estado = "Pendiente";
            $a_cuenta = $deuda_cuota_data['a_cuenta'];
            $total = $deuda_cuota_data['total'];

            // Si el monto a cuenta es igual al total, cambiar el estado a "Pagado"
            if ($a_cuenta == $total) {
                $estado = "Pagado";
            }

            // Actualizar deuda_cuota
            $deuda_cuota = DeudaCuota::where('id_deuda_cuota', $deuda_cuota_data['id_deuda_cuota'])->first();
            if ($deuda_cuota) {
                $deuda_cuota->a_cuenta = $a_cuenta;
                $deuda_cuota->estado = $estado;
                $deuda_cuota->update();

                // Actualizar cuota relacionada
                $cuota = Cuota::where('id_cuota', $deuda_cuota->id_cuota)->first();
                if ($cuota) {
                    // Si es un solo registro, actualizar el importe considerando "monto_pagar"
                    if ($is_single_record && isset($deuda_cuota_data['monto_pagar'])) {
                        $cuota->importe -= $deuda_cuota_data['monto_pagar'];
                    } else {
                        $cuota->importe = $deuda_cuota_data['importe'];
                    }
                    $cuota->update();
                }
            }
        }

        return response()->json(['message' => 'Deudas actualizadas correctamente'], 200);
    } else {
        return response()->json(['error' => 'No se recibieron deudas_cuotas válidas'], 400);
    }
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
