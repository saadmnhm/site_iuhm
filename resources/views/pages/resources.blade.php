@extends('layouts.site')

@section('title', __('ui.resources_page_title').' | '.config('app.name'))

@php
    $recentDocs = [
        ['title' => 'Rapport Annuel 2023', 'size' => 'PDF • 4.5 MB', 'icon' => '<svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>'],
        ['title' => 'Guide du Bénévole', 'size' => 'PDF • 2.1 MB', 'icon' => '<svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'],
        ['title' => 'Statuts de l\'Association', 'size' => 'PDF • 800 KB', 'icon' => '<svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>'],
    ];
    $allDocs = [
        [
            'type' => 'PAMPHLET',
            'meta' => '1.2 MB • PDF',
            'title' => 'Initiatives Quartiers Verts',
            'desc' => 'Étude sur l\'impact de la végétalisation urbaine sur le lien social en milieu précaire.',
            'image' => 'bg-[#3AA89B]',
            'render' => '<div class="absolute inset-0 flex items-center justify-center gap-2 px-10"><div class="w-1/2 h-4/5 bg-[#8ED8D0] shadow-xl relative overflow-hidden rounded-sm"><div class="absolute top-1/4 left-4 right-4 h-1 bg-white/60"></div><div class="absolute bottom-1/4 left-4 right-8 h-1 bg-white/60"></div><div class="absolute bottom-8 left-4 right-12 h-1 bg-white/60"></div></div><div class="w-2/5 h-3/5 bg-[#8ED8D0] shadow-xl relative overflow-hidden rounded-sm flex flex-col items-center justify-center text-white"><svg class="w-6 h-6 border-2 border-white rounded mb-2 pb-1" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M12 18l-6-6h12l-6 6z"></path></svg><div class="w-3/4 h-1 bg-white mb-1"></div><div class="w-1/2 h-1 bg-white"></div></div></div>'
        ],
        [
            'type' => 'LIVRE',
            'meta' => '8.4 MB • PDF',
            'title' => 'La Ville pour Tous',
            'desc' => 'Un recueil de témoignages et d\'analyses sur l\'accessibilité universelle dans l\'espace public.',
            'image' => 'bg-[#1E8A83]',
            'render' => '<div class="absolute inset-0 flex items-center justify-center"><div class="w-2/3 h-4/5 bg-[#17726A] shadow-2xl relative rounded-sm"><div class="absolute top-1/3 left-1/2 -translate-x-1/2 -translate-y-1/2 text-white"><svg class="w-16 h-16" viewBox="0 0 24 24" fill="white"><path d="M12 2C6.48 2 2 6.48 2 12c0 2.92 1.25 5.54 3.25 7.41L12 11l6.75 8.41C20.75 17.54 22 14.92 22 12c0-5.52-4.48-10-10-10z"></path></svg></div><div class="absolute bottom-4 left-0 right-0 h-2 bg-white"></div></div></div>'
        ],
        [
            'type' => 'RAPPORT',
            'meta' => '3.7 MB • PDF',
            'title' => 'Synthèse Projets 2022',
            'desc' => 'Analyse chiffrée et qualitative des actions menées sur le terrain par nos équipes.',
            'image' => 'bg-[#FAF9FB]',
            'render' => '<div class="absolute inset-0 p-8"><img src="https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80" alt="Book" class="w-full h-full object-cover shadow-2xl rounded-r-xl rounded-l-sm border-l-4 border-[#0c1a30] mix-blend-multiply opacity-90 sepia-[.3] hue-rotate-[180deg]"></div>'
        ],
    ];
@endphp

@section('content')
    <div class="bg-white pb-24">
        <!-- Header -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <div class="max-w-2xl">
                    <h1 class="text-5xl md:text-6xl font-bold tracking-tight text-[#16254d]">
                        Bibliothèque de <br>
                        <span class="text-[#0e7025]">Ressources</span>
                    </h1>
                    <p class="mt-6 text-lg text-slate-600 max-w-xl">
                        Accédez à l'ensemble de nos publications, guides et rapports de recherche. Des outils concrets pour accompagner le développement urbain et social.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="rounded-full bg-[#adfda2] px-5 py-2.5 text-sm font-semibold text-[#0a1e47] hover:bg-[#9af08e] transition">
                        Toutes les publications
                    </button>
                    <button class="rounded-full bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-500 hover:bg-slate-200 transition">
                        Recherche
                    </button>
                </div>
            </div>
        </div>

        <!-- Featured Document & Recent -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
            <div class="grid lg:grid-cols-[1fr_360px] gap-8 items-start">
                <!-- Featured Card -->
                <div class="relative overflow-hidden rounded-[2rem] bg-[#162a52] text-white p-8 md:p-12 shadow-xl min-h-[420px] flex flex-col justify-center">
                    <!-- Decorative blueprint background overlay -->
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-repeat" style="background-image: radial-gradient(circle at 100% 50%, rgba(255,255,255,0.1) 0%, transparent 50%); mix-blend-mode: overlay;"></div>
                    <!-- Blueprint line decorations -->
                    <div class="absolute inset-0 pointer-events-none opacity-30">
                        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                            <rect width="100%" height="100%" fill="none" stroke="currentColor" stroke-width="0.5" stroke-dasharray="4 4" rx="32"/>
                            <!-- fake chart/grid lines -->
                            <path d="M0,80 L800,80 M150,0 L150,600 M0,200 L800,200 M300,0 L300,600" stroke="currentColor" stroke-width="0.5" />
                        </svg>
                    </div>

                    <div class="relative z-10 max-w-xl">
                        <span class="inline-flex rounded bg-[#10752d] px-2.5 py-1 text-xs font-bold uppercase tracking-wide text-white">
                            NOUVEAUTÉ
                        </span>
                        <h2 class="mt-4 text-4xl font-bold tracking-tight">Manuel de l'Inclusion Urbaine 2024</h2>
                        <p class="mt-4 text-lg text-white/70">
                            Un guide complet sur les meilleures pratiques de participation citoyenne dans les quartiers en développement.
                        </p>
                        <button class="mt-8 inline-flex items-center gap-2 rounded-full bg-[#127027] px-6 py-3 font-semibold text-white transition hover:bg-[#0f5a1f]">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v7.586l2.293-2.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L9 11.586V4a1 1 0 011-1zM5 15a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            Télécharger (12.4 MB)
                        </button>
                    </div>
                </div>

                <!-- Recent Docs sidebar -->
                <div class="bg-[#FAFAFB] rounded-[2rem] p-8 lg:p-10">
                    <h3 class="text-xl font-bold text-[#16254d]">Documents Récents</h3>
                    <ul class="mt-8 space-y-6">
                        @foreach($recentDocs as $doc)
                        <li class="flex items-center gap-4 group cursor-pointer">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white shadow-sm border border-slate-100 group-hover:border-slate-300 transition">
                                {!! $doc['icon'] !!}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-[#16254d] text-sm group-hover:text-[#0e7025] transition">{{ $doc['title'] }}</h4>
                                <p class="text-[13px] font-medium text-slate-500">{{ $doc['size'] }}</p>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <a href="#" class="mt-8 inline-flex items-center gap-2 text-sm font-bold text-[#0e7025] hover:text-[#0a4d1a] transition">
                        Voir toutes les archives
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Complete Library -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
                <h2 class="text-2xl font-bold text-[#16254d]">Bibliothèque Complète</h2>
                <div class="relative w-full sm:w-72">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" placeholder="Rechercher un document..." class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm placeholder:text-slate-500 focus:border-[#16254d] focus:outline-none focus:ring-1 focus:ring-[#16254d]">
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach($allDocs as $doc)
                <div class="group flex flex-col h-full">
                    <!-- Image Card Mockup -->
                    <div class="relative w-full aspect-[4/5] rounded-xl {{ $doc['image'] }} overflow-hidden mb-6 shadow-sm">
                        {!! $doc['render'] !!}
                    </div>
                    
                    <!-- Metadata & Info -->
                    <div class="flex flex-col flex-1">
                        <div class="flex items-center justify-between text-xs font-bold uppercase tracking-wider mb-2">
                            <span class="text-[#0e7025]">{{ $doc['type'] }}</span>
                            <span class="text-slate-500">{{ $doc['meta'] }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-[#16254d] mb-2 leading-snug">{{ $doc['title'] }}</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $doc['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-16 flex justify-center">
                <button class="rounded-full border border-slate-300 bg-white px-8 py-3 text-sm font-bold text-[#16254d] shadow-sm hover:bg-slate-50 hover:border-slate-400 transition">
                    Charger plus de documents
                </button>
            </div>
        </div>
    </div>
@endsection
