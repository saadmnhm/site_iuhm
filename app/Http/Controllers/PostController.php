<?php

namespace App\Http\Controllers;

use App\Services\ContentApiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private readonly ContentApiService $api) {}

    public function index(Request $request): View
    {
        $search   = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));
        $page     = (int) $request->query('page', 1);

        $posts         = $this->api->getNews(9, $page, $search ?: null, $category ?: null);
        $featuredPosts = $this->api->getLatestNews(6);
        $deliverables  = $this->api->getPopularDeliverables(4);

        return view('pages.posts.index', [
            'posts'         => $posts,
            'featuredPosts' => $featuredPosts,
            'deliverables'  => $deliverables,
            'pages'         => collect(),
            'search'        => $search,
            'pageSlug'      => $category,
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->api->getNewsBySlug($slug);

        abort_if($post === null, 404);

        $relatedPosts = $this->api->getLatestNews(3);

        return view('pages.posts.show', compact('post', 'relatedPosts'));
    }
}
