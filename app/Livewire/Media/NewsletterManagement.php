<?php

namespace App\Livewire\Media;

use App\Models\Media\Newsletter;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class NewsletterManagement extends Component
{
    use WithPagination, WithFileUploads;

    public string $search        = '';
    public string $statusFilter  = 'all';
    public bool   $showForm      = false;
    public bool   $editMode      = false;
    public ?int   $newsletterId  = null;

    public $title, $title_ar, $content = '', $content_ar, $featuredImage, $newFeaturedImage;
    public $issueNumber, $sent_at;
    public bool $is_published = false;

    protected $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        $uniqueRule = $this->editMode
            ? 'nullable|integer|unique:iuhm.newsletters,issue_number,' . $this->newsletterId
            : 'nullable|integer|unique:iuhm.newsletters,issue_number';

        return [
            'title'            => 'required|string|max:255',
            'title_ar'         => 'nullable|string|max:255',
            'content'          => 'required|string',
            'content_ar'       => 'nullable|string',
            'newFeaturedImage'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'issueNumber'      => $uniqueRule,
            'is_published'     => 'boolean',
            'sent_at'          => 'nullable|date',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->issueNumber = (Newsletter::max('issue_number') ?? 0) + 1;
        $this->showForm    = true;
        $this->editMode    = false;
    }

    public function openEdit(int $id): void
    {
        $nl                  = Newsletter::findOrFail($id);
        $this->newsletterId  = $nl->id;
        $this->title         = $nl->title;
        $this->title_ar      = $nl->title_ar;
        $this->content       = $nl->content;
        $this->content_ar    = $nl->content_ar;
        $this->featuredImage = $nl->featured_image;
        $this->issueNumber   = $nl->issue_number;
        $this->is_published  = $nl->is_published;
        $this->sent_at       = $nl->sent_at?->format('Y-m-d H:i');
        $this->editMode      = true;
        $this->showForm      = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => $this->title,
            'title_ar'     => $this->title_ar ?: null,
            'content'      => $this->content,
            'content_ar'   => $this->content_ar ?: null,
            'issue_number' => $this->issueNumber,
            'is_published' => $this->is_published,
        ];

        if ($this->is_published) {
            $data['published_at'] = now();
        }

        if ($this->sent_at) {
            $data['sent_at'] = now()->parse($this->sent_at);
        }

        if ($this->newFeaturedImage) {
            $filename = uniqid('newsletter_') . '.' . $this->newFeaturedImage->getClientOriginalExtension();
            $this->newFeaturedImage->storeAs('newsletters', $filename, 'uploads');
            $data['featured_image'] = 'uploads/newsletters/' . $filename;
        }

        if ($this->editMode) {
            Newsletter::findOrFail($this->newsletterId)->update($data);
            $this->dispatch('notify', message: 'Infolettre mise à jour avec succès!', type: 'success');
        } else {
            $data['slug']      = Newsletter::generateSlug($this->title);
            $data['author_id'] = auth()->id();
            Newsletter::create($data);
            $this->dispatch('notify', message: 'Infolettre créée avec succès!', type: 'success');
        }

        $this->resetForm();
    }

    public function togglePublish(int $id): void
    {
        $nl = Newsletter::findOrFail($id);
        $nl->update([
            'is_published' => !$nl->is_published,
            'published_at' => !$nl->is_published ? now() : $nl->published_at,
        ]);
        $this->dispatch('notify', message: 'Statut mis à jour.', type: 'success');
    }

    public function delete(int $id): void
    {
        Newsletter::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Infolettre supprimée.', type: 'success');
    }

    public function resetForm(): void
    {
        $this->reset(['newsletterId', 'title', 'title_ar', 'content', 'content_ar', 'featuredImage', 'newFeaturedImage', 'is_published', 'sent_at', 'issueNumber']);
        $this->content    = '';
        $this->is_published = false;
        $this->editMode   = false;
        $this->showForm   = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = Newsletter::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'published') {
            $query->where('is_published', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        $newsletters = $query->latest()->paginate(15);

        return view('livewire.media.newsletter-management', [
            'newsletters'          => $newsletters,
            'totalNewsletters'     => Newsletter::count(),
            'publishedNewsletters' => Newsletter::where('is_published', true)->count(),
            'sentNewsletters'      => Newsletter::whereNotNull('sent_at')->count(),
        ])->layout('layouts.app');
    }
}
