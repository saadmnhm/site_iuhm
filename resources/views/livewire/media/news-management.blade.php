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
    .img-toolbar-news { display: none; position: absolute; background: #1e293b; border-radius: 0.5rem; padding: 4px; gap: 4px; z-index: 100; flex-wrap: wrap; box-shadow: 0 4px 16px rgba(0,0,0,0.25); }
    .img-toolbar-news.active { display: flex; }
    .img-toolbar-news button { background: transparent; border: none; color: #e2e8f0; cursor: pointer; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; white-space: nowrap; }
    .img-toolbar-news button:hover { background: rgba(255,255,255,0.1); }
    .img-toolbar-news input[type=range] { width: 90px; accent-color: #60a5fa; }
    .ql-editor img.img-selected { outline: 2px solid #60a5fa; outline-offset: 2px; }
    .news-editor-wrapper { resize: vertical; overflow: hidden; min-height: 450px; border-radius: 0 0 0.75rem 0.75rem; }
</style>
<script>
document.addEventListener('alpine:init', () => {
    if (window.__newsEditorRegistered) return;
    window.__newsEditorRegistered = true;
    Alpine.data('newsEditor', () => ({
        quill: null,
        _imgToolbar: null,
        _selectedImg: null,
        init() {
            const wire = this.$wire;
            const uploadUrl = '{{ route('admin.editor.image-upload') }}';
            const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';

            this.$nextTick(() => {
                this.quill = new Quill(this.$refs.newsEditorContainer, {
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
            tb.className = 'img-toolbar-news';
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
            Retour aux actualités
        </button>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <span class="inline-flex rounded-full bg-blue-100 px-4 py-1.5 text-[0.7rem] font-extrabold uppercase tracking-widest text-blue-800 mb-3">
                    {{ $editMode ? 'Modification' : 'Nouvelle actualité' }}
                </span>
                <h2 class="text-2xl font-bold text-[#0B1528] sm:text-3xl">
                    {{ $editMode ? 'Modifier l\'actualité' : 'Créer une actualité' }}
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
                <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-blue-600 mb-3">Titre</label>
                <input type="text" wire:model="title" placeholder="Titre de l'actualité…"
                       class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-lg font-bold text-[#0B1528] placeholder:text-slate-300 placeholder:font-normal outline-none focus:border-[#0B1528] focus:bg-white focus:ring-2 focus:ring-[#0B1528]/10 transition">
                @error('title') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <label class="block text-[0.65rem] font-extrabold uppercase tracking-widest text-blue-600 mb-3">Résumé</label>
                <textarea wire:model="excerpt" rows="3" placeholder="Résumé court…"
                          class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-sm text-slate-600 outline-none focus:border-[#0B1528] focus:bg-white focus:ring-2 focus:ring-[#0B1528]/10 resize-none transition"></textarea>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="size-2.5 rounded-full bg-blue-400"></span>
                        <span class="text-[0.65rem] font-extrabold uppercase tracking-widest text-blue-600">Contenu</span>
                    </div>
                    <span class="text-[0.6rem] text-slate-400 font-medium">Cliquez sur une image pour la redimensionner · Glissez le bas pour agrandir</span>
                </div>
                <div x-data="newsEditor" wire:ignore class="news-editor-wrapper">
                    <div x-ref="newsEditorContainer"></div>
                </div>
                @error('content') <p class="px-6 pb-4 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="space-y-5">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-blue-600 mb-4">Image</p>
                @if($image && !$file)
                <div class="mb-4 overflow-hidden rounded-xl h-36 w-full">
                    <img src="{{ \Illuminate\Support\Str::startsWith($image, ['http','https']) ? $image : asset($image) }}" class="w-full h-full object-cover" alt="">
                </div>
                @endif
                @if($file)
                <div class="mb-4 overflow-hidden rounded-xl h-36 w-full">
                    <img src="{{ $file->temporaryUrl() }}" class="w-full h-full object-cover" alt="">
                </div>
                @endif
                <label class="flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 bg-[#FAFAFC] py-6 text-center hover:border-blue-400 hover:bg-blue-50 transition group">
                    <flux:icon.photo class="size-7 text-slate-300 group-hover:text-blue-500 mb-2 transition" />
                    <p class="text-sm font-bold text-slate-500">Choisir une image</p>
                    <input type="file" wire:model="file" accept="image/*" class="hidden">
                </label>
                @error('file') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-blue-600 mb-4">Statut</p>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" wire:click="$set('is_published', false)"
                            class="flex flex-col items-center gap-2 rounded-xl border-2 px-3 py-4 transition text-center {{ !$is_published ? 'border-[#0B1528] bg-[#0B1528] text-white' : 'border-slate-200 bg-slate-50 text-slate-500' }}">
                        <flux:icon.pencil-square class="size-5" />
                        <span class="text-xs font-extrabold uppercase">Brouillon</span>
                    </button>
                    <button type="button" wire:click="$set('is_published', true)"
                            class="flex flex-col items-center gap-2 rounded-xl border-2 px-3 py-4 transition text-center {{ $is_published ? 'border-blue-400 bg-blue-400 text-white' : 'border-slate-200 bg-slate-50 text-slate-500' }}">
                        <flux:icon.check-circle class="size-5" />
                        <span class="text-xs font-extrabold uppercase">Publié</span>
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-blue-600 mb-3">Catégorie</p>
                <input type="text" wire:model="category" placeholder="Ex: Environnement"
                       class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-sm outline-none focus:border-[#0B1528] focus:ring-2 focus:ring-[#0B1528]/10 transition">
            </div>

            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
                <p class="text-[0.65rem] font-extrabold uppercase tracking-widest text-blue-600 mb-3">Tags</p>
                <input type="text" wire:model="tags_input" placeholder="tag1, tag2…"
                       class="w-full rounded-xl border border-slate-100 bg-[#FAFAFC] px-4 py-3 text-sm outline-none focus:border-[#0B1528] focus:ring-2 focus:ring-[#0B1528]/10 transition">
                <p class="mt-1 text-[0.7rem] text-slate-400">Séparés par des virgules</p>
            </div>
        </div>
    </div>

</div>
</form>

@else
<div class="p-6 sm:p-8">

    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between mb-8">
        <div>
            <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-blue-600">CONTENT STUDIO</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight text-slate-900">Actualités</h2>
            <p class="mt-2 text-sm text-slate-600">Publiez et gérez les actualités de l'Initiative Urbaine.</p>
        </div>
        <button type="button" wire:click="openCreate()"
                class="inline-flex items-center gap-2 self-start rounded-full bg-[#0f1d57] px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-[#14256f]">
            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle actualité
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 mb-3">
                <flux:icon.rss class="size-5 text-blue-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $statistics['totalNews'] }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Total</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 mb-3">
                <flux:icon.check-circle class="size-5 text-emerald-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $statistics['publishedNews'] }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Publiées</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 mb-3">
                <flux:icon.pencil-square class="size-5 text-amber-600" />
            </div>
            <p class="text-2xl font-black text-slate-900">{{ $statistics['draftNews'] }}</p>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-0.5">Brouillons</p>
        </div>
    </div>

    {{-- Search --}}
    <div class="flex flex-col gap-3 sm:flex-row mb-6">
        <div class="relative flex-1">
            <flux:icon.magnifying-glass class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 size-4 text-slate-400" />
            <input type="text" wire:model.live.debounce="search" placeholder="Rechercher…"
                   class="w-full rounded-xl border border-slate-200 bg-white py-3 pl-11 pr-4 text-sm outline-none focus:border-[#0f1d57] focus:ring-2 focus:ring-[#0f1d57]/10">
        </div>
        <input type="text" wire:model.live="categoryFilter" placeholder="Filtrer par catégorie…"
               class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[#0f1d57]">
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-[#04103A] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Titre</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Catégorie</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase">Statut</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($newsItems as $news)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($news->image)
                                <img src="{{ \Illuminate\Support\Str::startsWith($news->image, ['http','https']) ? $news->image : asset($news->image) }}"
                                     class="h-10 w-10 flex-shrink-0 rounded-lg object-cover" alt="">
                                @else
                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-blue-50">
                                    <flux:icon.rss class="size-5 text-blue-300" />
                                </div>
                                @endif
                                <span class="max-w-xs truncate text-sm font-semibold text-[#04103A]">{{ $news->title }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($news->category)
                            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $news->category }}</span>
                            @else
                            <span class="text-slate-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ $news->published_at?->format('d M Y') ?? $news->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1
                                {{ $news->is_published ? 'bg-emerald-100 text-emerald-700 ring-emerald-200' : 'bg-amber-100 text-amber-700 ring-amber-200' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $news->is_published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                {{ $news->is_published ? 'Publiée' : 'Brouillon' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="openEdit({{ $news->id }})"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#0f1d57]/5 text-[#0f1d57] transition hover:bg-[#0f1d57] hover:text-white" title="Modifier">
                                    <flux:icon.pencil class="size-4" />
                                </button>
                                <button wire:click="togglePublish({{ $news->id }})"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full transition
                                               {{ $news->is_published ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white' }}">
                                    <flux:icon.{{ $news->is_published ? 'eye-slash' : 'eye' }} class="size-4" />
                                </button>
                                <button wire:click="delete({{ $news->id }})"
                                        wire:confirm="Supprimer cette actualité ?"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-rose-50 text-rose-600 transition hover:bg-rose-600 hover:text-white">
                                    <flux:icon.trash class="size-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-14 text-center">
                            <flux:icon.rss class="size-10 text-slate-200 mx-auto mb-3" />
                            <p class="text-sm font-semibold text-slate-500">Aucune actualité trouvée.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col gap-3 border-t border-slate-200 bg-white px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">{{ $newsItems->firstItem() ?? 0 }}–{{ $newsItems->lastItem() ?? 0 }} sur {{ $newsItems->total() }}</p>
            <div>{{ $newsItems->links() }}</div>
        </div>
    </div>

</div>
@endif
</div>
