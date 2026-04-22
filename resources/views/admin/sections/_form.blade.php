@php
    $locales = config('app.supported_locales', ['en']);
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <x-ui.card>
        <div class="grid gap-5 md:grid-cols-2">
            <div class="space-y-2">
                <label for="section-page" class="form-label">{{ __('ui.page') }}</label>
                <select id="section-page" name="page_id" class="form-input" required>
                    <option value="">{{ __('ui.choose_page') }}</option>
                    @foreach ($pages as $pageItem)
                        <option value="{{ $pageItem->id }}" @selected((int) old('page_id', $section->page_id) === $pageItem->id)>
                            {{ $pageItem->getLocalized('title') }}
                        </option>
                    @endforeach
                </select>
                @error('page_id')
                    <p class="text-sm font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <x-ui.input
                name="key"
                :label="__('ui.key')"
                :value="$section->key"
                :placeholder="__('ui.section_key_placeholder')"
                required
            />

            <x-ui.input
                name="sort_order"
                type="number"
                min="0"
                :label="__('ui.sort_order')"
                :value="$section->sort_order"
            />

            <x-ui.input
                name="cta_url"
                type="url"
                :label="__('ui.cta_url')"
                :value="$section->cta_url"
                :placeholder="__('ui.url_placeholder')"
            />

            <x-ui.input
                name="image_path"
                type="url"
                :label="__('ui.image_url')"
                :value="$section->image_path"
                :placeholder="__('ui.url_placeholder')"
            />
        </div>

        <div class="mt-5 flex items-center gap-2">
            <input
                id="section-active"
                type="checkbox"
                name="is_active"
                value="1"
                class="size-4 rounded border-slate-300 text-sky-700 focus:ring-sky-500"
                @checked(old('is_active', $section->is_active))
            >
            <label for="section-active" class="text-sm font-medium text-slate-700">{{ __('ui.is_active') }}</label>
        </div>
    </x-ui.card>

    <x-ui.card :title="__('ui.translated_fields')">
        <div class="space-y-6">
            @foreach ($locales as $locale)
                <div class="grid gap-4 rounded-2xl border border-slate-100 p-4 md:grid-cols-3">
                    <x-ui.input
                        :name="'heading['.$locale.']'"
                        :error-key="'heading.'.$locale"
                        :label="__('ui.heading_in', ['language' => __('ui.language_'.$locale)])"
                        :value="$section->heading[$locale] ?? null"
                        required
                    />

                    <x-ui.input
                        :name="'cta_label['.$locale.']'"
                        :error-key="'cta_label.'.$locale"
                        :label="__('ui.cta_label_in', ['language' => __('ui.language_'.$locale)])"
                        :value="$section->cta_label[$locale] ?? null"
                    />

                    <x-ui.input
                        :name="'body['.$locale.']'"
                        :error-key="'body.'.$locale"
                        type="textarea"
                        :label="__('ui.body_in', ['language' => __('ui.language_'.$locale)])"
                        :value="$section->body[$locale] ?? null"
                        rows="4"
                        class="md:col-span-3"
                    />
                </div>
            @endforeach
        </div>
    </x-ui.card>

    <div class="flex flex-wrap gap-3">
        <x-ui.button type="submit">{{ $submitLabel }}</x-ui.button>
        <x-ui.button variant="secondary" :href="route('admin.sections.index')">{{ __('ui.cancel') }}</x-ui.button>
    </div>
</form>
