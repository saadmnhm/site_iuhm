<?php

namespace App\DTO;

use Carbon\Carbon;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Str;
use App\DTO\ApiPost;

/**
 * Wraps a single deliverable returned by the Content Management API.
 */
class ApiDeliverable implements UrlRoutable
{
    public readonly int $id;
    public readonly string $slug;
    public readonly string $title;
    public readonly ?string $description;
    public readonly ?string $file_url;
    public readonly ?string $file_type;
    public readonly string $category;
    public readonly string $status;
    public readonly ?Carbon $due_date;
    public readonly bool $is_published;
    public readonly int $downloads_count;
    public readonly ?Carbon $published_at;
    public readonly ?object $author;

    public function __construct(array $data)
    {
        $this->id              = (int) ($data['id'] ?? 0);
        $this->slug            = $data['slug'] ?? Str::slug($data['title'] ?? 'deliverable');
        $this->title           = $data['title'] ?? '';
        $this->description     = $data['description'] ?? null;
        $this->file_url        = isset($data['file_url']) && filled($data['file_url'])
            ? ApiPost::resolveImageUrl($data['file_url'])
            : null;
        $this->file_type       = $data['file_type'] ?? null;
        $this->category        = $data['category'] ?? '';
        $this->status          = $data['status'] ?? 'pending';
        $this->is_published    = (bool) ($data['is_published'] ?? true);
        $this->downloads_count = (int) ($data['downloads_count'] ?? 0);

        $this->due_date = isset($data['due_date'])
            ? Carbon::parse($data['due_date'])
            : null;

        $this->published_at = isset($data['published_at'])
            ? Carbon::parse($data['published_at'])
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
