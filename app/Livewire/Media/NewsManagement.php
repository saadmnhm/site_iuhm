<?php

namespace App\Livewire\Media;

use App\Models\Media\News;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class NewsManagement extends Component
{
    use WithPagination, WithFileUploads;

    public string $search          = '';
    public string $categoryFilter  = '';
    public bool   $showForm        = false;
    public bool   $editMode        = false;
    public ?int   $newsId          = null;

    public $title, $title_ar, $excerpt, $excerpt_ar, $content = '', $content_ar, $image, $file;
    public $category, $tags_input;
    public bool $is_published = false;

    protected $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'title_ar'     => 'nullable|string|max:255',
            'excerpt'      => 'nullable|string|max:500',
            'excerpt_ar'   => 'nullable|string|max:500',
            'content'      => 'required|string',
            'content_ar'   => 'nullable|string',
            'file'         => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'category'     => 'nullable|string|max:100',
            'tags_input'   => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ];
    }

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingCategoryFilter(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->editMode = false;
    }

    public function openEdit(int $id): void
    {
        $news              = News::findOrFail($id);
        $this->newsId      = $news->id;
        $this->title       = $news->title;
        $this->title_ar    = $news->title_ar;
        $this->excerpt     = $news->excerpt;
        $this->excerpt_ar  = $news->excerpt_ar;
        $this->content     = $news->content;
        $this->content_ar  = $news->content_ar;
        $this->image       = $news->image;
        $this->category    = $news->category;
        $this->tags_input  = $news->tags ? implode(', ', $news->tags) : '';
        $this->is_published= $news->is_published;
        $this->editMode    = true;
        $this->showForm    = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => $this->title,
            'title_ar'     => $this->title_ar ?: null,
            'excerpt'      => $this->excerpt ?: null,
            'excerpt_ar'   => $this->excerpt_ar ?: null,
            'content'      => $this->content,
            'content_ar'   => $this->content_ar ?: null,
            'category'     => $this->category ?: null,
            'tags'         => $this->tags_input ? array_map('trim', explode(',', $this->tags_input)) : null,
            'is_published' => $this->is_published,
        ];

        if ($this->is_published) {
            $data['published_at'] = now();
        }

        if ($this->file) {
            $filename = uniqid('news_') . '.' . $this->file->getClientOriginalExtension();
            $this->file->storeAs('news', $filename, 'uploads');
            $data['image'] = 'uploads/news/' . $filename;
        }

        if ($this->editMode) {
            News::findOrFail($this->newsId)->update($data);
            $this->dispatch('notify', message: 'Actualité mise à jour avec succès!', type: 'success');
        } else {
            $data['slug']      = News::generateSlug($this->title);
            $data['author_id'] = auth()->id();
            News::create($data);
            $this->dispatch('notify', message: 'Actualité créée avec succès!', type: 'success');
        }

        $this->resetForm();
    }

    public function togglePublish(int $id): void
    {
        $news = News::findOrFail($id);
        $news->update([
            'is_published' => !$news->is_published,
            'published_at' => !$news->is_published ? now() : $news->published_at,
        ]);
        $this->dispatch('notify', message: 'Statut mis à jour.', type: 'success');
    }

    public function delete(int $id): void
    {
        News::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Actualité supprimée.', type: 'success');
    }

    public function resetForm(): void
    {
        $this->reset(['newsId', 'title', 'title_ar', 'excerpt', 'excerpt_ar', 'content', 'content_ar', 'image', 'file', 'category', 'tags_input', 'is_published']);
        $this->content    = '';
        $this->is_published = false;
        $this->editMode   = false;
        $this->showForm   = false;
        $this->resetValidation();
        $this->resetPage();
    }

    public function render()
    {
        $query = News::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('excerpt', 'like', "%{$this->search}%");
            });
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        $newsItems = $query->latest()->paginate(15);

        $statistics = [
            'totalNews'     => News::count(),
            'publishedNews' => News::where('is_published', true)->count(),
            'draftNews'     => News::where('is_published', false)->count(),
        ];

        return view('livewire.media.news-management', compact('newsItems', 'statistics'))
            ->layout('layouts.app');
    }
}
