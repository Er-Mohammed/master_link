<?php

namespace App\Http\Requests\Admin;

use App\Models\Consultation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateConsultationRequest extends FormRequest
{
    /**
     * Determine whether the admin is authorized
     * to update the consultation.
     */
    public function authorize(): bool
    {
        $consultation = $this->route('consultation');

        return $consultation
            && ($this->user()?->can(
                'update',
                $consultation
            ) ?? false);
    }

    /**
     * Get the validation rules that apply
     * to the request.
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([
                    'new',
                    'contacted',
                    'in_progress',
                    'completed',
                    'cancelled',
                ]),
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'status.required' =>
                'حالة الاستشارة مطلوبة.',

            'status.in' =>
                'حالة الاستشارة غير صالحة.',
        ];
    }
}
