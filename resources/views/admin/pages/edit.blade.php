@extends('layouts.site')

@section('title', __('ui.edit_page').' | '.config('app.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 pb-10 pt-14 sm:px-6 lg:px-8 lg:pt-20">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ __('ui.edit_page') }}</h1>
        <p class="mt-2 text-slate-600">{{ __('ui.edit_page_intro') }}</p>

        <div class="mt-6">
            @include('admin.pages._form', [
                'action' => route('admin.pages.update', $page),
                'method' => 'PUT',
                'submitLabel' => __('ui.update'),
            ])
        </div>
    </section>
@endsection
