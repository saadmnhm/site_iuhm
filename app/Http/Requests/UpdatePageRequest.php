<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePageRequest extends FormRequest
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
        $pageId = $this->route('page')?->id;

        return [
            'slug' => ['required', 'string', 'max:120', 'alpha_dash', Rule::unique('pages', 'slug')->ignore($pageId)],
            'title.en' => ['required', 'string', 'max:255'],
            'title.fr' => ['required', 'string', 'max:255'],
            'title.ar' => ['required', 'string', 'max:255'],
            'excerpt.en' => ['nullable', 'string', 'max:1000'],
            'excerpt.fr' => ['nullable', 'string', 'max:1000'],
            'excerpt.ar' => ['nullable', 'string', 'max:1000'],
            'hero_image' => ['nullable', 'url', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ]);
    }
}
