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

        return view('pages.articles.index', [
            'posts' => $posts,
            'pages' => collect(),
        ]);
    }
}
