<?php

namespace App\Http\Controllers;

use App\Exports\PDF\SociosPDFExport;
use App\Exports\SociosExport;
use App\Filters\SociosFilter;
use App\Models\Socio;
use App\Http\Resources\SocioCollection;
use App\Http\Resources\SocioConSinPuestos;
use App\Models\Puesto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class SocioController extends Controller
{
    public function index(Request $request)
    {
        $per_page = 16;
        if (isset($request->per_page)) {
            $per_page = $request->per_page;
        }

        $listado = Socio::select('socios.*');

        if (isset($request->nombre_socio)) {
            $texto = strtr(utf8_decode($request->nombre_socio), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = strtr(utf8_decode($texto), utf8_decode('àáâãäçèéêëìíîïññòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiin?ooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
            $texto = str_replace(' ', '%', $texto);
            $listado->join("usuarios","socios.id_usuario","usuarios.id_usuario")
                    ->whereRaw("concat(upper(nombre_usuario),dni,correo,telefono) LIKE upper( ? )", ['%'.$texto.'%']);
        }

        if (isset($request->numero_puesto)) {
            $listado->join('puestos','socios.id_socio','puestos.id_socio')
                    ->whereRaw("upper(numero_puesto) LIKE upper( ? )", ['%'.$request->numero_puesto.'%']);
        }
        
        return new SocioCollection($listado->paginate($per_page));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellido_materno' => 'required',
            'apellido_paterno' => 'required',
            'correo' => 'required',
            'direccion' => 'required',
            'dni' => 'required|string|digits:8',
            'estado' => 'required',
            'fecha_registro' => 'required',
            'sexo' => 'required',
            'telefono' => 'required|string|digits:9',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio',
            'apellido_materno.required' => 'El campo apellido materno es obligatorio',
            'apellido_paterno.required' => 'El campo apellido paterno es obligatorio',
            'correo.required' => 'El campo correo es obligatorio',
            'direccion.required' => 'El campo direccion es obligatorio',
            'dni.required' => 'El campo dni es obligatorio',
            'dni.digits' => 'El campo dni debe tener 8 digitos',
            'estado.required' => 'El campo estado es obligatorio',
            'fecha_registro.required' => 'El campo fecha de registro es obligatorio',
            'sexo.required' => 'El campo sexo es obligatorio',
            'telefono.required' => 'El campo telefono es obligatorio',
            'telefono.digits' => 'El campo telefono debe tener 9 digitos'
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        // Registro de usuario
        $usuario = new Usuario();
        $usuario->nombre_usuario = $request->input('nombre').' '.$request->input('apellido_paterno').' '.$request->input('apellido_materno');
        $usuario->rol = 'Socio';

        // La contraseña por defecto es el dni encriptado
        $contrasenia = $request->input('dni');
        $usuario->contrasenia = Hash::make($contrasenia);

        $usuario->estado = $request->input('estado');
        $usuario->fecha_registro = $request->input('fecha_registro');
        $usuario->save();

        // Registro de socio
        $socio = new Socio();
        $socio->id_usuario = $usuario->id_usuario;
        $socio->nombres = $request->input('nombre');
        $socio->apellido_paterno = $request->input('apellido_paterno');
        $socio->apellido_materno = $request->input('apellido_materno');
        $socio->dni = $request->input('dni');
        $socio->correo = $request->input('correo');
        $socio->telefono = $request->input('telefono');
        $socio->direccion = $request->input('direccion');
        $socio->sexo = $request->input('sexo');
        $socio->save();

        // Se asigna el puesto al socio
        if ($request->input('id_puesto') == null) {
            return response()->json(["data"=>$socio, "message"=>"Socio registrado correctamente"]);
        }

        $puesto = Puesto::where('id_puesto', $request->input('id_puesto'))->first();
        $puesto->id_socio = $socio->id_socio;
        $puesto->estado = 2;
        $puesto->update();

        return response()->json(["data"=>$socio, "message"=>"Socio registrado correctamente"]);
    }

    public function update(Request $request, $id_socio)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellido_materno' => 'required',
            'apellido_paterno' => 'required',
            'correo' => 'required',
            'direccion' => 'required',
            'dni' => 'required|string|digits:8',
            'estado' => 'required',
            'fecha_registro' => 'required',
            'sexo' => 'required',
            'telefono' => 'required|string|digits:9',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio',
            'apellido_materno.required' => 'El campo apellido materno es obligatorio',
            'apellido_paterno.required' => 'El campo apellido paterno es obligatorio',
            'correo.required' => 'El campo correo es obligatorio',
            'direccion.required' => 'El campo direccion es obligatorio',
            'dni.required' => 'El campo dni es obligatorio',
            'dni.digits' => 'El campo dni debe tener 8 digitos',
            'estado.required' => 'El campo estado es obligatorio',
            'fecha_registro.required' => 'El campo fecha de registro es obligatorio',
            'sexo.required' => 'El campo sexo es obligatorio',
            'telefono.required' => 'El campo telefono es obligatorio',
            'telefono.digits' => 'El campo telefono debe tener 9 digitos',
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        // Actualizar datos del socio
        $socio = Socio::where('id_socio', $id_socio)->first();
        $socio->nombres = $request->input('nombre');
        $socio->apellido_paterno = $request->input('apellido_paterno');
        $socio->apellido_materno = $request->input('apellido_materno');
        $socio->correo = $request->input('correo');
        $socio->direccion = $request->input('direccion');
        $socio->dni = $request->input('dni');
        $socio->fecha_registro = $request->input('fecha_registro');
        $socio->sexo = $request->input('sexo');
        $socio->telefono = $request->input('telefono');
        $socio->update();

        // Actualizar datos de usuario
        $usuario = Usuario::where('id_usuario', $socio->id_usuario)->first();
        $usuario->nombre_usuario = $request->input('nombre').' '.$request->input('apellido_paterno').' '.$request->input('apellido_materno');
        $usuario->estado = $request->input('estado');
        $usuario->update();

        return response()->json(["data"=>$socio, "message"=>"Los datos del socio fueron actualizados correctamente"]);
    }

    public function destroy($id_socio)
    {
        // Buscamos al socio
        $socio = Socio::find($id_socio);

        // Verificamos si el socio existe
        if(!$socio){
            return response()->json(['error' => 'El socio no existe.'], 400);
        }

        // Verificamos si el socio tiene un puesto asignado y lo liberamos
        if ($socio->puesto) {
            $puesto = Puesto::where('id_puesto', $socio->puesto->id_puesto)->first();
            $puesto->id_socio = null; // Sin socio
            $puesto->estado = 1; // Disponible
            $puesto->update();
        }

        // Eliminamos al socio desactivando su cuenta
        $usuario = Usuario::where('id_usuario', $socio->id_usuario)->first();
        $usuario->estado = "0"; // Desactivado
        $usuario->update();

        return response()->json(["data"=>[],"message"=>"El socio se elimino correctamente"]);
    }

    public function consinPuestos(Request $request)
    {
        $filter = new SociosFilter();
        $queryItems = $filter->transform($request);
        if (count($queryItems) == 0) {
            return new SocioConSinPuestos(
                Socio::select('socios.*')
                    ->leftJoin('puestos','puestos.id_socio','socios.id_socio')
                    ->paginate());
        } else {
            $socios = Socio::select('socios.*')
                ->leftJoin('puestos','puestos.id_socio','socios.id_socio')
                ->where($queryItems)->paginate();
            return new SocioConSinPuestos($socios->appends($request->query()));
        }
    }

    public function export()
    {
        return Excel::download(new SociosExport(), 'socios.xlsx');
    }

    public function exportPDF()
    {
        $export = new SociosPDFExport();
        return $export->generatePDF();
    }
}
