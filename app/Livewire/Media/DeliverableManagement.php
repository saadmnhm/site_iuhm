<?php

namespace App\Livewire\Media;

use App\Models\Media\Deliverable;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class DeliverableManagement extends Component
{
    use WithPagination, WithFileUploads;

    public string $search        = '';
    public string $statusFilter  = 'all';
    public bool   $showForm      = false;
    public bool   $editMode      = false;
    public ?int   $deliverableId = null;

    public $title, $title_ar, $description, $description_ar, $file, $newFile;
    public $category, $due_date;
    public string $status      = 'pending';
    public bool $is_published  = false;

    protected $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'newFile'        => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:5120',
            'category'       => 'nullable|string|max:100',
            'status'         => 'in:pending,completed,overdue',
            'due_date'       => 'nullable|date',
            'is_published'   => 'boolean',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editMode = false;
    }

    public function openEdit(int $id): void
    {
        $d                   = Deliverable::findOrFail($id);
        $this->deliverableId = $d->id;
        $this->title         = $d->title;
        $this->title_ar      = $d->title_ar;
        $this->description   = $d->description;
        $this->description_ar= $d->description_ar;
        $this->file          = $d->file_url;
        $this->category      = $d->category;
        $this->status        = $d->status;
        $this->due_date      = $d->due_date?->format('Y-m-d');
        $this->is_published  = $d->is_published;
        $this->editMode      = true;
        $this->showForm      = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'          => $this->title,
            'title_ar'       => $this->title_ar ?: null,
            'description'    => $this->description ?: null,
            'description_ar' => $this->description_ar ?: null,
            'category'       => $this->category ?: null,
            'status'         => $this->status,
            'due_date'       => $this->due_date ? now()->parse($this->due_date) : null,
            'is_published'   => $this->is_published,
        ];

        if ($this->is_published) {
            $data['published_at'] = now();
        }

        if ($this->newFile) {
            $filename = uniqid('deliverable_') . '.' . $this->newFile->getClientOriginalExtension();
            $this->newFile->storeAs('deliverables', $filename, 'public');
            $data['file_url']  = 'deliverables/' . $filename;
            $data['file_type'] = $this->newFile->getClientOriginalExtension();
        }

        if ($this->editMode) {
            Deliverable::findOrFail($this->deliverableId)->update($data);
            $this->dispatch('notify', message: 'Livrable mis à jour avec succès!', type: 'success');
        } else {
            $data['slug']      = Deliverable::generateSlug($this->title);
            $data['author_id'] = auth()->id();
            Deliverable::create($data);
            $this->dispatch('notify', message: 'Livrable créé avec succès!', type: 'success');
        }

        $this->resetForm();
    }

    public function togglePublish(int $id): void
    {
        $d = Deliverable::findOrFail($id);
        $d->update([
            'is_published' => !$d->is_published,
            'published_at' => !$d->is_published ? now() : $d->published_at,
        ]);
        $this->dispatch('notify', message: 'Statut mis à jour.', type: 'success');
    }

    public function delete(int $id): void
    {
        Deliverable::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Livrable supprimé.', type: 'success');
    }

    public function resetForm(): void
    {
        $this->reset(['deliverableId', 'title', 'title_ar', 'description', 'description_ar', 'file', 'newFile', 'category', 'due_date', 'is_published']);
        $this->status      = 'pending';
        $this->is_published= false;
        $this->editMode    = false;
        $this->showForm    = false;
        $this->resetValidation();
        $this->resetPage();
    }

    public function render()
    {
        $query = Deliverable::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhere('category', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'published') {
            $query->where('is_published', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        $deliverables = $query->latest()->paginate(15);

        return view('livewire.media.deliverable-management', [
            'deliverables'          => $deliverables,
            'totalDeliverables'     => Deliverable::count(),
            'publishedDeliverables' => Deliverable::where('is_published', true)->count(),
            'completedDeliverables' => Deliverable::where('status', 'completed')->count(),
        ])->layout('layouts.app');
    }
}
