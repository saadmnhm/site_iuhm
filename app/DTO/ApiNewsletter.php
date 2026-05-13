<?php

namespace App\DTO;

use Carbon\Carbon;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Str;
use App\DTO\ApiPost;

/**
 * Wraps a single newsletter edition returned by the Content Management API.
 */
class ApiNewsletter implements UrlRoutable
{
    public readonly int $id;
    public readonly string $slug;
    public readonly string $title;
    public readonly ?string $content;
    public readonly ?string $featured_image;
    public readonly ?int $issue_number;
    public readonly bool $is_published;
    public readonly ?Carbon $published_at;
    public readonly ?Carbon $sent_at;
    public readonly int $recipients_count;
    public readonly ?object $author;

    public function __construct(array $data)
    {
        $this->id               = (int) ($data['id'] ?? 0);
        $this->slug             = $data['slug'] ?? Str::slug($data['title'] ?? 'newsletter');
        $this->title            = $data['title'] ?? '';
        $this->content          = $data['content'] ?? null;
        $this->featured_image   = isset($data['featured_image']) && filled($data['featured_image'])
            ? ApiPost::resolveImageUrl($data['featured_image'])
            : null;
        $this->issue_number     = isset($data['issue_number']) ? (int) $data['issue_number'] : null;
        $this->is_published     = (bool) ($data['is_published'] ?? true);
        $this->recipients_count = (int) ($data['recipients_count'] ?? 0);

        $this->published_at = isset($data['published_at'])
            ? Carbon::parse($data['published_at'])
            : null;

        $this->sent_at = isset($data['sent_at'])
            ? Carbon::parse($data['sent_at'])
            : null;

        $this->author = isset($data['author']) && is_array($data['author'])
            ? (object) $data['author']
            : null;
    }

    // UrlRoutable ──────────────────────────────────────────────────────────────

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
