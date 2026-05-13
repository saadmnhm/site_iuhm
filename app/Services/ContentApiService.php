<?php

namespace App\Services;

use App\DTO\ApiDeliverable;
use App\DTO\ApiNewsletter;
use App\DTO\ApiPost;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContentApiService
{
    private string $baseUrl;
    private int $timeout;
    private ?string $token;
    private int $cacheTtl;

    public function __construct()
    {
        $this->baseUrl  = rtrim((string) config('api.base_url', 'http://127.0.0.1:8000/api/v1'), '/');
        $this->timeout  = (int) config('api.timeout', 10);
        $this->token    = config('api.token') ?: null;
        $this->cacheTtl = (int) config('api.cache_ttl', 300); // 5 min default
    }

    // -------------------------------------------------------------------------
    // Health / Connectivity
    // -------------------------------------------------------------------------

    /**
     * Ping the ERP API and return a status array.
     * Never throws â€“ returns ['ok' => false] on any failure.
     *
     * @return array{ok: bool, latency_ms: int, status: int|null, data: array}
     */
    public function ping(): array
    {
        $url   = $this->baseUrl . '/health';
        $start = microtime(true);

        try {
            $response = $this->buildClient()
                ->timeout(5)
                ->get($url);

            $latency = (int) round((microtime(true) - $start) * 1000);

            return [
                'ok'         => $response->successful(),
                'latency_ms' => $latency,
                'status'     => $response->status(),
                'data'       => (array) ($response->json() ?? []),
            ];
        } catch (\Throwable $e) {
            Log::warning("ContentApiService: ping failed â€“ {$e->getMessage()}");

            return [
                'ok'         => false,
                'latency_ms' => (int) round((microtime(true) - $start) * 1000),
                'status'     => null,
                'data'       => ['error' => $e->getMessage()],
            ];
        }
    }

    // -------------------------------------------------------------------------
    // Blog Posts
    // -------------------------------------------------------------------------

    /** @return LengthAwarePaginator<ApiPost> */
    public function getBlogPosts(int $perPage = 9, int $page = 1, ?string $search = null, ?string $category = null): LengthAwarePaginator
    {
        $params   = $this->filterParams(compact('perPage', 'page', 'search', 'category'));
        $response = $this->cachedGet('/blog', $params);

        $items = collect($response['data'] ?? [])
            ->map(fn (array $item) => new ApiPost($item));

        return $this->buildPaginator($items, $response['pagination'] ?? [], $perPage, $page, '/blog');
    }

    public function getBlogPostBySlug(string $slug): ?ApiPost
    {
        $data = ($this->get("/blog/slug/{$slug}"))['data'] ?? null;
        return $data ? new ApiPost($data) : null;
    }

    public function getBlogPostById(int $id): ?ApiPost
    {
        $data = ($this->get("/blog/{$id}"))['data'] ?? null;
        return $data ? new ApiPost($data) : null;
    }

    /** @return Collection<int, ApiPost> */
    public function getTrendingPosts(int $limit = 6): Collection
    {
        $response = $this->cachedGet('/blog/trending', ['limit' => $limit]);
        return collect($response['data'] ?? [])->map(fn ($i) => new ApiPost($i));
    }

    // -------------------------------------------------------------------------
    // News
    // -------------------------------------------------------------------------

    /** @return LengthAwarePaginator<ApiPost> */
    public function getNews(int $perPage = 9, int $page = 1, ?string $search = null, ?string $category = null): LengthAwarePaginator
    {
        $params   = $this->filterParams(compact('perPage', 'page', 'search', 'category'));
        $response = $this->cachedGet('/news', $params);

        $items = collect($response['data'] ?? [])
            ->map(fn (array $item) => new ApiPost($item));

        return $this->buildPaginator($items, $response['pagination'] ?? [], $perPage, $page, '/news');
    }

    public function getNewsBySlug(string $slug): ?ApiPost
    {
        $data = ($this->get("/news/slug/{$slug}"))['data'] ?? null;
        return $data ? new ApiPost($data) : null;
    }

    /** @return Collection<int, ApiPost> */
    public function getLatestNews(int $limit = 6): Collection
    {
        $response = $this->cachedGet('/news/latest', ['limit' => $limit]);
        return collect($response['data'] ?? [])->map(fn ($i) => new ApiPost($i));
    }

    // -------------------------------------------------------------------------
    // Deliverables
    // -------------------------------------------------------------------------

    /** @return LengthAwarePaginator<ApiDeliverable> */
    public function getDeliverables(int $perPage = 12, int $page = 1, ?string $search = null, ?string $category = null, ?string $status = null): LengthAwarePaginator
    {
        $params   = $this->filterParams(compact('perPage', 'page', 'search', 'category', 'status'));
        $response = $this->cachedGet('/deliverables', $params);

        $items = collect($response['data'] ?? [])
            ->map(fn (array $item) => new ApiDeliverable($item));

        return $this->buildPaginator($items, $response['pagination'] ?? [], $perPage, $page, '/deliverables');
    }

    public function getDeliverableBySlug(string $slug): ?ApiDeliverable
    {
        $data = ($this->get("/deliverables/slug/{$slug}"))['data'] ?? null;
        return $data ? new ApiDeliverable($data) : null;
    }

    /** @return Collection<int, ApiDeliverable> */
    public function getPopularDeliverables(int $limit = 6): Collection
    {
        $response = $this->cachedGet('/deliverables/popular', ['limit' => $limit]);
        return collect($response['data'] ?? [])->map(fn ($i) => new ApiDeliverable($i));
    }

    // -------------------------------------------------------------------------
    // Newsletters
    // -------------------------------------------------------------------------

    /** @return LengthAwarePaginator<ApiNewsletter> */
    public function getNewsletters(int $perPage = 12, int $page = 1, ?string $search = null): LengthAwarePaginator
    {
        $params   = $this->filterParams(compact('perPage', 'page', 'search'));
        $response = $this->cachedGet('/newsletters', $params);

        $items = collect($response['data'] ?? [])
            ->map(fn (array $item) => new ApiNewsletter($item));

        return $this->buildPaginator($items, $response['pagination'] ?? [], $perPage, $page, '/newsletters');
    }

    /** @return Collection<int, ApiNewsletter> */
    public function getLatestNewsletters(int $limit = 5): Collection
    {
        $response = $this->cachedGet('/newsletters/latest', ['limit' => $limit]);
        return collect($response['data'] ?? [])->map(fn ($i) => new ApiNewsletter($i));
    }

    /** @return array<string, int> */
    public function getNewsletterStats(): array
    {
        return (array) (($this->cachedGet('/newsletters/stats'))['data'] ?? []);
    }

    /**
     * Subscribe an email to the newsletter via the ERP API.
     *
     * @return array<string, mixed>  Returns ['success' => true] or ['error' => string]
     */
    public function subscribeNewsletter(string $email, ?string $name = null): array
    {
        $payload = array_filter(['email' => $email, 'name' => $name]);

        return $this->post('/newsletters/subscribe', $payload);
    }

    /**
     * Submit a contact form message via the ERP API.
     *
     * @return array<string, mixed>  Returns ['success' => true] or ['error' => string]
     */
    public function submitContact(string $name, string $email, ?string $phone, ?string $subject, string $message): array
    {
        $payload = array_filter([
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'subject' => $subject,
            'message' => $message,
        ], fn ($v) => $v !== null && $v !== '');

        return $this->post('/contacts', $payload);
    }

    /**
     * Invalidate all cached API responses (call after writing to ERP).
     */
    public function flushCache(): void
    {
        Cache::tags(['erp-api'])->flush();
    }

    // -------------------------------------------------------------------------
    // Internal helpers
    // -------------------------------------------------------------------------

    private function buildClient(): \Illuminate\Http\Client\PendingRequest
    {
        $client = Http::timeout($this->timeout)->acceptJson();

        if ($this->token) {
            $client = $client->withToken($this->token);
        }

        return $client;
    }

    /**
     * GET with caching. Uses cache tags when available, falls back to key-based cache.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    private function cachedGet(string $path, array $params = []): array
    {
        $key = 'erp_api:' . md5($path . serialize($params));

        if ($this->cacheTtl <= 0) {
            return $this->get($path, $params);
        }

        try {
            return Cache::tags(['erp-api'])->remember($key, $this->cacheTtl, fn () => $this->get($path, $params));
        } catch (\BadMethodCallException) {
            // Driver doesn't support tags (e.g. file/database) â€“ use plain key
            return Cache::remember($key, $this->cacheTtl, fn () => $this->get($path, $params));
        }
    }

    /**
     * Perform a raw GET request. Never throws â€“ returns [] on failure.
     *
     * @param  array<string, mixed>  $params
     * @return array<string, mixed>
     */
    private function get(string $path, array $params = []): array
    {
        $url = $this->baseUrl . $path;

        try {
            $response = $this->buildClient()->get($url, $params);

            if ($response->successful()) {
                return (array) $response->json();
            }

            Log::warning('ContentApiService: non-2xx response', [
                'url'    => $url,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error("ContentApiService: request failed â€“ {$e->getMessage()}", [
                'url'    => $url,
                'params' => $params,
            ]);
        }

        return [];
    }

    /**
     * Perform a raw POST request. Never throws — returns ['error' => string] on failure.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function post(string $path, array $data = []): array
    {
        $url = $this->baseUrl . $path;

        try {
            $response = $this->buildClient()->post($url, $data);

            if ($response->successful()) {
                return (array) ($response->json() ?? ['success' => true]);
            }

            Log::warning('ContentApiService: non-2xx POST response', [
                'url'    => $url,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return ['error' => $response->json('message') ?? __('Une erreur est survenue.')];
        } catch (\Throwable $e) {
            Log::error("ContentApiService: POST request failed — {$e->getMessage()}", [
                'url'  => $url,
                'data' => array_keys($data),
            ]);

            return ['error' => __('Impossible de contacter le serveur.')];
        }
    }

    /** Remove null/empty values and rename camelCase keys to snake_case. */
    private function filterParams(array $params): array
    {
        $map = [
            'perPage'  => 'per_page',
            'search'   => 'search',
            'page'     => 'page',
            'category' => 'category',
            'status'   => 'status',
        ];

        $out = [];
        foreach ($params as $key => $value) {
            if ($value === null || $value === '' || $value === 0) {
                continue;
            }
            $out[$map[$key] ?? $key] = $value;
        }

        return $out;
    }

    /**
     * Build a LengthAwarePaginator from an API pagination payload.
     *
     * @param  Collection<int, mixed>  $items
     * @param  array<string, int>      $pagination
     * @return LengthAwarePaginator<mixed>
     */
    private function buildPaginator(Collection $items, array $pagination, int $perPage, int $page, string $path): LengthAwarePaginator
    {
        $total   = (int) ($pagination['total']       ?? $items->count());
        $perPage = (int) ($pagination['per_page']    ?? $perPage);
        $page    = (int) ($pagination['current_page'] ?? $page);

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => url($path), 'query' => request()->query()]
        );
    }
}
