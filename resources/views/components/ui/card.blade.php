@props(['title' => null])

<article {{ $attributes->merge(['class' => 'rounded-3xl border border-white/70 bg-white/75 p-6 shadow-lg shadow-slate-200/60 backdrop-blur']) }}>
    @if ($title)
        <h3 class="text-lg font-semibold text-slate-900">{{ $title }}</h3>
    @endif

    <div @class(['mt-4' => $title])>
        {{ $slot }}
    </div>
</article>
