<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>|string>
     */
    public function rules(): array
    {
        return [
            'page_id' => ['required', 'integer', 'exists:pages,id'],
            'key' => [
                'required',
                'string',
                'max:120',
                'alpha_dash',
                Rule::unique('sections', 'key')->where(fn ($query) => $query->where('page_id', $this->integer('page_id'))),
            ],
            'heading.en' => ['required', 'string', 'max:255'],
            'heading.fr' => ['required', 'string', 'max:255'],
            'heading.ar' => ['required', 'string', 'max:255'],
            'body.en' => ['nullable', 'string'],
            'body.fr' => ['nullable', 'string'],
            'body.ar' => ['nullable', 'string'],
            'cta_label.en' => ['nullable', 'string', 'max:120'],
            'cta_label.fr' => ['nullable', 'string', 'max:120'],
            'cta_label.ar' => ['nullable', 'string', 'max:120'],
            'cta_url' => ['nullable', 'url', 'max:2048'],
            'image_path' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }
}
