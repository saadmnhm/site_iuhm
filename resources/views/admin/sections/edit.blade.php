@extends('layouts.site')

@section('title', __('ui.edit_section').' | '.config('app.name'))

@section('content')
    <section class="mx-auto max-w-5xl px-4 pb-10 pt-14 sm:px-6 lg:px-8 lg:pt-20">
        <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ __('ui.edit_section') }}</h1>
        <p class="mt-2 text-slate-600">{{ __('ui.edit_section_intro') }}</p>

        <div class="mt-6">
            @include('admin.sections._form', [
                'action' => route('admin.sections.update', $section),
                'method' => 'PUT',
                'submitLabel' => __('ui.update'),
            ])
        </div>
    </section>
@endsection
