<?php

namespace App\Http\Controllers;

use App\Filters\DeudaFilter;
use App\Models\Deuda;
use App\Http\Requests\StoreDeudaRequest;
use App\Http\Requests\UpdateDeudaRequest;
use App\Http\Resources\DeudaCollection;
use Illuminate\Http\Request;

class DeudaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new DeudaFilter();
        $queryItems = $filter->transform($request);
        $deudas = Deuda::where($queryItems)->paginate();
        return new DeudaCollection($deudas->appends($request->query()));
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
    public function store(StoreDeudaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Deuda $deuda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deuda $deuda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeudaRequest $request, Deuda $deuda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deuda $deuda)
    {
        //
    }
}
