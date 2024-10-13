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
use App\Models\Usuario;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Middleware\APIToken;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'usuario' => 'required',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('nombre_usuario',$validated['usuario'])->first();
        if (!$usuario){
            return response()->json(['message' => 'No se pudo validar el acceso.'], 400);
        }

        if(password_verify($validated['password'],$usuario->contrasenia)){
            $usuario = Usuario::find($usuario->id_usuario);
            $usuario->token = $this->apiToken();
            $usuario->save();

            $response = [
                "token" => $usuario->token,
                'message' => 'Se logueo correctamente.',
            ];
            return response()->json($response,200);
        } else {
            return response()->json(['message' => 'No se pudo validar el acceso'], 400);
        }
    }

    public function logout(Request $request)
    {
        $usuario = Usuario::where('nombre_usuario',$request->input('usuario'))->first();
        if (!$usuario){
            return response()->json(['message' => 'No se pudo validar el acceso.'], 400);
        }

        $usuario = Usuario::find($usuario->id_usuario);
        $usuario->token = null;
        $usuario->save();

        $response = [
            'message' => 'Salio del sistema correctamente.',
        ];
        return response()->json($response,200);
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'usuario' => 'required',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('nombre_usuario',$validated['usuario'])->first();
        if ($usuario) {
            $usuario->contrasenia = Hash::make($validated['password']);
            $usuario->save();
        }
    }

    public function validaciones(Request $request)
    {
        $usuario = Usuario::select(
                "personas.nombre_completo"
            )
            ->join('personas','usuarios.id_usuario','personas.id_persona')
            ->where('usuarios.token',$request->input('token'))->first();
        if (!$usuario){
            return response()->json(['message' => 'No se pudo validar el acceso.'], 400);
        }

        return response()->json($usuario,200);
    }

    public function ventanas(Request $request)
    {
        $response = [];

        return response()->json($response,200);
    }

    private function apiToken() {
        $str_random = Str::random(60);
        $apiToken = uniqid(base64_encode($str_random));
        return $apiToken;
    }
}
