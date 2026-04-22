<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectionRequest;
use App\Http\Requests\UpdateSectionRequest;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $pageId = $request->integer('page_id');

        $sections = Section::query()
            ->with('page')
            ->when($pageId > 0, fn (Builder $query) => $query->where('page_id', $pageId))
            ->when($search !== '', function (Builder $query) use ($search): void {
                $query->where(function (Builder $builder) use ($search): void {
                    $builder->where('key', 'like', "%{$search}%");

                    foreach (config('app.supported_locales', ['en']) as $locale) {
                        $builder->orWhere("heading->{$locale}", 'like', "%{$search}%");
                    }
                });
            })
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        $pages = Page::query()->ordered()->get(['id', 'slug', 'title']);

        return view('admin.sections.index', compact('sections', 'search', 'pageId', 'pages'));
    }

    public function create(): View
    {
        $section = new Section([
            'heading' => [],
            'body' => [],
            'cta_label' => [],
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $pages = Page::query()->ordered()->get(['id', 'slug', 'title']);

        return view('admin.sections.create', compact('section', 'pages'));
    }

    public function store(StoreSectionRequest $request): RedirectResponse
    {
        $section = Section::create($request->validated());

        return redirect()
            ->route('admin.sections.edit', $section)
            ->with('success', __('ui.created_successfully', ['resource' => __('ui.section')]));
    }

    public function edit(Section $section): View
    {
        $pages = Page::query()->ordered()->get(['id', 'slug', 'title']);

        return view('admin.sections.edit', compact('section', 'pages'));
    }

    public function update(UpdateSectionRequest $request, Section $section): RedirectResponse
    {
        $section->update($request->validated());

        return redirect()
            ->route('admin.sections.edit', $section)
            ->with('success', __('ui.updated_successfully', ['resource' => __('ui.section')]));
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();

        return redirect()
            ->route('admin.sections.index')
            ->with('success', __('ui.deleted_successfully', ['resource' => __('ui.section')]));
    }
}
