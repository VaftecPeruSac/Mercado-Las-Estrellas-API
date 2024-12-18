<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Http\Resources\BlockCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blocks = Block::all();
        return new BlockCollection($blocks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
        ], [
            'nombre.required' => 'El nombre del bloque es requerido.',
        ]);

        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()->first()], 400);
        }

        // Crear un nuevo bloque
        $block = new Block();
        $block->nombre = $request->input('nombre');
        $block->save();

        return response()->json(["data"=>$block, "message"=>"Bloque Registrado correctamente"]);
    }
}
