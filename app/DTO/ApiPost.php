<?php

namespace App\DTO;

use Carbon\Carbon;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Str;

/**
 * Wraps a single blog-post or news item returned by the Content Management API.
 *
 * Implements the same interface that the Blade views expect from an Eloquent
 * Post model (getLocalized, image_url, page, author, published_at, …) so the
 * existing templates continue to work without modification.
 */
class ApiPost implements UrlRoutable
{
    public readonly int $id;
    public readonly string $slug;
    public readonly bool $is_published;
    public readonly int $views_count;
    public readonly array $tags;
    public readonly ?string $image;
    public readonly ?Carbon $published_at;
    public readonly ?object $author;
    public readonly ?ApiCategory $page;

    /** @var array<string, string> */
    private array $fields = [];

    public function __construct(array $data)
    {
        $this->id          = (int) ($data['id'] ?? 0);
        $this->slug        = $data['slug'] ?? Str::slug($data['title'] ?? 'post');
        $this->is_published = (bool) ($data['is_published'] ?? true);
        $this->views_count  = (int) ($data['views_count'] ?? 0);
        $this->tags         = (array) ($data['tags'] ?? []);
        $this->image        = isset($data['image']) && filled($data['image'])
            ? self::resolveImageUrl($data['image'])
            : null;

        $this->published_at = isset($data['published_at'])
            ? Carbon::parse($data['published_at'])
            : null;

        // Build the flat fields map used by getLocalized()
        foreach (['title', 'excerpt', 'content'] as $field) {
            $this->fields[$field]         = $data[$field]       ?? '';
            $this->fields["{$field}_ar"]  = $data["{$field}_ar"] ?? '';
        }

        // Author object ──────────────────────────────────────────────
        $this->author = isset($data['author']) && is_array($data['author'])
            ? (object) $data['author']
            : null;

        // Category / page object ──────────────────────────────────────
        $this->page = isset($data['category']) && filled($data['category'])
            ? new ApiCategory($data['category'], $data['category_ar'] ?? null)
            : null;
    }

    // -------------------------------------------------------------------------
    // Localisation – mirrors App\Models\Concerns\HasLocalizedAttributes
    // -------------------------------------------------------------------------

    public function getLocalized(string $attribute, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $fallback = config('app.fallback_locale', 'en');

        if ($locale === 'ar') {
            $ar = $this->fields["{$attribute}_ar"] ?? '';
            if (filled($ar)) {
                return $ar;
            }
        }

        $val = $this->fields[$attribute] ?? $this->fields["{$attribute}_{$fallback}"] ?? '';

        return filled($val) ? $val : ($this->fields[$attribute] ?? '');
    }

    /**
     * Returns the content in the current locale (mirrors iuhm model method).
     */
    public function getTranslatedContent(): string
    {
        return $this->getLocalized('content');
    }

    /**
     * Returns the title in the current locale.
     */
    public function getTranslatedTitle(): string
    {
        return $this->getLocalized('title');
    }

    /**
     * Returns the excerpt in the current locale.
     */
    public function getTranslatedExcerpt(): string
    {
        return $this->getLocalized('excerpt');
    }

    // -------------------------------------------------------------------------
    // Image URL resolution
    // -------------------------------------------------------------------------

    /**
     * Prefix relative image paths with the platform base URL.
     * Absolute URLs (http/https) are returned unchanged.
     * Change CONTENT_API_PLATFORM_URL in .env to point to a different host.
     */
    public static function resolveImageUrl(?string $path): ?string
    {
        if (!$path) return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        return rtrim(config('api.platform_url', 'http://127.0.0.1:8000'), '/') . '/' . ltrim($path, '/');
    }

    // -------------------------------------------------------------------------
    // Magic getter – exposes virtual properties expected by Blade views
    // -------------------------------------------------------------------------

    public function __get(string $name): mixed
    {
        return match ($name) {
            'image_url'      => $this->image,
            'featured_image' => $this->image,
            'title'          => $this->fields['title'] ?? '',
            'excerpt'        => $this->fields['excerpt'] ?? '',
            'content'        => $this->fields['content'] ?? '',
            default          => null,
        };
    }

    public function __isset(string $name): bool
    {
        return in_array($name, ['image_url', 'featured_image', 'title', 'excerpt', 'content'], true);
    }

    // -------------------------------------------------------------------------
    // UrlRoutable – lets route('posts.show', $apiPost) generate the right URL
    // -------------------------------------------------------------------------

    public function getRouteKey(): string
    {
        return $this->slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolveRouteBinding($value, $field = null): static
    {
        return $this;
    }

    public function resolveChildRouteBinding($childType, $value, $field): ?static
    {
        return null;
    }
}
