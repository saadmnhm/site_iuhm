<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $pages = Page::query()
            ->withCount(['sections', 'posts'])
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $builder) use ($search): void {
                    $builder->where('slug', 'like', "%{$search}%");

                    foreach (config('app.supported_locales', ['en']) as $locale) {
                        $builder
                            ->orWhere("title->{$locale}", 'like', "%{$search}%")
                            ->orWhere("excerpt->{$locale}", 'like', "%{$search}%");
                    }
                });
            })
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('admin.pages.index', compact('pages', 'search'));
    }

    public function create(): View
    {
        $page = new Page([
            'title' => [],
            'excerpt' => [],
            'is_published' => true,
            'sort_order' => 0,
        ]);

        return view('admin.pages.create', compact('page'));
    }

    public function store(StorePageRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! $data['is_published']) {
            $data['published_at'] = null;
        } elseif (empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        Page::create($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', __('ui.created_successfully', ['resource' => __('ui.page')]));
    }

    public function edit(Page $page): View
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $data = $request->validated();

        if (! $data['is_published']) {
            $data['published_at'] = null;
        } elseif (empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $page->update($data);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('success', __('ui.updated_successfully', ['resource' => __('ui.page')]));
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', __('ui.deleted_successfully', ['resource' => __('ui.page')]));
    }
}
