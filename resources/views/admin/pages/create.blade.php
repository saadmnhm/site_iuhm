@extends('layouts.site')

@section('title', __('ui.create_page').' | '.config('app.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 pb-10 pt-14 sm:px-6 lg:px-8 lg:pt-20">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ __('ui.create_page') }}</h1>
        <p class="mt-2 text-slate-600">{{ __('ui.create_page_intro') }}</p>

        <div class="mt-6">
            @include('admin.pages._form', [
                'action' => route('admin.pages.store'),
                'method' => 'POST',
                'submitLabel' => __('ui.save'),
            ])
        </div>
    </section>
@endsection
