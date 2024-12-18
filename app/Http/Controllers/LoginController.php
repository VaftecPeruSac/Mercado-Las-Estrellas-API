<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required',
            'password' => 'required',
        ], [
            'usuario.required' => 'El usuario es requerido.',
            'password.required' => 'La contraseña es requerida.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Buscamos al usuario por su nombre de usuario
        $usuario = Usuario::where('nombre_usuario', $request->input('usuario'))->first();

        // Si no existe el usuario o la contraseña no coincide
        if (!$usuario || !password_verify($request->input('password'), $usuario->contrasenia)){
            return response()->json(['message' => 'Nombre de usuario y/o contraseña incorrectos.'], 400);
        }

        $usuario = Usuario::find($usuario->id_usuario);
        $usuario->token = $this->apiToken();
        $usuario->save();

        return response()->json([
            "token" => $usuario->token,
            "message" => 'Se logueo correctamente.',
        ],200);
    }

    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required',
        ], [
            'usuario.required' => 'El usuario es requerido.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Buscamos al usuario por su nombre de usuario
        $usuario = Usuario::where('nombre_usuario', $request->input('usuario'))->first();

        // Si no existe el usuario
        if (!$usuario){
            return response()->json(['message' => 'Ocurrio un error al cerrar sesión.'], 400);
        }

        // Eliminamos el token
        $usuario = Usuario::find($usuario->id_usuario);
        $usuario->token = null;
        $usuario->save();

        return response()->json(['message' => 'Salio del sistema correctamente.'], 200);
    }

    public function validaciones(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ], [
            'token.required' => 'El token es requerido.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        // Buscamos al usuario por su token y retornamos su id, nombre y rol
        $usuario = Usuario::select("id_usuario", "nombre_usuario", "rol")
            ->where('usuarios.token',$request->input('token'))->first();

        // Si no existe el usuario
        if (!$usuario){
            return response()->json(['message' => 'No se pudo validar el acceso.'], 400);
        }

        return response()->json($usuario, 200);
    }

    private function apiToken() {
        $str_random = Str::random(60);
        $apiToken = uniqid(base64_encode($str_random));
        return $apiToken;
    }
}
