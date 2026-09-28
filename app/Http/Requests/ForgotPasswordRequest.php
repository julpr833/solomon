<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ForgotPasswordRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "email" => ['required', 'email', 'exists:users,email']
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Debes ingresar un correo electrónico válido',
            'email.email' => 'Debes ingresar un correo electrónico válido',
            'email.exists' => 'Su correo electrónico no pertenece a ninguna cuenta'
        ];
    }
}
