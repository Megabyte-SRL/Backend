<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarUsuarioRequest extends FormRequest
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
        $user = $this->user(); // Get the authenticated user

        // Define the validation rules based on the user's role
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirmPassword' => 'required|same:password',
        ];

        if ($user->rol === 'docente') {
            $rules['nombre'] = 'required|string|min:3|max:50';
            $rules['apellido'] = 'required|string|min:3|max:50';
        }

        return $rules;
    }
}
