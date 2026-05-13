<?php

namespace App\DTO;

use Illuminate\Support\Str;

/**
 * Lightweight category/page object returned alongside API posts.
 * Mirrors the interface the Blade views expect from a Page model.
 */
class ApiCategory
{
    public readonly string $slug;

    public function __construct(
        private readonly string $title,
        private readonly ?string $title_ar = null,
    ) {
        $this->slug = Str::slug($title);
    }

    /**
     * Return the localised category title (mirrors HasLocalizedAttributes).
     */
    public function getLocalized(string $attribute, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'ar' && filled($this->title_ar)) {
            return $this->title_ar;
        }

        return $this->title;
    }

    public function __get(string $name): mixed
    {
        return match ($name) {
            'title' => $this->title,
            default => null,
        };
    }
}
