@extends('layouts.site')

@section('title', __('ui.news').' | '.config('app.name'))

@section('content')
<div class="bg-[#FAFAFC] pb-24">
    <!-- Hero Section -->
    <section class="relative pt-16 pb-20 sm:pt-24 sm:pb-28 overflow-hidden">
        <!-- Abstract gradient backgrounds -->
        <div class="absolute inset-0 pointer-events-none" style="background: radial-gradient(circle at 0% 0%, #fdfcff 0%, transparent 60%), radial-gradient(circle at 100% 100%, #f8fafc 0%, transparent 60%);"></div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-[1.1fr_1fr] gap-12 lg:gap-16 items-center">
                <!-- Text Content -->
                <div class="max-w-xl">
                    <h1 class="text-[3.5rem] font-medium tracking-tight text-[#0f172a] leading-[1.1] sm:text-[4rem]">
                        Actualités &<br>
                        <span class="text-[#10752d]">Événements</span>
                    </h1>
                    <p class="mt-8 text-[1.05rem] leading-relaxed text-slate-600 font-medium">
                        Restez informé de la vie de votre quartier. Initiative Urbaine s'engage à partager avec vous les projets, les rencontres et les évolutions qui façonnent notre environnement commun.
                    </p>
                    
                    <div class="mt-10 flex flex-wrap gap-4">
                        <span class="inline-flex rounded-full bg-[#82E682] px-5 py-2.5 text-[0.8rem] font-bold text-[#0a471b] tracking-wide">
                            Quartier Vivant
                        </span>
                        <span class="inline-flex rounded-full bg-[#E2E4E9] px-5 py-2.5 text-[0.8rem] font-bold text-[#475569] tracking-wide">
                            Impact 2024
                        </span>
                    </div>
                </div>

                <!-- Hero Image with Overlapping Card -->
                <div class="relative">
                    <div class="relative overflow-hidden rounded-[2rem] aspect-[4/3] shadow-2xl shadow-slate-300/40">
                        <img src="https://images.unsplash.com/photo-1599839619722-39751411ea63?auto=format&fit=crop&w=1200&q=80" alt="Garden" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Floating Stats Card -->
                    <div class="absolute -bottom-8 left-8 rounded-3xl bg-white/90 backdrop-blur-md px-8 py-5 shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-white">
                        <div class="text-[1.75rem] font-extrabold text-[#0f172a] leading-none mb-1">12+</div>
                        <div class="text-[0.65rem] font-bold uppercase tracking-wider text-slate-500">Événements ce mois</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dernières Nouvelles -->
    <section class="py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10 pb-4 border-b border-slate-200">
                <h2 class="text-2xl font-bold tracking-tight text-[#0f172a] sm:text-[1.75rem]">Dernières Nouvelles</h2>
                <a href="#" class="inline-flex items-center text-[0.85rem] font-bold text-[#10752d] hover:text-[#0b5420] transition">
                    Voir tout <span class="ml-1 text-lg leading-none">→</span>
                </a>
            </div>

            <div class="flex overflow-x-auto gap-8 pb-8 snap-x snap-mandatory pt-2 scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                <style>
                    /* Hide scrollbar for Chrome, Safari and Opera */
                    .flex::-webkit-scrollbar {
                        display: none;
                    }
                </style>
                <!-- Large Main Card 1 -->
                <article class="snap-center sm:snap-start shrink-0 w-[90vw] lg:w-[55vw] group relative flex flex-col sm:flex-row bg-[#F8F9FA] rounded-[2rem] overflow-hidden transition hover:shadow-lg hover:shadow-slate-200/50">
                    <div class="sm:w-1/2 relative h-64 sm:h-auto overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1544928147-79a2dbc1f389?auto=format&fit=crop&w=1000&q=80" alt="Inauguration" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    </div>
                    <div class="sm:w-1/2 flex flex-col p-8 sm:p-10 justify-between">
                        <div>
                            <span class="text-[0.7rem] font-extrabold uppercase tracking-widest text-[#10752d] mb-4 block">PROJET</span>
                            <h3 class="text-2xl font-medium text-[#0f172a] leading-[1.3] mb-4">
                                Inauguration de la Maison de la Parole
                            </h3>
                            <p class="text-sm font-medium text-slate-500 leading-relaxed">
                                Un nouvel espace dédié à l'échange citoyen et à la médiation culturelle ouvre ses portes au cœur du quartier Saint-Michel.
                            </p>
                        </div>
                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200">
                            <span class="text-[0.75rem] font-bold text-slate-500">14 Mars 2024</span>
                            <button class="text-slate-400 hover:text-[#10752d]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            </button>
                        </div>
                    </div>
                </article>

                <!-- Smaller Card 1 -->
                <article class="snap-center sm:snap-start shrink-0 w-[90vw] lg:w-[35vw] group relative flex flex-col bg-[#EBECEF] rounded-[2rem] p-8 sm:p-10 transition hover:shadow-lg hover:shadow-slate-200/50 justify-between">
                    <div>
                        <span class="text-[0.7rem] font-extrabold uppercase tracking-widest text-[#10752d] mb-4 block">VIE DE QUARTIER</span>
                        <h3 class="text-[1.35rem] font-medium text-[#0f172a] leading-[1.3] mb-4">
                            Retour sur le festival d'automne
                        </h3>
                        <p class="text-sm font-medium text-slate-500 leading-relaxed mb-8">
                            Plus de 500 résidents ont participé à notre célébration annuelle des récoltes urbaines...
                        </p>
                        <div class="overflow-hidden rounded-xl h-32 w-full mb-8 relative border-2 border-white shadow-sm">
                            <div class="absolute inset-0 bg-[#258d60] flex items-center justify-center font-caveat text-4xl text-[#ebd88d]">Harvest Stories</div>
                            <img src="https://images.unsplash.com/photo-1518182170546-076616fdcb26?auto=format&fit=crop&w=800&q=80" alt="Festival" class="w-full h-full object-cover opacity-70 mix-blend-overlay">
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-6 border-t border-[#d5d7de]">
                        <span class="text-[0.75rem] font-bold text-slate-500">08 Mars 2024</span>
                        <button class="text-[#0f172a]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>
                    </div>
                </article>
                
                <!-- Large Main Card 2 -->
                <article class="snap-center sm:snap-start shrink-0 w-[90vw] lg:w-[55vw] group relative flex flex-col sm:flex-row bg-[#F8F9FA] rounded-[2rem] overflow-hidden transition hover:shadow-lg hover:shadow-slate-200/50">
                    <div class="sm:w-1/2 relative h-64 sm:h-auto overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1574359411659-15573a27fd0c?auto=format&fit=crop&w=1000&q=80" alt="Rencontre CITOYENNE" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    </div>
                    <div class="sm:w-1/2 flex flex-col p-8 sm:p-10 justify-between">
                        <div>
                            <span class="text-[0.7rem] font-extrabold uppercase tracking-widest text-[#10752d] mb-4 block">RENCONTRE</span>
                            <h3 class="text-2xl font-medium text-[#0f172a] leading-[1.3] mb-4">
                                Assemblée Citoyenne Périodique
                            </h3>
                            <p class="text-sm font-medium text-slate-500 leading-relaxed">
                                Un bilan sur les activités culturelles de ce trimestre et le dialogue continu pour améliorer la convivialité des espaces publics.
                            </p>
                        </div>
                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200">
                            <span class="text-[0.75rem] font-bold text-slate-500">02 Mars 2024</span>
                            <button class="text-slate-400 hover:text-[#10752d]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            </button>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- upcoming events and sidebar -->
    <section class="py-12 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[1.3fr_1fr] gap-12 lg:gap-16 items-start">
                
                <!-- Left: Events List -->
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-[#0f172a] sm:text-[1.75rem] mb-2">Événements à Venir</h2>
                    <p class="text-sm font-medium text-slate-500 mb-10">Participez à la vie collective.</p>

                    <div class="space-y-4">
                        <!-- Event 1 (Highlighted) -->
                        <a href="#" class="group flex items-center gap-6 p-5 sm:p-6 rounded-[2rem] bg-[#F6F7F9] transition hover:bg-[#F0F1F4]">
                            <div class="flex flex-col items-center justify-center w-[4.5rem] h-[4.5rem] rounded-full bg-[#82E682] shadow-sm shrink-0">
                                <span class="text-2xl font-bold text-[#0f172a] leading-none mb-0.5">22</span>
                                <span class="text-[0.6rem] font-extrabold uppercase tracking-widest text-[#0a471b]">MARS</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base font-bold text-[#0f172a] mb-1.5 group-hover:text-[#10752d] transition">Atelier d'Agriculture Urbaine</h4>
                                <div class="flex items-center gap-4 text-[0.8rem] font-bold text-slate-500">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        14:00 - 17:00
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Jardin de la Paix
                                    </span>
                                </div>
                            </div>
                            <div class="text-slate-400 group-hover:text-[#10752d] transition shrink-0 pl-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </a>

                        <!-- Event 2 -->
                        <a href="#" class="group flex items-center gap-6 p-5 sm:p-6 rounded-[2rem] bg-[#F6F7F9] transition hover:bg-[#F0F1F4]">
                            <div class="flex flex-col items-center justify-center w-[4.5rem] h-[4.5rem] rounded-full bg-[#EAECEF] border border-slate-200 shrink-0">
                                <span class="text-2xl font-bold text-[#0f172a] leading-none mb-0.5">28</span>
                                <span class="text-[0.6rem] font-extrabold uppercase tracking-widest text-[#475569]">MARS</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base font-bold text-[#0f172a] mb-1.5 group-hover:text-[#10752d] transition">Assemblée Citoyenne</h4>
                                <div class="flex items-center gap-4 text-[0.8rem] font-bold text-slate-500">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        18:30 - 20:30
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Centre Communautaire
                                    </span>
                                </div>
                            </div>
                            <div class="text-slate-400 group-hover:text-[#10752d] transition shrink-0 pl-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </a>

                        <!-- Event 3 -->
                        <a href="#" class="group flex items-center gap-6 p-5 sm:p-6 rounded-[2rem] bg-[#F6F7F9] transition hover:bg-[#F0F1F4]">
                            <div class="flex flex-col items-center justify-center w-[4.5rem] h-[4.5rem] rounded-full bg-[#EAECEF] border border-slate-200 shrink-0">
                                <span class="text-2xl font-bold text-[#0f172a] leading-none mb-0.5">05</span>
                                <span class="text-[0.6rem] font-extrabold uppercase tracking-widest text-[#475569]">AVRIL</span>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-base font-bold text-[#0f172a] mb-1.5 group-hover:text-[#10752d] transition">Festival Numérique</h4>
                                <div class="flex items-center gap-4 text-[0.8rem] font-bold text-slate-500">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        10:00 - 19:00
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Hub de l'Initiative
                                    </span>
                                </div>
                            </div>
                            <div class="text-slate-400 group-hover:text-[#10752d] transition shrink-0 pl-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Right: Sidebar Cards -->
                <div class="flex flex-col gap-6">
                    <!-- Service Updates Card -->
                    <div class="rounded-2xl p-8 sm:p-10 bg-[#0c132b] text-white shadow-xl shadow-slate-300/40">
                        <h3 class="text-[1.35rem] font-medium mb-8">Mises à jour des Services</h3>
                        
                        <div class="space-y-6">
                            <!-- Update 1 -->
                            <div class="flex gap-4">
                                <div class="shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-[#86e288]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold mb-1">Espace Multimédia</h4>
                                    <p class="text-xs text-white/70 font-medium leading-relaxed">Nouveaux horaires : Ouvert jusqu'à 20h les mardis et jeudis.</p>
                                </div>
                            </div>
                            
                            <!-- Update 2 -->
                            <div class="flex gap-4">
                                <div class="shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-[#86e288]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold mb-1">Programme Jeunesse</h4>
                                    <p class="text-xs text-white/70 font-medium leading-relaxed">Les inscriptions pour les camps de printemps sont maintenant ouvertes.</p>
                                </div>
                            </div>

                            <!-- Update 3 -->
                            <div class="flex gap-4">
                                <div class="shrink-0 mt-0.5">
                                    <svg class="w-5 h-5 text-[#82E682]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold mb-1">Bureau Administratif</h4>
                                    <p class="text-xs text-white/70 font-medium leading-relaxed">Fermeture exceptionnelle ce vendredi pour formation interne.</p>
                                </div>
                            </div>
                        </div>

                        <button class="w-full mt-10 rounded-full border border-white/20 bg-white/5 py-4 text-xs font-bold text-white transition hover:bg-white/10 text-center">
                            Consulter l'annuaire complet
                        </button>
                    </div>

                    <!-- Impact Card -->
                    <div class="rounded-2xl p-8 sm:p-10 bg-[#9BFA9B] shadow-xl shadow-slate-300/40">
                        <h3 class="text-xl font-medium text-[#0f172a] mb-2">Notre Impact</h3>
                        <p class="text-sm font-medium text-[#10752d] leading-relaxed mb-6">Chaque action renforce les liens de notre tissu social.</p>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-[#B2FBB2] rounded-xl p-4">
                                <div class="text-[1.35rem] leading-none mb-1 font-bold text-[#0f172a]">2.4k</div>
                                <div class="text-[0.6rem] font-extrabold uppercase tracking-widest text-[#10752d]">MEMBRES ACTIFS</div>
                            </div>
                            <div class="bg-[#B2FBB2] rounded-xl p-4">
                                <div class="text-[1.35rem] leading-none mb-1 font-bold text-[#0f172a]">150</div>
                                <div class="text-[0.6rem] font-extrabold uppercase tracking-widest text-[#10752d]">ATELIERS / AN</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section removed and Articles removed as per user request -->
</div>
@endsection
