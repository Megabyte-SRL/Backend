<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarAmbientesArchivoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'file.required' => 'Se requiere un archivo para subir.',
            'file.file' => 'El elemento subido debe ser un archivo.',
            'file.mimes' => 'Solamente archivos CSV son permitidos.',
            'file.max' => 'El tamaño máximo del archivo son 2MB.',
        ];
    }
}
