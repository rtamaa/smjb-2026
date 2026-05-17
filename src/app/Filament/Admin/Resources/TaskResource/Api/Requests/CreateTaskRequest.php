<?php

namespace App\Filament\Admin\Resources\TaskResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|min:3',
            'focus_minutes' => 'integer|min:1|max:180',
            'user_id' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'material_link' => 'nullable|url',
            'is_completed' => 'sometimes|boolean',
            'completed_at' => 'sometimes|nullable|date',
            'sort_order' => 'sometimes|integer',
        ];
    }
}