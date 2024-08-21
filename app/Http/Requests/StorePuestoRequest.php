<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePuestoRequest extends FormRequest
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
            'id_socio' => ['required'],
            'id_gironegocio' => ['required'],
            'id_block' => ['required'],
            'area' => ['required'],
            'id_inquilino' => ['required'],
            'estado' => ['required'],
            'fecha_registro' => ['required'],
        ];
    }
}
