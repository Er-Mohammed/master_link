<?php

namespace App\Http\Requests\Admin;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;

class SyncProjectMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'media' => [
                'present',
                'array',
            ],
            'media.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (is_numeric($value)) {
                        if (! Media::where('id', (int) $value)->exists()) {
                            $fail("The selected {$attribute} is invalid.");
                        }
                    } elseif (is_array($value)) {
                        if (! isset($value['id']) || ! Media::where('id', (int) $value['id'])->exists()) {
                            $fail("The selected {$attribute} is invalid.");
                        }
                    } else {
                        $fail("The {$attribute} must be an integer media ID or an object containing an id.");
                    }
                },
            ],
        ];
    }

    /**
     * Get normalized array format for Eloquent sync with pivot data.
     */
    public function getNormalizedMediaData(): array
    {
        $media = $this->input('media', []);
        $syncData = [];

        foreach ($media as $index => $item) {
            if (is_array($item)) {
                $id = (int) $item['id'];
                $sortOrder = isset($item['sort_order']) ? (int) $item['sort_order'] : $index;
            } else {
                $id = (int) $item;
                $sortOrder = $index;
            }

            $syncData[$id] = [
                'sort_order' => $sortOrder,
            ];
        }

        return $syncData;
    }
}
