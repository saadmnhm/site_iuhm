<div class="p-6 sm:p-8">

    {{-- Header --}}
    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between mb-8">
        <div>
            <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-violet-600">CONTENT STUDIO</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Messages de contact</h2>
            <p class="mt-2 text-sm text-slate-600">Consultez et gérez les messages envoyés via le formulaire de contact du site.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 mb-3">
                <flux:icon.inbox class="size-5 text-violet-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $stats['total'] }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Total</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 mb-3">
                <flux:icon.envelope class="size-5 text-rose-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $stats['unread'] }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Non lus</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 mb-3">
                <flux:icon.envelope-open class="size-5 text-emerald-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $stats['read'] }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Lus</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-col gap-3 sm:flex-row mb-6">
        <div class="relative flex-1">
            <flux:icon.magnifying-glass class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 size-4 text-slate-400" />
            <input type="text" wire:model.live.debounce="search" placeholder="Rechercher par nom, email, sujet…"
                   class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm outline-none focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
        </div>
        <select wire:model.live="filter" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57]">
            <option value="all">Tous</option>
            <option value="unread">Non lus</option>
            <option value="read">Lus</option>
        </select>
    </div>

    {{-- Detail panel --}}
    @if($showDetail && $contact)
    <div class="mb-8 rounded-2xl border border-violet-200 bg-violet-50 p-6 shadow-sm">
        <div class="flex items-start justify-between gap-4 mb-5">
            <div>
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-violet-600 mb-1">Message de contact</p>
                <h3 class="text-lg font-bold text-slate-900">{{ $contact->subject ?: '(Sans sujet)' }}</h3>
            </div>
            <button wire:click="closeDetail()" class="rounded-full p-2 text-slate-400 hover:bg-white hover:text-slate-700 transition">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="grid gap-4 sm:grid-cols-3 mb-5">
            <div class="rounded-xl bg-white p-4 border border-violet-100">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-violet-500 mb-1">Nom</p>
                <p class="text-sm font-semibold text-slate-900">{{ $contact->name }}</p>
            </div>
            <div class="rounded-xl bg-white p-4 border border-violet-100">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-violet-500 mb-1">Email</p>
                <a href="mailto:{{ $contact->email }}" class="text-sm font-semibold text-[#0f1d57] hover:underline">{{ $contact->email }}</a>
            </div>
            <div class="rounded-xl bg-white p-4 border border-violet-100">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-violet-500 mb-1">Téléphone</p>
                <p class="text-sm font-semibold text-slate-900">{{ $contact->phone ?: '—' }}</p>
            </div>
        </div>

        <div class="rounded-xl bg-white p-5 border border-violet-100 mb-4">
            <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-violet-500 mb-2">Message</p>
            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap">{{ $contact->message }}</p>
        </div>

        <div class="flex items-center justify-between">
            <p class="text-xs text-slate-400">
                Reçu le {{ $contact->created_at->format('d M Y à H:i') }}
                @if($contact->read_at)
                    · Lu le {{ $contact->read_at->format('d M Y à H:i') }}
                @endif
            </p>
            <div class="flex items-center gap-2">
                <a href="mailto:{{ $contact->email }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#0f1d57] px-4 py-2 text-xs font-bold text-white hover:bg-[#14256f] transition">
                    <flux:icon.envelope class="size-4" />
                    Répondre
                </a>
                <button wire:click="markUnread({{ $contact->id }})"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    <flux:icon.envelope class="size-4" />
                    Marquer non lu
                </button>
                <button wire:click="delete({{ $contact->id }})"
                        wire:confirm="Supprimer ce message ?"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-50 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-600 hover:text-white transition">
                    <flux:icon.trash class="size-4" />
                    Supprimer
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-left text-[0.65rem] font-extrabold uppercase tracking-widest text-slate-500">Statut</th>
                        <th class="px-6 py-4 text-left text-[0.65rem] font-extrabold uppercase tracking-widest text-slate-500">Nom</th>
                        <th class="px-6 py-4 text-left text-[0.65rem] font-extrabold uppercase tracking-widest text-slate-500">Email</th>
                        <th class="px-6 py-4 text-left text-[0.65rem] font-extrabold uppercase tracking-widest text-slate-500">Sujet</th>
                        <th class="px-6 py-4 text-left text-[0.65rem] font-extrabold uppercase tracking-widest text-slate-500">Date</th>
                        <th class="px-6 py-4 text-right text-[0.65rem] font-extrabold uppercase tracking-widest text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($contacts as $item)
                    <tr class="hover:bg-slate-50 transition cursor-pointer {{ !$item->is_read ? 'bg-violet-50/40' : '' }}"
                        wire:click="view({{ $item->id }})">
                        <td class="px-6 py-4">
                            @if(!$item->is_read)
                                <span class="inline-flex size-2.5 rounded-full bg-rose-500"></span>
                            @else
                                <span class="inline-flex size-2.5 rounded-full bg-slate-200"></span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-{{ !$item->is_read ? 'bold' : 'medium' }} text-slate-900">{{ $item->name }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600">{{ $item->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-600 max-w-xs truncate">{{ $item->subject ?: '—' }}</p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-xs text-slate-400">{{ $item->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4 text-right" wire:click.stop>
                            <div class="flex items-center justify-end gap-1.5">
                                <button wire:click="view({{ $item->id }})"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-violet-50 text-violet-600 transition hover:bg-violet-600 hover:text-white">
                                    <flux:icon.eye class="size-4" />
                                </button>
                                <button wire:click="delete({{ $item->id }})"
                                        wire:confirm="Supprimer ce message ?"
                                        class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white">
                                    <flux:icon.trash class="size-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center">
                            <flux:icon.inbox class="size-10 text-slate-200 mx-auto mb-3" />
                            <p class="text-sm font-semibold text-slate-500">Aucun message trouvé.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($contacts->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $contacts->links() }}
    </div>
    @endif

</div>
