<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Http\Resources\PagoCollection;
use App\Models\Cuota;
use App\Models\DeudaCuota;
use App\Models\Puesto;
use Illuminate\Http\Client\Request;

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
        //debe recibir una lista de deudas_cuotas para cambiar el estado y monto de las deudas_cuotas
        $estado  ="Pendiente";
        $a_cuenta  =$request->input('a_cuenta');
        $total  =$request->input('total');
        if ( $a_cuenta === $total) {
            $estado ="Pagado";
        }

        //actualizar deuda_cuota
        $deuda_cuota = DeudaCuota::where('id_deuda_cuota', $request->input('id_deuda_cuota'))->first();
        $deuda_cuota->a_cuenta = $request->input('a_cuenta');
        $deuda_cuota->estado = $estado;
        $deuda_cuota->update();
        //actualizar cuota
        $cuota = Cuota::where('id_deuda_cuota', $deuda_cuota->id_cuota)->first();
        $cuota->importe = $request->input('importe');
        $cuota->update();


        //registrar Pago
        // $pago = new Pago();
        // $pago->id_socio = $request->input('id_socio');
        // $pago->id_documento = $request->input('id_documento');
        // $pago->numero_pago = $request->input('numero_pago');
        // $pago->serie = $request->input('serie');
        // $pago->total_pago = $request->input('total_pago');
        // $pago->fecha_registro = $request->input('fecha_registro');
        // $pago->save();

    }
    public function ListaDeudaCuotas()
    {
        $deuda_cuota = DeudaCuota::select('deuda_cuotas.id_deuda_cuota', 'deuda_cuotas.a_cuenta', 'cuotas.fecha_registro', 'cuotas.importe')
        ->join('cuotas', 'deuda_cuotas.id_cuota', '=', 'cuotas.id_cuota')
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
