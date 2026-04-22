<?php

namespace App\Models\Concerns;

trait HasLocalizedAttributes
{
    /**
     * Resolve a localized value from a JSON-backed attribute.
     */
    public function getLocalized(string $attribute, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $fallbackLocale = config('app.fallback_locale');

        $value = $this->getAttribute($attribute);

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($value)) {
            return '';
        }

        $localized = $value[$locale] ?? $value[$fallbackLocale] ?? null;

        if (is_string($localized) && filled($localized)) {
            return $localized;
        }

        foreach ($value as $translation) {
            if (is_string($translation) && filled($translation)) {
                return $translation;
            }
        }

        return '';
    }
}
