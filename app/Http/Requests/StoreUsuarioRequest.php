<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_persona' => ['required'],
            'nombre_usuario' => ['required'],
            'contrasenia' => ['required'],
            'rol' => ['required'],
            'estado' => ['required'],
            'fecha_registro' => ['required'],
        ];
    }
    protected function prepareForValidation(){
        $this->merge([
            'telefono'  => $this->telefono
        ]);
    }
}
