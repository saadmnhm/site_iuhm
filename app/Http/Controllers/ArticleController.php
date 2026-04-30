<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $pageSlug = trim((string) $request->query('page'));

        $posts = Post::query()
            ->with(['page', 'section', 'author'])
            ->published()
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $builder) use ($search): void {
                    foreach (config('app.supported_locales', ['en']) as $locale) {
                        $builder
                            ->orWhere("title->{$locale}", 'like', "%{$search}%")
                            ->orWhere("excerpt->{$locale}", 'like', "%{$search}%");
                    }
                });
            })
            ->when($pageSlug !== '', function (Builder $query) use ($pageSlug): void {
                $query->whereHas('page', fn (Builder $builder) => $builder->where('slug', $pageSlug));
            })
            ->latest('published_at')
            ->latest('id')
            ->paginate(9)
            ->withQueryString();

        $pages = Page::query()
            ->published()
            ->ordered()
            ->get(['id', 'slug', 'title']);

        return view('pages.articles.index', compact('posts', 'pages'));
    }
}
