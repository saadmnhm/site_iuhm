@php
    $locales = config('app.supported_locales', ['en']);
    $publishedAtValue = old('published_at', optional($page->published_at)->format('Y-m-d\TH:i'));
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card>
        <div class="grid gap-5 md:grid-cols-2">
            <x-ui.input
                name="slug"
                :label="__('ui.slug')"
                :value="$page->slug"
                :placeholder="__('ui.slug_placeholder')"
                required
            />

            <x-ui.input
                name="sort_order"
                type="number"
                min="0"
                :label="__('ui.sort_order')"
                :value="$page->sort_order"
            />

            <x-ui.input
                name="hero_image"
                type="url"
                :label="__('ui.hero_image_url')"
                :value="$page->hero_image"
                :placeholder="__('ui.url_placeholder')"
            />

            <x-ui.input
                name="published_at"
                type="datetime-local"
                :label="__('ui.published_at')"
                :value="$publishedAtValue"
            />
        </div>

        <div class="mt-5 flex items-center gap-2">
            <input
                id="page-published"
                type="checkbox"
                name="is_published"
                value="1"
                class="size-4 rounded border-slate-300 text-sky-700 focus:ring-sky-500"
                @checked(old('is_published', $page->is_published))
            >
            <label for="page-published" class="text-sm font-medium text-slate-700">{{ __('ui.is_published') }}</label>
        </div>
    </x-ui.card>

    <x-ui.card :title="__('ui.translated_fields')">
        <div class="space-y-6">
            @foreach ($locales as $locale)
                <div class="grid gap-4 rounded-2xl border border-slate-100 p-4 md:grid-cols-2">
                    <x-ui.input
                        :name="'title['.$locale.']'"
                        :error-key="'title.'.$locale"
                        :label="__('ui.title_in', ['language' => __('ui.language_'.$locale)])"
                        :value="$page->title[$locale] ?? null"
                        required
                    />

                    <x-ui.input
                        :name="'excerpt['.$locale.']'"
                        :error-key="'excerpt.'.$locale"
                        type="textarea"
                        :label="__('ui.excerpt_in', ['language' => __('ui.language_'.$locale)])"
                        :value="$page->excerpt[$locale] ?? null"
                        rows="3"
                    />
                </div>
            @endforeach
        </div>
    </x-ui.card>

    <div class="flex flex-wrap gap-3">
        <x-ui.button type="submit">{{ $submitLabel }}</x-ui.button>
        <x-ui.button variant="secondary" :href="route('admin.pages.index')">{{ __('ui.cancel') }}</x-ui.button>
    </div>
</form>
