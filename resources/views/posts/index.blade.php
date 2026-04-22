@extends('layouts.site')

@section('title', __('ui.news').' | '.config('app.name'))

@section('content')
    <section class="mx-auto max-w-7xl px-4 pb-8 pt-14 sm:px-6 lg:px-8 lg:pt-20">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-4xl font-bold tracking-tight text-slate-900 md:text-5xl">{{ __('ui.news') }}</h1>
                <p class="mt-3 text-slate-600">{{ __('ui.news_intro') }}</p>
            </div>
        </div>

        <x-ui.card class="mt-8">
            <form method="GET" action="{{ route('posts.index') }}" class="grid gap-4 md:grid-cols-3">
                <x-ui.input
                    name="search"
                    :label="__('ui.search')"
                    :value="$search"
                    :placeholder="__('ui.search_placeholder')"
                />

                <div class="space-y-2">
                    <label for="page-filter" class="form-label">{{ __('ui.page') }}</label>
                    <select id="page-filter" name="page" class="form-input">
                        <option value="">{{ __('ui.all_pages') }}</option>
                        @foreach ($pages as $page)
                            <option value="{{ $page->slug }}" @selected($pageSlug === $page->slug)>
                                {{ $page->getLocalized('title') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-3">
                    <x-ui.button type="submit">{{ __('ui.apply_filters') }}</x-ui.button>
                    <x-ui.button variant="secondary" :href="route('posts.index')">{{ __('ui.clear') }}</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </section>

    <section class="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($posts as $post)
                <x-ui.card class="h-full">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        {{ optional($post->published_at)->translatedFormat('d M Y') ?? __('ui.recent') }}
                    </p>

                    <h2 class="mt-3 text-xl font-semibold text-slate-900">{{ $post->getLocalized('title') }}</h2>
                    <p class="mt-3 text-slate-600">{{ $post->getLocalized('excerpt') }}</p>

                    <x-ui.button variant="ghost" size="sm" class="mt-5" :href="route('posts.show', $post)">
                        {{ __('ui.read_more') }}
                    </x-ui.button>
                </x-ui.card>
            @empty
                <x-ui.card class="md:col-span-2 lg:col-span-3">
                    <p class="text-slate-600">{{ __('ui.no_posts_match') }}</p>
                </x-ui.card>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </section>
@endsection
