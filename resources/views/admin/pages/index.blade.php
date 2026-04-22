@extends('layouts.site')

@section('title', __('ui.manage_pages').' | '.config('app.name'))

@section('content')
    <section class="mx-auto max-w-7xl px-4 pb-8 pt-14 sm:px-6 lg:px-8 lg:pt-20">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ __('ui.manage_pages') }}</h1>
                <p class="mt-2 text-slate-600">{{ __('ui.pages_admin_intro') }}</p>
            </div>

            <x-ui.button :href="route('admin.pages.create')">{{ __('ui.create_page') }}</x-ui.button>
        </div>

        <x-ui.card class="mt-6">
            <form method="GET" action="{{ route('admin.pages.index') }}" class="grid gap-4 md:grid-cols-[1fr_auto_auto]">
                <x-ui.input
                    name="search"
                    :label="__('ui.search')"
                    :value="$search"
                    :placeholder="__('ui.search_pages_placeholder')"
                />

                <div class="flex items-end">
                    <x-ui.button type="submit">{{ __('ui.search') }}</x-ui.button>
                </div>

                <div class="flex items-end">
                    <x-ui.button variant="secondary" :href="route('admin.pages.index')">{{ __('ui.clear') }}</x-ui.button>
                </div>
            </form>
        </x-ui.card>

        <x-ui.card class="mt-6 overflow-hidden px-0 py-0">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="table-head-cell">{{ __('ui.slug') }}</th>
                            <th class="table-head-cell">{{ __('ui.title') }}</th>
                            <th class="table-head-cell">{{ __('ui.status') }}</th>
                            <th class="table-head-cell">{{ __('ui.sections') }}</th>
                            <th class="table-head-cell">{{ __('ui.news') }}</th>
                            <th class="table-head-cell">{{ __('ui.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($pages as $page)
                            <tr>
                                <td class="table-cell font-medium text-slate-900">{{ $page->slug }}</td>
                                <td class="table-cell">{{ $page->getLocalized('title') }}</td>
                                <td class="table-cell">
                                    <span @class([
                                        'rounded-full px-2.5 py-1 text-xs font-semibold',
                                        'bg-emerald-100 text-emerald-700' => $page->is_published,
                                        'bg-amber-100 text-amber-700' => ! $page->is_published,
                                    ])>
                                        {{ $page->is_published ? __('ui.published') : __('ui.draft') }}
                                    </span>
                                </td>
                                <td class="table-cell">{{ $page->sections_count }}</td>
                                <td class="table-cell">{{ $page->posts_count }}</td>
                                <td class="table-cell">
                                    <div class="flex flex-wrap gap-2">
                                        <x-ui.button size="sm" variant="secondary" :href="route('admin.pages.edit', $page)">
                                            {{ __('ui.edit') }}
                                        </x-ui.button>

                                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" onsubmit="return confirm('{{ __('ui.delete_confirmation') }}');">
                                            @csrf
                                            @method('DELETE')

                                            <x-ui.button type="submit" size="sm" variant="danger">
                                                {{ __('ui.delete') }}
                                            </x-ui.button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="table-cell text-center text-slate-600" colspan="6">{{ __('ui.no_pages_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>

        <div class="mt-6">
            {{ $pages->links() }}
        </div>
    </section>
@endsection
