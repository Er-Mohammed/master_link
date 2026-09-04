<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = $this->user()?->id;

        return [
            'name' => [
                'sometimes',
                'string',
                'max:150',
            ],

            'email' => [
                'sometimes',
                'email',
                'max:150',
                Rule::unique('admins', 'email')
                    ->ignore($adminId),
            ],

            'profile_media_id' => [
                'nullable',
                'integer',
                Rule::exists('media', 'id')->where(function ($query) {
                    $query->where('media_type', 'image');
                }),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'الاسم يجب أن يكون نصًا.',
            'name.max' => 'الاسم لا يمكن أن يتجاوز 150 حرفًا.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل بحساب آخر.',
            'profile_media_id.exists' => 'صورة الملف الشخصي المحددة غير موجودة أو ليست صورة.',
        ];
    }
}
