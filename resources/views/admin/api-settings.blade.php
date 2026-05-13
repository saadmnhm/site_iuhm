<x-layouts.app :title="'Configuration API ERP'">
<div class="max-w-2xl mx-auto py-10 px-4" x-data="apiSettings()">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Configuration de l'API ERP</h1>
        <p class="mt-1 text-sm text-slate-500">Connexion entre ce site et la plateforme IUHM (ERP).</p>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 flex items-start gap-3">
        <svg class="mt-0.5 size-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
    </div>
    @endif
    @if($errors->any())
    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4">
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Live status card --}}
    <div class="mb-6 rounded-2xl border bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50">
            <div class="flex items-center gap-3">
                <div class="flex size-10 items-center justify-center rounded-xl bg-[#0B1528] text-white shrink-0">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">Statut de connexion ERP</p>
                    <p class="text-xs text-slate-500">Vérification en temps réel</p>
                </div>
            </div>
            <button type="button" @click="ping()"
                    :disabled="pinging"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition disabled:opacity-50">
                <svg class="size-4" :class="{'animate-spin': pinging}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span x-text="pinging ? 'Test en cours…' : 'Tester la connexion'"></span>
            </button>
        </div>
        <div class="px-6 py-5">
            <template x-if="pingResult === null && !pinging">
                <p class="text-sm text-slate-400 italic">Cliquez sur "Tester la connexion" pour vérifier le lien avec l'ERP.</p>
            </template>
            <template x-if="pinging">
                <div class="flex items-center gap-3">
                    <svg class="size-5 animate-spin text-slate-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <p class="text-sm text-slate-500">Connexion en cours…</p>
                </div>
            </template>
            <template x-if="pingResult !== null && !pinging">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1"
                              :class="pingResult.ok ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-red-100 text-red-700 ring-red-200'">
                            <span class="size-2 rounded-full" :class="pingResult.ok ? 'bg-emerald-500' : 'bg-red-500'"></span>
                            <span x-text="pingResult.ok ? 'Connecté' : 'Hors ligne'"></span>
                        </span>
                        <span class="text-xs text-slate-400" x-text="pingResult.latency_ms + ' ms'"></span>
                        <span class="text-xs text-slate-400" x-text="'HTTP ' + (pingResult.status ?? 'N/A')"></span>
                    </div>
                    <template x-if="pingResult.ok && pingResult.data && pingResult.data.stats">
                        <div class="grid grid-cols-4 gap-3">
                            <template x-for="[label, val] in Object.entries(pingResult.data.stats)">
                                <div class="rounded-xl bg-slate-50 border border-slate-100 px-3 py-2 text-center">
                                    <p class="text-lg font-black text-[#0B1528]" x-text="val"></p>
                                    <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400 mt-0.5" x-text="label.replace('_', ' ')"></p>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="!pingResult.ok">
                        <p class="text-sm text-red-600 mt-2" x-text="pingResult.data?.error ?? 'Impossible de joindre l\'ERP'"></p>
                    </template>
                </div>
            </template>
        </div>
    </div>

    {{-- Settings form --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100 bg-slate-50">
            <div class="flex size-10 items-center justify-center rounded-xl bg-[#0B1528] text-white shrink-0">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900">Paramètres de connexion</p>
                <p class="text-xs text-slate-500">Modifiez les variables d'environnement directement</p>
            </div>
        </div>

        <form action="{{ route('admin.api-settings.update') }}" method="POST" class="px-6 py-6 space-y-5">
            @csrf @method('PUT')

            {{-- Base URL --}}
            <div>
                <label for="api_base_url" class="block text-sm font-semibold text-slate-700 mb-1.5">URL de l'API <span class="text-red-500">*</span></label>
                <input type="url" id="api_base_url" name="api_base_url"
                       value="{{ old('api_base_url', $currentUrl) }}"
                       placeholder="http://127.0.0.1:8000/api/v1" required
                       class="block w-full rounded-xl border @error('api_base_url') border-red-400 bg-red-50 @else border-slate-300 bg-white @enderror py-2.5 px-4 text-sm focus:border-[#82E682] focus:ring-2 focus:ring-[#82E682]/20 transition-all">
                <p class="mt-1 text-xs text-slate-400">Ex: <code class="bg-slate-100 px-1 rounded">http://127.0.0.1:8000/api/v1</code></p>
                @error('api_base_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Platform URL --}}
            <div>
                <label for="api_platform_url" class="block text-sm font-semibold text-slate-700 mb-1.5">URL de la plateforme (images) <span class="text-red-500">*</span></label>
                <input type="url" id="api_platform_url" name="api_platform_url"
                       value="{{ old('api_platform_url', $currentPlatformUrl) }}"
                       placeholder="http://127.0.0.1:8000" required
                       class="block w-full rounded-xl border @error('api_platform_url') border-red-400 bg-red-50 @else border-slate-300 bg-white @enderror py-2.5 px-4 text-sm focus:border-[#82E682] focus:ring-2 focus:ring-[#82E682]/20 transition-all">
                <p class="mt-1 text-xs text-slate-400">Préfixe des chemins d'images relatifs retournés par l'API.</p>
                @error('api_platform_url')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Token --}}
            <div>
                <label for="api_token" class="block text-sm font-semibold text-slate-700 mb-1.5">Token d'authentification</label>
                <input type="password" id="api_token" name="api_token"
                       value="{{ old('api_token', $currentToken) }}"
                       placeholder="Laisser vide si l'API est publique"
                       class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 px-4 text-sm focus:border-[#82E682] focus:ring-2 focus:ring-[#82E682]/20 transition-all font-mono">
                <p class="mt-1 text-xs text-slate-400">Envoyé comme <code class="bg-slate-100 px-1 rounded">Authorization: Bearer …</code> sur chaque requête.</p>
            </div>

            {{-- Timeout + Cache row --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="api_timeout" class="block text-sm font-semibold text-slate-700 mb-1.5">Timeout (secondes)</label>
                    <input type="number" id="api_timeout" name="api_timeout"
                           value="{{ old('api_timeout', $currentTimeout) }}" min="1" max="120"
                           class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 px-4 text-sm focus:border-[#82E682] focus:ring-2 focus:ring-[#82E682]/20 transition-all">
                </div>
                <div>
                    <label for="api_cache_ttl" class="block text-sm font-semibold text-slate-700 mb-1.5">Cache (secondes, 0=désactivé)</label>
                    <input type="number" id="api_cache_ttl" name="api_cache_ttl"
                           value="{{ old('api_cache_ttl', $currentCacheTtl) }}" min="0" max="86400"
                           class="block w-full rounded-xl border border-slate-300 bg-white py-2.5 px-4 text-sm focus:border-[#82E682] focus:ring-2 focus:ring-[#82E682]/20 transition-all">
                </div>
            </div>

            {{-- Config preview --}}
            <div class="rounded-xl bg-slate-50 border border-slate-200 px-5 py-4">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Valeurs actuelles (.env)</p>
                <div class="font-mono text-xs text-slate-600 space-y-1 break-all">
                    <p><span class="text-indigo-600">CONTENT_API_BASE_URL</span>=<span class="text-green-700">{{ $currentUrl }}</span></p>
                    <p><span class="text-indigo-600">CONTENT_API_PLATFORM_URL</span>=<span class="text-green-700">{{ $currentPlatformUrl }}</span></p>
                    <p><span class="text-indigo-600">CONTENT_API_TOKEN</span>=<span class="text-green-700">{{ $currentToken ? '***' : '(non défini)' }}</span></p>
                    <p><span class="text-indigo-600">CONTENT_API_TIMEOUT</span>=<span class="text-green-700">{{ $currentTimeout }}</span></p>
                    <p><span class="text-indigo-600">CONTENT_API_CACHE_TTL</span>=<span class="text-green-700">{{ $currentCacheTtl }}</span></p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#0B1528] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#1e3a8a] transition-colors shadow-sm">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Enregistrer
                </button>
                <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">Annuler</a>
            </div>
        </form>
    </div>

    {{-- Info --}}
    <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 size-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-sm text-blue-800 space-y-1">
                <p class="font-semibold">Endpoints ERP utilisés</p>
                <ul class="list-disc list-inside text-blue-700 text-xs space-y-0.5 font-mono">
                    <li>GET /api/v1/health — vérification santé</li>
                    <li>GET /api/v1/blog — articles</li>
                    <li>GET /api/v1/news — actualités</li>
                    <li>GET /api/v1/deliverables — livrables</li>
                    <li>GET /api/v1/newsletters — newsletter</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function apiSettings() {
    return {
        pinging: false,
        pingResult: null,
        async ping() {
            this.pinging = true;
            this.pingResult = null;
            try {
                const r = await fetch('{{ route('admin.api-settings.ping') }}', {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                this.pingResult = await r.json();
            } catch (e) {
                this.pingResult = { ok: false, latency_ms: 0, status: null, data: { error: e.message } };
            }
            this.pinging = false;
        }
    }
}
</script>
</x-layouts.app>

<div class="max-w-2xl mx-auto py-10 px-4">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Configuration de l'API Contenu</h1>
        <p class="mt-1 text-sm text-slate-500">
            Configurez l'URL de base de l'API externe qui alimente les sections
            Blog, Actualités, Livrables et Newsletter de votre site.
        </p>
    </div>

    {{-- Success alert --}}
    @if(session('success'))
    <div class="mb-6 rounded-xl bg-green-50 border border-green-200 px-5 py-4 flex items-start gap-3">
        <svg class="mt-0.5 size-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Error alerts --}}
    @if($errors->any())
    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-5 py-4">
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Settings card --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        {{-- Card header --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100 bg-slate-50">
            <div class="flex size-10 items-center justify-center rounded-xl bg-[#0B1528] text-white shrink-0">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-slate-900">API Contenu</p>
                <p class="text-xs text-slate-500">Paramètres de connexion</p>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.api-settings.update') }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Base URL --}}
            <div>
                <label for="api_base_url" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    URL de base de l'API
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="size-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <input
                        type="url"
                        id="api_base_url"
                        name="api_base_url"
                        value="{{ old('api_base_url', $currentUrl) }}"
                        placeholder="https://votre-domaine/api/v1"
                        required
                        class="block w-full rounded-xl border @error('api_base_url') border-red-400 bg-red-50 @else border-slate-300 bg-white @enderror py-2.5 pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#82E682] focus:ring-2 focus:ring-[#82E682]/20 transition-all"
                    >
                </div>
                <p class="mt-1.5 text-xs text-slate-400">
                    Exemple&nbsp;: <code class="font-mono bg-slate-100 px-1 rounded">https://your-domain/api/v1</code>
                </p>
                @error('api_base_url')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Timeout --}}
            <div>
                <label for="api_timeout" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Délai d'expiration (secondes)
                </label>
                <input
                    type="number"
                    id="api_timeout"
                    name="api_timeout"
                    value="{{ old('api_timeout', $currentTimeout) }}"
                    min="1"
                    max="120"
                    class="block w-32 rounded-xl border border-slate-300 bg-white py-2.5 px-4 text-sm text-slate-900 focus:border-[#82E682] focus:ring-2 focus:ring-[#82E682]/20 transition-all"
                >
                <p class="mt-1.5 text-xs text-slate-400">
                    Durée maximale d'attente d'une réponse de l'API (recommandé&nbsp;: 10 s).
                </p>
            </div>

            {{-- Current config preview --}}
            <div class="rounded-xl bg-slate-50 border border-slate-200 px-5 py-4">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Configuration actuelle (.env)</p>
                <div class="font-mono text-xs text-slate-600 space-y-1">
                    <p><span class="text-indigo-600">CONTENT_API_BASE_URL</span>=<span class="text-green-700 break-all">{{ $currentUrl }}</span></p>
                    <p><span class="text-indigo-600">CONTENT_API_TIMEOUT</span>=<span class="text-green-700">{{ $currentTimeout }}</span></p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-[#0B1528] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#1e3a8a] transition-colors shadow-sm">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Enregistrer
                </button>
                <a href="{{ route('admin.pages.index') }}"
                    class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    {{-- Info box --}}
    <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 size-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-blue-800 space-y-1">
                <p class="font-semibold">Endpoints utilisés</p>
                <ul class="list-disc list-inside text-blue-700 text-xs space-y-0.5 font-mono">
                    <li>GET /blog — articles</li>
                    <li>GET /news — actualités</li>
                    <li>GET /deliverables — livrables</li>
                    <li>GET /newsletters — newsletter</li>
                </ul>
                <p class="text-xs text-blue-600 mt-2">
                    Vous pouvez aussi éditer manuellement la variable
                    <code class="bg-blue-100 px-1 rounded">CONTENT_API_BASE_URL</code>
                    dans votre fichier <code class="bg-blue-100 px-1 rounded">.env</code>.
                </p>
            </div>
        </div>
    </div>

</div>
</x-layouts.app>
