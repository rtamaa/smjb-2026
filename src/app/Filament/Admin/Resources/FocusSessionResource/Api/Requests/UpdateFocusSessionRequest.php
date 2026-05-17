<?php

namespace App\Filament\Admin\Resources\FocusSessionResource\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFocusSessionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ended_at' => 'nullable|date',
            'duration_actual' => 'nullable|integer',
            'is_completed' => 'sometimes|boolean',
            'is_cancelled' => 'sometimes|boolean',
        ];
    }
}
