<div class="p-6 sm:p-8">

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-700">CONTENT STUDIO</p>
        <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">Vue d'ensemble du contenu</h2>
        <p class="mt-2 text-sm leading-6 text-slate-600">
            Gérez et suivez l'ensemble du contenu publié sur le site web de l'Initiative Urbaine.
        </p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5 mb-10">
        @foreach ($stats_card as $card)
        <a href="{{ route($card['route']) }}"
           class="group rounded-2xl bg-white p-5 shadow-sm border border-slate-100 hover:shadow-md hover:-translate-y-0.5 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $card['bg'] }}">
                    <flux:icon.{{ $card['icon'] }} class="size-5 {{ $card['color'] }}" />
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">{{ $card['sub'] }}</span>
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $card['total'] }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">{{ $card['label'] }}</p>
        </a>
        @endforeach
    </div>

    {{-- Recent Content: Blog + News --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 mb-10">

        {{-- Recent Blog Posts --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">Articles récents</h3>
                <a href="{{ route('admin.media.blog') }}" class="text-xs font-semibold text-[#0f1d57] hover:underline">Voir tout →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentBlog as $post)
                <div class="flex items-start gap-4 rounded-xl border border-slate-100 bg-white p-4 shadow-sm hover:shadow-md transition">
                    @if($post->image)
                    <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg">
                        <img src="{{ \Illuminate\Support\Str::startsWith($post->image, ['http','https']) ? $post->image : asset($post->image) }}" alt="" class="h-full w-full object-cover">
                    </div>
                    @else
                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50">
                        <flux:icon.newspaper class="size-6 text-indigo-300" />
                    </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-semibold text-slate-900 truncate">{{ $post->title }}</h4>
                        <p class="mt-1 text-xs text-slate-400">{{ $post->published_at?->format('d M Y') ?? '—' }}</p>
                    </div>
                    <span class="flex-shrink-0 inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">Publié</span>
                </div>
                @empty
                <div class="rounded-xl border border-dashed border-slate-200 bg-white p-6 text-center text-sm text-slate-400">
                    Aucun article publié.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent News --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">Actualités récentes</h3>
                <a href="{{ route('admin.media.news') }}" class="text-xs font-semibold text-[#0f1d57] hover:underline">Voir tout →</a>
            </div>
            <div class="space-y-3">
                @forelse($recentNews as $news)
                <div class="flex items-start gap-4 rounded-xl border border-slate-100 bg-white p-4 shadow-sm hover:shadow-md transition">
                    @if($news->image)
                    <div class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg">
                        <img src="{{ \Illuminate\Support\Str::startsWith($news->image, ['http','https']) ? $news->image : asset($news->image) }}" alt="" class="h-full w-full object-cover">
                    </div>
                    @else
                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50">
                        <flux:icon.rss class="size-6 text-blue-300" />
                    </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-semibold text-slate-900 truncate">{{ $news->title }}</h4>
                        <p class="mt-1 text-xs text-slate-400">{{ $news->published_at?->format('d M Y') ?? '—' }}</p>
                    </div>
                    <span class="flex-shrink-0 inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">Publié</span>
                </div>
                @empty
                <div class="rounded-xl border border-dashed border-slate-200 bg-white p-6 text-center text-sm text-slate-400">
                    Aucune actualité publiée.
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Newsletters --}}
    @if($recentNewsletters->count() > 0)
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400">Infolettres récentes</h3>
            <a href="{{ route('admin.media.newsletters') }}" class="text-xs font-semibold text-[#0f1d57] hover:underline">Voir tout →</a>
        </div>
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach($recentNewsletters as $nl)
            <div class="rounded-xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100">
                        <flux:icon.mail class="size-5 text-emerald-600" />
                    </div>
                    @if($nl->issue_number)
                    <span class="text-xs font-bold text-slate-400">#{{ $nl->issue_number }}</span>
                    @endif
                </div>
                <h4 class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $nl->title }}</h4>
                <p class="text-xs text-slate-400 mt-2">{{ $nl->created_at->format('d M Y') }}</p>
                @if($nl->is_published)
                <span class="mt-2 inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">Envoyée</span>
                @else
                <span class="mt-2 inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">Brouillon</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
