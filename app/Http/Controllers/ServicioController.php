<?php

namespace App\Http\Controllers;

use App\Exports\ServicioExport;
use App\Models\Servicio;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use App\Http\Resources\ServicioCollection;
use Illuminate\Http\Request;
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
        $servicio = new Servicio();
        $servicio->descripcion = $request->input('descripcion');
        $servicio->costo_unitario = $request->input('costo_unitario');
        $servicio->tipo_servicio = $request->input('tipo_servicio');
        $servicio->estado = $request->input('estado');
        $servicio->fecha_registro = $request->input('fecha_registro');
        $servicio->save();

        return response()->json(["data"=>$servicio,"message"=>"Servicio Registrado correctamente"]);
    }

    public function export()
    {
        return Excel::download(new ServicioExport(), 'servicios.xlsx');
    }

    public function show(Servicio $servicio)
    {}

    public function edit(Servicio $servicio)
    {}

    public function update(Request $request,$id_servicio)
    {
        $validated = $request->validate([
            'costo_unitario' => 'required',
            'descripcion' => 'required|max:255',
            'estado' => 'required',
            'fecha_registro' => 'required',
            'tipo_servicio' => 'required',
        ]);

        $servicio = Servicio::findOrFail($id_servicio);
        $servicio->descripcion = $validated['descripcion'];
        $servicio->costo_unitario = $validated['costo_unitario'];
        $servicio->tipo_servicio = $validated['tipo_servicio'];
        $servicio->estado = $validated['estado'];
        $servicio->fecha_registro = $validated['fecha_registro'];
        $servicio->save();

        return response()->json(["data"=>$servicio,"message"=>"Servicio Editado correctamente"]);
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
