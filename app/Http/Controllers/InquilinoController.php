<?php

namespace App\Http\Controllers;

use App\Models\Inquilino;
use App\Http\Resources\InquilinoCollection;
use App\Models\Puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InquilinoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inquilinos = Inquilino::all();
        return new InquilinoCollection($inquilinos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'dni' => 'required|string|digits:8',
            'telefono' => 'required|string|digits:9',
            'id_puesto' => 'required',
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'apellido_paterno.required' => 'El apellido paterno es requerido.',
            'apellido_materno.required' => 'El apellido materno es requerido.',
            'dni.required' => 'El DNI es requerido.',
            'dni.digits' => 'El DNI debe tener 8 dígitos.',
            'telefono.required' => 'El teléfono es requerido.',
            'telefono.digits' => 'El teléfono debe tener 9 dígitos.',
            'id_puesto.required' => 'El puesto es requerido.',
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        $inquilino = new Inquilino();
        $inquilino->nombre = $request->input('nombre');
        $inquilino->apellido_paterno = $request->input('apellido_paterno');
        $inquilino->apellido_materno = $request->input('apellido_materno');
        $inquilino->dni = $request->input('dni');
        $inquilino->telefono = $request->input('telefono');// fecha registro
        $inquilino->save();

        $puesto = Puesto::where('id_puesto', $request->input('id_puesto'))->first();
        $puesto->id_inquilino = $inquilino->id_inquilino;
        $puesto->update();

        return response()->json(["data"=>$inquilino,"message"=>"Inquilino registrado correctamente"]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_inquilino)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
            'dni' => 'required|string|digits:8',
            'telefono' => 'required|string|digits:9',
            'id_puesto' => 'required',
        ], [
            'nombre.required' => 'El nombre es requerido.',
            'apellido_paterno.required' => 'El apellido paterno es requerido.',
            'apellido_materno.required' => 'El apellido materno es requerido.',
            'dni.required' => 'El DNI es requerido.',
            'dni.digits' => 'El DNI debe tener 8 dígitos.',
            'telefono.required' => 'El teléfono es requerido.',
            'telefono.digits' => 'El teléfono debe tener 9 dígitos.',
            'id_puesto.required' => 'El puesto es requerido.',
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        $inquilino = Inquilino::findOrFail($id_inquilino);
        $inquilino->nombre = $request->input('nombre');
        $inquilino->apellido_paterno = $request->input('apellido_paterno');
        $inquilino->apellido_materno = $request->input('apellido_materno');
        $inquilino->dni = $request->input('dni');
        $inquilino->telefono = $request->input('telefono');// fecha registro
        $inquilino->save();

        $puesto = Puesto::where('id_puesto', $request->input('id_puesto'))->first();
        $puesto->id_inquilino = $inquilino->id_inquilino;
        $puesto->update();

        return response()->json(["data"=>$inquilino,"message"=>"Los datos del inquilino fueron actualizados correctamente"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_inquilino)
    {
        $inquilino = Inquilino::find($id_inquilino);

        if (!$inquilino) {
            return response()->json(["error" => "El puesto no cuenta con inquilinos"], 400);
        }

        // Eliminar inquilino del puesto
        $puesto = Puesto::where('id_inquilino', $id_inquilino)->first();
        $puesto->id_inquilino = null;
        $puesto->update();

        $inquilino->delete();

        return response()->json(["data" => $inquilino, "message" => "Inquilino eliminado correctamente"]);
    }
}
