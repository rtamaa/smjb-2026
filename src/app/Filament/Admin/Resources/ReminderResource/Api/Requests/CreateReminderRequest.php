<?php

namespace App\Filament\Admin\Resources\ReminderResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
            'title' => 'required|string|min:3',
            'remind_at' => 'required|date',
            'type' => 'nullable|string',
            'is_sent' => 'sometimes|boolean',
            'sent_at' => 'nullable|date',
        ];
    }
}