<?php

namespace App\Http\Requests\ShortUrls;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreShortUrlRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isAdmin() || $this->user()->isMember());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'original_url' => [
                'required',
                'url',
                'max:2048',
                'regex:/^https?:\/\//i',
            ],
        ];
    }
}