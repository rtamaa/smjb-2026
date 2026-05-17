<?php

namespace App\Filament\Admin\Resources\TaskResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|min:3',
            'focus_minutes' => 'sometimes|integer|min:1|max:180',
            'description' => 'sometimes|nullable|string',
            'material_link' => 'sometimes|nullable|url',
            'is_completed' => 'sometimes|boolean',
            'completed_at' => 'sometimes|nullable|date',
            'sort_order' => 'sometimes|integer',
        ];
    }
}