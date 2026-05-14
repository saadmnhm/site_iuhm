<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\ContentApiService;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function __construct(private readonly ContentApiService $api) {}

    public function home(): View
    {
        $with = [
            'sections' => fn ($query) => $query->active()->ordered(),
            'posts' => fn ($query) => $query->published()->latest('published_at')->limit(3),
        ];

        $page = Page::query()
            ->with($with)
            ->where('slug', 'home')
            ->published()
            ->first();

        if (! $page) {
            $page = Page::query()
                ->with($with)
                ->published()
                ->ordered()
                ->first();
        }

        if (! $page) {
            $page = new Page([
                'title' => [
                    'en' => config('app.name'),
                    'fr' => config('app.name'),
                    'ar' => config('app.name'),
                ],
                'excerpt' => [
                    'en' => '',
                    'fr' => '',
                    'ar' => '',
                ],
                'hero_image' => null,
            ]);

            $page->setRelation('sections', collect());
            $page->setRelation('posts', collect());
        }

        $latestNews    = $this->api->getLatestNews(3)->filter(fn ($p) => $p->is_published)->values();
        $trendingPosts = $this->api->getTrendingPosts(3)->filter(fn ($p) => $p->is_published)->values();

        return view('pages.home.home', compact('page', 'latestNews', 'trendingPosts'));
    }
}
