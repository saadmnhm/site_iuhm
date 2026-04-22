@props([
    'id',
    'title' => null,
])

<dialog id="{{ $id }}" class="ui-modal" aria-labelledby="{{ $id }}-title">
    <div class="ui-modal__panel">
        <div class="flex items-center justify-between gap-4">
            @if ($title)
                <h2 id="{{ $id }}-title" class="text-lg font-semibold text-slate-900">{{ $title }}</h2>
            @endif

            <button
                type="button"
                class="inline-flex size-9 items-center justify-center rounded-full bg-slate-100 text-lg text-slate-600 transition hover:bg-slate-200"
                data-close-modal="{{ $id }}"
                aria-label="{{ __('ui.close') }}"
            >
                &times;
            </button>
        </div>

        <div class="mt-4">
            {{ $slot }}
        </div>
    </div>
</dialog>
