<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {

        $service = $this->route('service');


        return [

            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],


            'slug' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('services','slug')
                    ->ignore($service->id),
            ],


            'short_description' => [
                'nullable',
                'string',
            ],


            'full_description' => [
                'nullable',
                'string',
            ],


            'sort_order' => [
                'nullable',
                'integer',
            ],


            'is_active' => [
                'boolean',
            ],

        ];
    }
}