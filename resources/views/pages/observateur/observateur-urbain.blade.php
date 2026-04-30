@extends('layouts.site')

@section('title', __('ui.articles_page_title').' | '.config('app.name'))

@php
    $heroImage = 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1600&q=80';
    $categories = [
        ['label' => __('ui.articles_filter_all'), 'value' => 'all'],
        ['label' => __('ui.articles_filter_urbanisme'), 'value' => 'urbanisme'],
        ['label' => __('ui.articles_filter_social'), 'value' => 'social'],
        ['label' => __('ui.articles_filter_environnement'), 'value' => 'environnement'],
    ];
@endphp

@section('content')
    <!-- Hero Section -->
    <section class="relative isolate overflow-hidden bg-[#081d53] text-white">
        <div class="absolute inset-0">
            <img src="{{ $heroImage }}" alt="L'Observateur Urbain" class="h-full w-full object-cover opacity-70">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(5,17,52,0.82)_0%,rgba(5,17,52,0.72)_42%,rgba(8,29,83,0.58)_100%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(115,196,255,0.28),transparent_34%),radial-gradient(circle_at_bottom_right,rgba(101,255,173,0.12),transparent_26%)]"></div>
        </div>

        <div class="relative mx-auto max-w-7xl px-4 pb-28 pt-16 sm:px-6 lg:px-8 lg:pb-32 lg:pt-24">
            <div id="hero" class="max-w-3xl">
                <p class="inline-flex items-center rounded-full border border-white/15 bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-white/85 backdrop-blur">
                    {{ __('ui.observateur_urbain_hero_badge') }}
                </p>

                <h1 class="mt-8 max-w-2xl text-5xl font-black tracking-tight text-white sm:text-6xl lg:text-7xl">
                    {{ __('ui.observateur_urbain_hero_title') }}
                </h1>

                <p class="mt-6 max-w-xl text-base leading-8 text-white/78 sm:text-lg">
                    {{ __('ui.observateur_urbain_hero_description') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Filters and Search -->
    <section class="bg-white py-8 sm:py-12 border-b border-slate-200">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <!-- Filter Buttons -->
                <div class="flex flex-wrap gap-3">
                    @foreach ($categories as $category)
                        <button class="rounded-full px-4 py-2 text-sm font-semibold transition {{ $category['value'] === 'all' ? 'bg-[#0f2454] text-white' : 'border border-slate-300 text-slate-700 hover:border-slate-400' }}">
                            {{ $category['label'] }}
                        </button>
                    @endforeach
                </div>

                <!-- Search Bar -->
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="{{ __('ui.articles_search_placeholder') }}"
                        class="rounded-full border border-slate-300 bg-slate-50 py-2 pl-4 pr-10 text-sm focus:border-[#2d4b86] focus:outline-none focus:ring-1 focus:ring-[#2d4b86]"
                    >
                    <svg class="absolute right-3 top-1/2 size-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none">
                        <path d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="bg-white py-20 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($page->posts->count() > 0)
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($page->posts as $post)
                        <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-lg">
                            @if ($post->image_url)
                                <div class="overflow-hidden bg-slate-200 h-48">
                                    <img src="{{ $post->image_url }}" alt="{{ $post->getLocalized('title') }}" class="h-full w-full object-cover transition group-hover:scale-105">
                                </div>
                            @endif

                            <div class="p-6">
                                @if ($post->category)
                                    <span class="inline-flex items-center rounded-full bg-[#a7ff8f]/15 px-3 py-1 text-xs font-semibold text-[#1d7a2f]">
                                        {{ $post->category }}
                                    </span>
                                @endif

                                <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ $post->getLocalized('title') }}</h3>
                                <p class="mt-2 text-sm line-clamp-3 text-slate-600">{{ $post->getLocalized('excerpt') }}</p>

                                <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $post->published_at?->format('d M Y') }}</span>
                                </div>

                                <a href="{{ route('posts.show', $post) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#2d4b86] transition hover:text-[#0f2454]">
                                    {{ __('ui.read_more') }}
                                    <svg class="size-4" viewBox="0 0 20 20" fill="none">
                                        <path d="M4 10h12M11 5l5 5-5 5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex items-center justify-center gap-2">
                    <button class="rounded-full border border-slate-300 px-4 py-2 text-sm transition hover:bg-slate-50">&larr;</button>
                    <button class="rounded-full bg-[#0f2454] px-4 py-2 text-sm font-semibold text-white">1</button>
                    <button class="rounded-full border border-slate-300 px-4 py-2 text-sm text-slate-700 transition hover:bg-slate-50">2</button>
                    <button class="rounded-full border border-slate-300 px-4 py-2 text-sm text-slate-700 transition hover:bg-slate-50">3</button>
                    <button class="rounded-full border border-slate-300 px-4 py-2 text-sm transition hover:bg-slate-50">&rarr;</button>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-slate-600">{{ __('ui.articles_no_match') }}</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Newsletter CTA -->
    <section class="bg-[#081d53] py-20 sm:py-24">
        <div class="mx-auto max-w-2xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ __('ui.articles_newsletter_title') }}</h2>
            <p class="mt-4 text-base leading-7 text-white/78">{{ __('ui.articles_newsletter_description') }}</p>

            <div class="mt-8 flex gap-3">
                <input 
                    type="email" 
                    placeholder="{{ __('ui.articles_newsletter_placeholder') }}"
                    class="flex-1 rounded-2xl border border-white/20 bg-white/10 px-5 py-3 text-sm text-white placeholder:text-white/50 focus:border-white/40 focus:outline-none"
                >
                <button class="rounded-2xl bg-[#a7ff8f] px-6 py-3 font-semibold text-[#081d53] transition hover:bg-[#90ee7f]">
                    {{ __('ui.articles_newsletter_button') }}
                </button>
            </div>
        </div>
    </section>
@endsection
