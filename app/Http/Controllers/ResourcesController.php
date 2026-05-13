<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\ContentApiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    public function __construct(private readonly ContentApiService $api) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $page   = (int) $request->query('page', 1);

        $deliverables       = $this->api->getDeliverables(9, $page, $search ?: null);
        $featuredDeliverable = $deliverables->first();
        $recentDeliverables = $this->api->getPopularDeliverables(3);

        $dbPage = Page::query()
            ->where('slug', 'resources')
            ->published()
            ->with(['sections' => fn ($q) => $q->active()->ordered()])
            ->first();

        return view('pages.resources.resources', compact(
            'deliverables',
            'featuredDeliverable',
            'recentDeliverables',
            'search',
        ));
    }
}
