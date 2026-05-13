<?php

namespace App\Livewire\Media;

use App\Models\Media\BlogPost;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class BlogManagement extends Component
{
    use WithPagination, WithFileUploads;

    public string $search       = '';
    public string $statusFilter = 'all';

    public bool $editMode  = false;
    public ?int $postId    = null;

    // Form fields
    public $title, $title_ar, $excerpt, $content = '', $image, $newImage;
    public $category, $tags_input;
    public bool $is_published = false;
    public bool $showForm     = false;

    protected $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'title_ar'     => 'nullable|string|max:255',
            'excerpt'      => 'nullable|string|max:500',
            'content'      => 'required|string',
            'newImage'     => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'category'     => 'nullable|string|max:100',
            'tags_input'   => 'nullable|string|max:500',
            'is_published' => 'boolean',
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
        $post            = BlogPost::findOrFail($id);
        $this->postId    = $post->id;
        $this->title     = $post->title;
        $this->title_ar  = $post->title_ar;
        $this->excerpt   = $post->excerpt;
        $this->content   = $post->content;
        $this->image     = $post->image;
        $this->category  = $post->category;
        $this->tags_input= $post->tags ? implode(', ', $post->tags) : '';
        $this->is_published = $post->is_published;
        $this->editMode  = true;
        $this->showForm  = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => $this->title,
            'title_ar'     => $this->title_ar ?: null,
            'excerpt'      => $this->excerpt ?: null,
            'content'      => $this->content,
            'category'     => $this->category ?: null,
            'tags'         => $this->tags_input ? array_map('trim', explode(',', $this->tags_input)) : null,
            'is_published' => $this->is_published,
        ];

        if ($this->is_published) {
            $data['published_at'] = now();
        }

        if ($this->newImage) {
            $filename = uniqid('blog_') . '.' . $this->newImage->getClientOriginalExtension();
            $this->newImage->storeAs('blog', $filename, 'uploads');
            $data['image'] = 'uploads/blog/' . $filename;
        }

        if ($this->editMode) {
            BlogPost::findOrFail($this->postId)->update($data);
            $this->dispatch('notify', message: 'Article mis à jour avec succès!', type: 'success');
        } else {
            $data['slug']      = BlogPost::generateSlug($this->title);
            $data['author_id'] = auth()->id();
            BlogPost::create($data);
            $this->dispatch('notify', message: 'Article créé avec succès!', type: 'success');
        }

        $this->resetForm();
    }

    public function togglePublish(int $id): void
    {
        $post = BlogPost::findOrFail($id);
        $post->update([
            'is_published' => !$post->is_published,
            'published_at' => !$post->is_published ? now() : $post->published_at,
        ]);
        $this->dispatch('notify', message: 'Statut mis à jour.', type: 'success');
    }

    public function delete(int $id): void
    {
        BlogPost::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Article supprimé.', type: 'success');
    }

    public function resetForm(): void
    {
        $this->reset(['postId', 'title', 'title_ar', 'excerpt', 'content', 'image', 'newImage', 'category', 'tags_input', 'is_published']);
        $this->content    = '';
        $this->is_published = false;
        $this->editMode   = false;
        $this->showForm   = false;
        $this->resetValidation();
    }

    public function render()
    {
        $query = BlogPost::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('excerpt', 'like', "%{$this->search}%")
                  ->orWhere('category', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'published') {
            $query->where('is_published', true);
        } elseif ($this->statusFilter === 'draft') {
            $query->where('is_published', false);
        }

        $posts = $query->latest()->paginate(12);

        $stats = [
            'total'     => BlogPost::count(),
            'published' => BlogPost::where('is_published', true)->count(),
            'draft'     => BlogPost::where('is_published', false)->count(),
        ];

        return view('livewire.media.blog-management', compact('posts', 'stats'))
            ->layout('layouts.app');
    }
}
