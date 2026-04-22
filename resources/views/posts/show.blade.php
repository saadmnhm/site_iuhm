@extends('layouts.site')

@section('title', $post->getLocalized('title').' | '.config('app.name'))

@section('content')
    <article class="mx-auto max-w-4xl px-4 pb-16 pt-14 sm:px-6 lg:pt-20">
        <p class="text-sm font-semibold uppercase tracking-wide text-slate-500">
            {{ optional($post->published_at)->translatedFormat('d M Y') ?? __('ui.recent') }}
        </p>

        <h1 class="mt-3 text-4xl font-bold tracking-tight text-slate-900 md:text-5xl">{{ $post->getLocalized('title') }}</h1>
        <p class="mt-4 text-lg text-slate-600">{{ $post->getLocalized('excerpt') }}</p>

        <x-ui.card class="mt-8">
            <div class="prose max-w-none text-slate-700">
                {!! nl2br(e($post->getLocalized('content'))) !!}
            </div>
        </x-ui.card>

        <div class="mt-8">
            <x-ui.button variant="secondary" :href="route('posts.index')">
                {{ __('ui.back_to_news') }}
            </x-ui.button>
        </div>
    </article>
@endsection
