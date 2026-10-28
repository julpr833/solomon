<?php

namespace App\Http\Requests\Habits;

use Illuminate\Foundation\Http\FormRequest;

class DeleteHabitRequest extends FormRequest
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
            'habito_id' => ['required', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $routeHabit = $this->route('habito');
        $id = $this->input('habito_id', $this->input('habit_id', $this->input('id', $routeHabit)));

        if (is_object($id)) {
            $id = $id->getKey();
        }

        $this->merge(['habito_id' => $id]);
    }
}
