<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:M,F'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'telefono' => ['nullable', 'string', 'max:15'],
            'password' => [
                'required',
                'confirmed', // Exige que exista el campo 'password_confirmation'
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Name
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',

            // Gender
            'gender.required' => 'El género es obligatorio.',
            'gender.in' => 'El género seleccionado no es válido.',

            // Email
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresá un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',

            // Telefono
            'telefono.string' => 'El teléfono debe ser una cadena de texto.',
            'telefono.max' => 'El teléfono no puede superar los 20 caracteres.',

            // Password
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.letters' => 'La contraseña debe contener al menos una letra.',
            'password.mixed' => 'La contraseña debe incluir letras mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe contener al menos un número.',
            'password.symbols' => 'La contraseña debe contener al menos un carácter especial.',
            'password.uncompromised' => 'Esta contraseña ha sido expuesta en una filtración de datos. Por favor, elegí una diferente.',
        ];
    }
}
