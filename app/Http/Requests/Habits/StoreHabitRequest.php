<?php

namespace App\Http\Requests\Habits;

use App\Models\Habito;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHabitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'objetivo' => ['required', 'string', 'max:45'],
            'descripcion' => ['nullable', 'string', 'max:300'],
            'frecuencia' => ['required', Rule::in(Habito::FREQUENCIES)],
            'prioridad' => ['required', Rule::in(Habito::PRIORITIES)],
            'hora_recordatorio' => ['nullable', 'date_format:H:i'],
        ];
    }
}
