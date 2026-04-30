<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
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

        return view('pages.home.home', compact('page'));
    }

    public function show(Page $page): View
    {
        abort_unless($page->is_published, 404);

        $page->load([
            'sections' => fn ($query) => $query->active()->ordered(),
        ]);

        $posts = $page->posts()
            ->published()
            ->latest('published_at')
            ->paginate(6)
            ->withQueryString();

        // Check if there's a custom template for this page slug
        $customTemplatesFolder = ['observateur', 'services', 'about', 'resources'];
        $customTemplates = ['observateur-urbain', 'services', 'about', 'resources'];
        $view = in_array($page->slug, $customTemplates)
            ? "pages.{$customTemplatesFolder[array_search($page->slug, $customTemplates)]}.{$page->slug}" 
            : 'pages.show';

        return view($view, compact('page', 'posts'));
    }
}
