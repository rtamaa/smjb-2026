<?php

namespace App\Filament\Admin\Resources\ReminderResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReminderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'is_sent' => 'sometimes|boolean',
            'sent_at' => 'nullable|date',
            // Field lain tidak wajib di-update
            'user_id' => 'sometimes|exists:users,id',
            'task_id' => 'sometimes|exists:tasks,id',
            'title' => 'sometimes|string|min:3',
            'remind_at' => 'sometimes|date',
            'type' => 'nullable|string',
        ];
    }
}