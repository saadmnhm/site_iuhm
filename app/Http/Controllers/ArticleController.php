<?php

namespace App\Http\Controllers;

use App\Services\ContentApiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(private readonly ContentApiService $api) {}

    public function index(Request $request): View
    {
        $search   = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));
        $page     = (int) $request->query('page', 1);

        $posts = $this->api->getBlogPosts(9, $page, $search ?: null, $category ?: null);
        $posts->setCollection($posts->getCollection()->filter(fn ($p) => $p->is_published)->values());

        return view('pages.articles.index', [
            'posts' => $posts,
            'pages' => collect(),
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->api->getBlogPostBySlug($slug);

        abort_if($post === null || !$post->is_published, 404);

        $relatedPosts = $this->api->getLatestNews(3)->filter(fn ($p) => $p->is_published)->values();

        return view('pages.actualite.show', compact('post', 'relatedPosts'));
    }
}
