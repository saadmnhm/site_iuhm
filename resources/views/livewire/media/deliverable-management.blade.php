@push('head-scripts')
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.0/fonts/remixicon.css" rel="stylesheet" />
@endpush

<div>
@if($showForm)
<form wire:submit.prevent="save">
<div class="p-6 sm:p-8 bg-[#FAFAFC] min-h-screen">

    <div class="mb-8">
        <button type="button" wire:click="resetForm()"
                class="mb-4 inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-slate-500 hover:text-[#0B1528] transition-colors">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour aux livrables
        </button>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <span class="inline-flex rounded-full bg-amber-100 px-4 py-1.5 text-[0.7rem] font-extrabold uppercase tracking-widest text-amber-800 mb-3">
                    {{ $editMode ? 'Modification' : 'Nouveau livrable' }}
                </span>
                <h2 class="text-2xl font-bold text-[#0B1528] sm:text-3xl">
                    {{ $editMode ? 'Modifier le livrable' : 'Créer un livrable' }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" wire:click="resetForm()"
                        class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                    Annuler
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full bg-[#0B1528] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#162D5A] transition-colors shadow-md">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $editMode ? 'Mettre à jour' : 'Enregistrer' }}
                </button>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-amber-600 mb-3">Titre du livrable</label>
                <input type="text" wire:model="title" placeholder="Titre du livrable…"
                       class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-lg font-bold text-[#0B1528] placeholder:text-slate-300 placeholder:font-normal outline-none focus:border-[#0B1528] focus:bg-white focus:ring-2 focus:ring-[#0B1528]/10 transition">
                @error('title') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-amber-600 mb-3">Description</label>
                <textarea wire:model="description" rows="5" placeholder="Description du livrable…"
                          class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-sm text-slate-600 outline-none focus:border-[#0B1528] focus:bg-white focus:ring-2 focus:ring-[#0B1528]/10 resize-none transition leading-relaxed"></textarea>
                @error('description') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-5">
            {{-- File Upload --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-amber-600 mb-4">Fichier</p>
                @if($file && !$newFile)
                <div class="mb-4 flex items-center gap-3 rounded-xl bg-amber-50 px-4 py-3 border border-amber-100">
                    <flux:icon.document class="size-5 text-amber-600 flex-shrink-0" />
                    <span class="text-sm font-semibold text-amber-800 truncate flex-1">Fichier actuel</span>
                    <a href="{{ asset('storage/' . $file) }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-amber-700 hover:text-amber-900 underline">Voir</a>
                </div>
                @endif
                @if($newFile)
                <div class="mb-4 flex items-center gap-3 rounded-xl bg-emerald-50 px-4 py-3 border border-emerald-100">
                    <flux:icon.document-check class="size-5 text-emerald-600 flex-shrink-0" />
                    <span class="text-sm font-semibold text-emerald-800 truncate">{{ $newFile->getClientOriginalName() }}</span>
                </div>
                @endif
                <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 bg-[#FAFAFC] py-6 text-center hover:border-amber-400 hover:bg-amber-50 transition group">
                    <flux:icon.arrow-up-tray class="size-7 text-slate-300 group-hover:text-amber-500 mb-2 transition" />
                    <p class="text-sm font-bold text-slate-500">Télécharger un fichier</p>
                    <p class="mt-1 text-xs text-slate-400">PDF, DOC, DOCX, XLS, ZIP — max 5MB</p>
                    <input type="file" wire:model="newFile" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" class="hidden">
                </label>
                @error('newFile') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-amber-600 mb-4">Statut de livraison</p>
                <div class="space-y-2">
                    @foreach(['pending' => ['label' => 'En attente', 'color' => 'text-amber-600 bg-amber-50 border-amber-200'],
                               'completed' => ['label' => 'Complété', 'color' => 'text-emerald-700 bg-emerald-50 border-emerald-200'],
                               'overdue' => ['label' => 'En retard', 'color' => 'text-rose-700 bg-rose-50 border-rose-200']] as $val => $opt)
                    <label class="flex items-center gap-3 cursor-pointer rounded-xl border px-4 py-3 transition
                                  {{ $status === $val ? $opt['color'] : 'border-slate-100 bg-white hover:bg-slate-50' }}">
                        <input type="radio" wire:model="status" value="{{ $val }}" class="sr-only">
                        <span class="size-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                     {{ $status === $val ? 'border-current' : 'border-slate-300' }}">
                            @if($status === $val)
                            <span class="size-2 rounded-full bg-current"></span>
                            @endif
                        </span>
                        <span class="text-sm font-semibold">{{ $opt['label'] }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Publication --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-amber-600 mb-4">Publication</p>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" wire:click="$set('is_published', false)"
                            class="flex flex-col items-center gap-2 rounded-xl border-2 px-3 py-4 transition text-center {{ !$is_published ? 'border-[#0B1528] bg-[#0B1528] text-white' : 'border-slate-200 bg-slate-50 text-slate-500' }}">
                        <flux:icon.pencil-square class="size-5" />
                        <span class="text-xs font-extrabold uppercase">Brouillon</span>
                    </button>
                    <button type="button" wire:click="$set('is_published', true)"
                            class="flex flex-col items-center gap-2 rounded-xl border-2 px-3 py-4 transition text-center {{ $is_published ? 'border-amber-400 bg-amber-400 text-white' : 'border-slate-200 bg-slate-50 text-slate-500' }}">
                        <flux:icon.check-circle class="size-5" />
                        <span class="text-xs font-extrabold uppercase">Publié</span>
                    </button>
                </div>
            </div>

            {{-- Category & Due Date --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm space-y-4">
                <div>
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-amber-600 mb-2">Catégorie</p>
                    <input type="text" wire:model="category" placeholder="Ex: Rapport, Étude…"
                           class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-sm outline-none focus:border-[#0B1528] focus:ring-2 focus:ring-[#0B1528]/10 transition">
                </div>
                <div>
                    <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-amber-600 mb-2">Date d'échéance</p>
                    <input type="date" wire:model="due_date"
                           class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-sm outline-none focus:border-[#0B1528] focus:ring-2 focus:ring-[#0B1528]/10 transition">
                </div>
            </div>
        </div>
    </div>

</div>
</form>

@else
<div class="p-6 sm:p-8">

    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between mb-8">
        <div>
            <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-amber-600">CONTENT STUDIO</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Livrables</h2>
            <p class="mt-2 text-sm text-slate-600">Gérez et publiez les livrables du projet.</p>
        </div>
        <button type="button" wire:click="openCreate()"
                class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-[#14256f]">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau livrable
        </button>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 mb-3">
                <flux:icon.folder class="size-5 text-amber-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $totalDeliverables }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Total</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 mb-3">
                <flux:icon.check-circle class="size-5 text-emerald-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $publishedDeliverables }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Publiés</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 mb-3">
                <flux:icon.check-badge class="size-5 text-blue-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $completedDeliverables }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Complétés</p>
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row mb-6">
        <div class="relative flex-1">
            <flux:icon.magnifying-glass class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 size-4 text-slate-400" />
            <input type="text" wire:model.live.debounce="search" placeholder="Rechercher…"
                   class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm outline-none focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
        </div>
        <select wire:model.live="statusFilter" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57]">
            <option value="all">Tous</option>
            <option value="published">Publiés</option>
            <option value="draft">Brouillons</option>
        </select>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-[#04103A] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Titre</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Catégorie</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Statut livraison</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Publication</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($deliverables as $d)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-amber-50">
                                    <flux:icon.document class="size-5 text-amber-400" />
                                </div>
                                <span class="max-w-xs truncate text-sm font-semibold text-[#04103A]">{{ $d->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($d->category)
                            <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">{{ $d->category }}</span>
                            @else
                            <span class="text-slate-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusMap = ['pending' => ['bg-amber-100 text-amber-700', 'En attente'], 'completed' => ['bg-emerald-100 text-emerald-700', 'Complété'], 'overdue' => ['bg-rose-100 text-rose-700', 'En retard']];
                                [$cls, $lbl] = $statusMap[$d->status] ?? ['bg-slate-100 text-slate-600', $d->status];
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $cls }}">{{ $lbl }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1
                                {{ $d->is_published ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-amber-100 text-amber-700 ring-amber-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $d->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                {{ $d->is_published ? 'Publié' : 'Brouillon' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="openEdit({{ $d->id }})"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white">
                                    <flux:icon.pencil class="size-4" />
                                </button>
                                <button wire:click="togglePublish({{ $d->id }})"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full transition
                                               {{ $d->is_published ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white' }}">
                                    <flux:icon.{{ $d->is_published ? 'eye-slash' : 'eye' }} class="size-4" />
                                </button>
                                <button wire:click="delete({{ $d->id }})"
                                        wire:confirm="Supprimer ce livrable ?"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white">
                                    <flux:icon.trash class="size-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <flux:icon.folder class="size-10 text-slate-200 mx-auto mb-3" />
                            <p class="text-sm font-semibold text-slate-500">Aucun livrable trouvé.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">{{ $deliverables->firstItem() ?? 0 }}–{{ $deliverables->lastItem() ?? 0 }} sur {{ $deliverables->total() }}</p>
            <div>{{ $deliverables->links() }}</div>
        </div>
    </div>

</div>
@endif
</div>
