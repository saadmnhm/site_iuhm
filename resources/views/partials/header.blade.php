<header x-data="{ open: false }" class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="iu-container">
        <div class="flex h-20 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-lg font-semibold text-[#163260]">
                <svg class="size-5 text-[#214f95]" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 11.5 12 4l8 7.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7.5 10.5V20h9v-9.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>{{ __('ui.brand_name') }}</span>
            </a>

            <nav class="hidden items-center gap-6 lg:flex">
                <div class="relative inline-block">
                    <a href="{{ route('posts.index') }}" class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58]">
                        {{ __('ui.nav_news') }}
                    </a>
                    @if(request()->routeIs('posts.index'))
                        <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                    @endif
                </div>
                <div class="relative inline-block">
                    <a href="{{ route('pages.show', 'services') }}" class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58]">
                        {{ __('ui.nav_services') }}
                    </a>
                    @if(request()->routeIs('pages.show') && request()->segment(2) === 'services')
                        <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                    @endif
                </div>
                <div class="relative inline-block">
                    <a href="{{ route('articles.index') }}" class="text-sm font-semibold text-[#214f95]  transition hover:text-[#0a471b]">
                        {{ __('ui.nav_articles') ?? 'Articles' }}
                    </a>
                    @if(request()->routeIs('articles.index'))
                        <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                    @endif
                </div>
                <div class="relative inline-block">
                    <a href="{{ route('pages.show', 'resources') }}" class="text-sm font-semibold text-[#214f95] transition hover:text-[#122f58]">
                        Livrables
                    </a>
                    @if(request()->routeIs('pages.show') && request()->segment(2) === 'resources')
                        <div class="absolute -bottom-1.5 left-0 h-0.5 w-full bg-[#10752d]"></div>
                    @endif
                </div>
            </nav>

            <div class="flex items-center gap-2 sm:gap-3">
                @include('partials.language-switcher')

                <a
                    href="{{ route('home') }}#contact"
                    class="hidden rounded-full bg-[#0f2454] px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-[#0f2454]/30 transition hover:bg-[#0d1d46] sm:inline-flex"
                >
                    {{ __('ui.donate') }}
                </a>

                <button
                    type="button"
                    class="inline-flex size-10 items-center justify-center rounded-xl border border-slate-200 text-slate-700 lg:hidden"
                    x-on:click="open = !open"
                    :aria-expanded="open.toString()"
                    aria-label="{{ __('ui.open_menu') }}"
                >
                    <svg x-show="!open" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                    <svg x-show="open" x-cloak class="size-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div
            x-show="open"
            x-cloak
            x-transition.opacity.duration.150ms
            class="border-t border-slate-200 py-4 lg:hidden"
        >
            <nav class="flex flex-col gap-1">
                <a href="{{ route('posts.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100">
                    {{ __('ui.nav_news') }}
                </a>
                <a href="{{ route('pages.show', 'services') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100">
                    {{ __('ui.nav_services') }}
                </a>
                <a href="{{ route('articles.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95]  hover:bg-slate-100">
                    {{ __('ui.nav_articles') ?? 'Articles' }}
                </a>
                <a href="{{ route('pages.show', 'resources') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-[#214f95] hover:bg-slate-100">
                    Livrables
                </a>
                <a href="{{ route('home') }}#contact" class="rounded-xl bg-[#0f2454] px-3 py-2 text-center text-sm font-semibold text-white">
                    {{ __('ui.donate') }}
                </a>
            </nav>
        </div>
    </div>
</header>
