@push('head-scripts')
<link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.0/fonts/remixicon.css" rel="stylesheet" />
<style>
    .ql-toolbar.ql-snow { background: #f8fafc; border: none !important; border-bottom: 1px solid #e2e8f0 !important; border-radius: 0.75rem 0.75rem 0 0; }
    .ql-container.ql-snow { border: none !important; }
    .ql-editor { min-height: 400px; padding: 1.5rem; line-height: 1.8; color: #4A5568; font-size: 15px; }
    .ql-editor blockquote { background: #F0F2F5; border-left: 4px solid #82E682 !important; padding: 1rem 1.5rem; margin: 1.5rem 0; }
    .ql-editor img { max-width: 100%; border-radius: 0.5rem; cursor: pointer; display: inline-block; }
    .ql-editor img.img-float-left  { float: left;  margin: 0 1.5rem 1rem 0; }
    .ql-editor img.img-float-right { float: right; margin: 0 0 1rem 1.5rem; }
    .ql-editor img.img-full        { width: 100%; display: block; }
    .img-toolbar-nl { display: none; position: absolute; background: #1e293b; border-radius: 0.5rem; padding: 4px; gap: 4px; z-index: 100; flex-wrap: wrap; box-shadow: 0 4px 16px rgba(0,0,0,0.25); }
    .img-toolbar-nl.active { display: flex; }
    .img-toolbar-nl button { background: transparent; border: none; color: #e2e8f0; cursor: pointer; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; white-space: nowrap; }
    .img-toolbar-nl button:hover { background: rgba(255,255,255,0.1); }
    .img-toolbar-nl input[type=range] { width: 90px; accent-color: #34d399; }
    .ql-editor img.img-selected { outline: 2px solid #34d399; outline-offset: 2px; }
    .nl-editor-wrapper { resize: vertical; overflow: hidden; min-height: 450px; border-radius: 0 0 0.75rem 0.75rem; }
</style>
<script>
document.addEventListener('alpine:init', () => {
    if (window.__nlEditorRegistered) return;
    window.__nlEditorRegistered = true;
    Alpine.data('nlEditor', () => ({
        quill: null,
        _imgToolbar: null,
        _selectedImg: null,
        init() {
            const wire = this.$wire;
            const uploadUrl = '{{ route('admin.editor.image-upload') }}';
            const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';

            this.$nextTick(() => {
                this.quill = new Quill(this.$refs.nlEditorContainer, {
                    theme: 'snow',
                    modules: {
                        toolbar: {
                            container: [
                                ['bold','italic','underline','strike'],
                                [{ header: [1,2,3,false] }],
                                [{ size: ['small', false, 'large', 'huge'] }],
                                [{ color: [] }, { background: [] }],
                                [{ align: [] }],
                                ['blockquote','code-block'],
                                [{ list: 'ordered' }, { list: 'bullet' }],
                                ['link', 'image', 'video'],
                                ['clean']
                            ],
                            handlers: {
                                image: () => this._handleImageInsert(uploadUrl, csrf)
                            }
                        }
                    }
                });
                const raw = wire.get('content');
                if (raw) this.quill.root.innerHTML = raw;
                this.quill.on('text-change', () => wire.set('content', this.quill.root.innerHTML));
                wire.$watch('content', (val) => {
                    if (val !== undefined && val !== this.quill.root.innerHTML) this.quill.root.innerHTML = val || '';
                });
                this._setupImageInteractions();
            });
        },

        _handleImageInsert(uploadUrl, csrf) {
            const input = document.createElement('input');
            input.type = 'file'; input.accept = 'image/*'; input.multiple = true;
            input.click();
            input.onchange = async () => {
                for (const file of Array.from(input.files)) {
                    const fd = new FormData(); fd.append('image', file);
                    try {
                        const resp = await fetch(uploadUrl, { method:'POST', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}, body:fd });
                        if (!resp.ok) continue;
                        const data = await resp.json();
                        const range = this.quill.getSelection(true);
                        this.quill.insertEmbed(range.index, 'image', data.url);
                        this.quill.setSelection(range.index + 1);
                    } catch(e) { console.error(e); }
                }
            };
        },

        _setupImageInteractions() {
            const tb = document.createElement('div');
            tb.className = 'img-toolbar-nl';
            tb.innerHTML = `
                <button data-action="float-left">← Gauche</button>
                <button data-action="float-right">Droite →</button>
                <button data-action="full-width">↔ Plein</button>
                <button data-action="normal">Normal</button>
                <span style="color:#94a3b8;padding:2px 4px;font-size:11px">Taille:</span>
                <input type="range" min="10" max="100" value="100" data-action="resize">
                <button data-action="replace">🔄 Remplacer</button>
                <button data-action="remove" style="color:#f87171">✕</button>
            `;
            document.body.appendChild(tb);
            this._imgToolbar = tb;

            this.quill.root.addEventListener('click', (e) => {
                if (e.target.tagName === 'IMG') { this._selectImage(e.target, tb); }
                else { this._deselectImage(tb); }
            });

            tb.addEventListener('mousedown', (e) => {
                e.preventDefault();
                const action = e.target.dataset.action;
                if (!action || !this._selectedImg) return;
                const img = this._selectedImg;
                if (action === 'float-left') { img.className = 'img-float-left img-selected'; }
                else if (action === 'float-right') { img.className = 'img-float-right img-selected'; }
                else if (action === 'full-width') { img.className = 'img-full img-selected'; img.style.width = ''; }
                else if (action === 'normal') { img.className = 'img-selected'; img.style.width = ''; }
                else if (action === 'remove') { img.remove(); this._deselectImage(tb); }
                else if (action === 'replace') {
                    const inp = document.createElement('input'); inp.type='file'; inp.accept='image/*'; inp.click();
                    inp.onchange = async () => {
                        const fd = new FormData(); fd.append('image', inp.files[0]);
                        const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
                        const resp = await fetch('{{ route('admin.editor.image-upload') }}', { method:'POST', headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}, body:fd });
                        if (resp.ok) { const d = await resp.json(); img.src = d.url; }
                    };
                }
                this.quill.update();
                this.$wire.set('content', this.quill.root.innerHTML);
            });

            tb.addEventListener('input', (e) => {
                if (e.target.dataset.action === 'resize' && this._selectedImg) {
                    this._selectedImg.style.width = e.target.value + '%';
                    this._repositionToolbar(this._selectedImg, tb);
                }
            });
        },

        _selectImage(img, tb) {
            if (this._selectedImg) this._selectedImg.classList.remove('img-selected');
            this._selectedImg = img; img.classList.add('img-selected');
            const slider = tb.querySelector('[data-action=resize]');
            if (slider) slider.value = img.style.width ? parseInt(img.style.width) : 100;
            tb.classList.add('active');
            this._repositionToolbar(img, tb);
        },
        _deselectImage(tb) {
            if (this._selectedImg) this._selectedImg.classList.remove('img-selected');
            this._selectedImg = null; tb.classList.remove('active');
        },
        _repositionToolbar(img, tb) {
            const rect = img.getBoundingClientRect();
            tb.style.top = (window.scrollY + rect.top - tb.offsetHeight - 8) + 'px';
            tb.style.left = Math.max(8, rect.left) + 'px';
            tb.style.position = 'absolute';
        },
        destroy() { if (this._imgToolbar) this._imgToolbar.remove(); this.quill = null; },
    }));
});
</script>
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
            Retour aux infolettres
        </button>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <span class="inline-flex rounded-full bg-emerald-100 px-4 py-1.5 text-[0.7rem] font-extrabold uppercase tracking-widest text-emerald-800 mb-3">
                    {{ $editMode ? 'Modification' : 'Nouvelle infolettre' }}
                </span>
                <h2 class="text-2xl font-bold text-[#0B1528] sm:text-3xl">
                    {{ $editMode ? 'Modifier l\'infolettre' : 'Créer une infolettre' }}
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
                <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-emerald-600 mb-3">Titre</label>
                <input type="text" wire:model="title" placeholder="Titre de l'infolettre…"
                       class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-lg font-bold text-[#0B1528] placeholder:text-slate-300 placeholder:font-normal outline-none focus:border-[#0B1528] focus:bg-white focus:ring-2 focus:ring-[#0B1528]/10 transition">
                @error('title') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="size-2.5 rounded-full bg-emerald-400"></span>
                        <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-emerald-600">Contenu de l'infolettre</span>
                    </div>
                    <span class="text-[0.6rem] text-slate-400 font-medium">Cliquez sur une image pour la redimensionner · Glissez le bas pour agrandir</span>
                </div>
                <div x-data="nlEditor" wire:ignore class="nl-editor-wrapper">
                    <div x-ref="nlEditorContainer"></div>
                </div>
                @error('content') <p class="px-6 pb-4 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-5">
            {{-- Featured Image --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-emerald-600 mb-4">Image</p>
                @if($featuredImage && !$newFeaturedImage)
                <div class="mb-4 overflow-hidden rounded-xl h-36 w-full">
                    <img src="{{ \Illuminate\Support\Str::startsWith($featuredImage, ['http','https']) ? $featuredImage : asset($featuredImage) }}" class="w-full h-full object-cover" alt="">
                </div>
                @endif
                @if($newFeaturedImage)
                <div class="mb-4 overflow-hidden rounded-xl h-36 w-full">
                    <img src="{{ $newFeaturedImage->temporaryUrl() }}" class="w-full h-full object-cover" alt="">
                </div>
                @endif
                <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 bg-[#FAFAFC] py-6 text-center hover:border-emerald-400 hover:bg-emerald-50 transition group">
                    <flux:icon.photo class="size-7 text-slate-300 group-hover:text-emerald-500 mb-2 transition" />
                    <p class="text-sm font-bold text-slate-500">Choisir une image</p>
                    <input type="file" wire:model="newFeaturedImage" accept="image/*" class="hidden">
                </label>
                @error('newFeaturedImage') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            {{-- Issue Number --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-emerald-600 mb-3">Numéro d'édition</p>
                <input type="number" wire:model="issueNumber" placeholder="Ex: 12"
                       class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-sm font-semibold outline-none focus:border-[#0B1528] focus:ring-2 focus:ring-[#0B1528]/10 transition">
                @error('issueNumber') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-emerald-600 mb-4">Statut</p>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" wire:click="$set('is_published', false)"
                            class="flex flex-col items-center gap-2 rounded-xl border-2 px-3 py-4 transition text-center {{ !$is_published ? 'border-[#0B1528] bg-[#0B1528] text-white' : 'border-slate-200 bg-slate-50 text-slate-500' }}">
                        <flux:icon.pencil-square class="size-5" />
                        <span class="text-xs font-extrabold uppercase">Brouillon</span>
                    </button>
                    <button type="button" wire:click="$set('is_published', true)"
                            class="flex flex-col items-center gap-2 rounded-xl border-2 px-3 py-4 transition text-center {{ $is_published ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-200 bg-slate-50 text-slate-500' }}">
                        <flux:icon.check-circle class="size-5" />
                        <span class="text-xs font-extrabold uppercase">Publiée</span>
                    </button>
                </div>
            </div>

            {{-- Sent At --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-emerald-600 mb-3">Date d'envoi</p>
                <input type="datetime-local" wire:model="sent_at"
                       class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-sm outline-none focus:border-[#0B1528] focus:ring-2 focus:ring-[#0B1528]/10 transition">
            </div>
        </div>
    </div>

</div>
</form>

@else
<div class="p-6 sm:p-8">

    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between mb-8">
        <div>
            <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-emerald-600">CONTENT STUDIO</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Infolettres</h2>
            <p class="mt-2 text-sm text-slate-600">Créez et gérez les newsletters de l'Initiative Urbaine.</p>
        </div>
        <button type="button" wire:click="openCreate()"
                class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-[#14256f]">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle infolettre
        </button>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 mb-3">
                <flux:icon.mail class="size-5 text-emerald-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $totalNewsletters }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Total</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 mb-3">
                <flux:icon.paper-airplane class="size-5 text-blue-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $publishedNewsletters }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Publiées</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 mb-3">
                <flux:icon.check-badge class="size-5 text-purple-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $sentNewsletters }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Envoyées</p>
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row mb-6">
        <div class="relative flex-1">
            <flux:icon.magnifying-glass class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 size-4 text-slate-400" />
            <input type="text" wire:model.live.debounce="search" placeholder="Rechercher…"
                   class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm outline-none focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
        </div>
        <select wire:model.live="statusFilter" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57]">
            <option value="all">Toutes</option>
            <option value="published">Publiées</option>
            <option value="draft">Brouillons</option>
        </select>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($newsletters as $nl)
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm hover:shadow-md transition group">
            @if($nl->featured_image)
            <div class="overflow-hidden rounded-xl h-32 mb-4">
                <img src="{{ \Illuminate\Support\Str::startsWith($nl->featured_image, ['http','https']) ? $nl->featured_image : asset($nl->featured_image) }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="">
            </div>
            @else
            <div class="flex items-center justify-center rounded-xl h-32 mb-4 bg-emerald-50">
                <flux:icon.mail class="size-10 text-emerald-200" />
            </div>
            @endif

            <div class="flex items-start justify-between gap-2 mb-2">
                @if($nl->issue_number)
                <span class="text-xs font-bold text-slate-400">#{{ $nl->issue_number }}</span>
                @endif
                @if($nl->is_published)
                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">Publiée</span>
                @else
                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">Brouillon</span>
                @endif
            </div>

            <h4 class="text-sm font-semibold text-slate-900 line-clamp-2 mb-1">{{ $nl->title }}</h4>
            <p class="text-xs text-slate-400 mb-4">{{ $nl->created_at->format('d M Y') }}</p>

            <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                <button wire:click="openEdit({{ $nl->id }})"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white">
                    <flux:icon.pencil class="size-4" />
                </button>
                <button wire:click="togglePublish({{ $nl->id }})"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full transition
                               {{ $nl->is_published ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white' }}">
                    <flux:icon.{{ $nl->is_published ? 'eye-slash' : 'eye' }} class="size-4" />
                </button>
                <button wire:click="delete({{ $nl->id }})"
                        wire:confirm="Supprimer cette infolettre ?"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white">
                    <flux:icon.trash class="size-4" />
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-3 rounded-2xl border border-dashed border-slate-200 bg-white p-14 text-center">
            <flux:icon.mail class="size-10 text-slate-200 mx-auto mb-3" />
            <p class="text-sm font-semibold text-slate-500">Aucune infolettre trouvée.</p>
        </div>
        @endforelse
    </div>

    @if($newsletters->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $newsletters->links() }}
    </div>
    @endif

</div>
@endif
</div>
