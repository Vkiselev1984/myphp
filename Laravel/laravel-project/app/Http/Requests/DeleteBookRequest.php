<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'min:1', 'exists:books,id'],
        ];
    }

    // Включаем id из параметров роутинга в данные для валидации
    public function validationData(): array
    {
        return array_merge($this->all(), [
            'id' => (int) $this->route('id'),
        ]);
    }
}
