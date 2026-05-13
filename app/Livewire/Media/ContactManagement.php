<?php

namespace App\Livewire\Media;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactManagement extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $filter      = 'all'; // all | unread | read
    public bool   $showDetail  = false;
    public ?int   $contactId   = null;
    public ?Contact $contact   = null;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingFilter(): void { $this->resetPage(); }

    public function view(int $id): void
    {
        $this->contact   = Contact::findOrFail($id);
        $this->contactId = $id;
        $this->showDetail = true;

        if (!$this->contact->is_read) {
            $this->contact->update(['is_read' => true, 'read_at' => now()]);
        }
    }

    public function markUnread(int $id): void
    {
        Contact::findOrFail($id)->update(['is_read' => false, 'read_at' => null]);
        if ($this->contactId === $id) {
            $this->contact = Contact::findOrFail($id);
        }
        $this->dispatch('notify', message: 'Marqué comme non lu.', type: 'success');
    }

    public function delete(int $id): void
    {
        Contact::findOrFail($id)->delete();
        if ($this->contactId === $id) {
            $this->showDetail = false;
            $this->contact    = null;
            $this->contactId  = null;
        }
        $this->dispatch('notify', message: 'Message supprimé.', type: 'success');
    }

    public function closeDetail(): void
    {
        $this->showDetail = false;
        $this->contact    = null;
        $this->contactId  = null;
    }

    public function render()
    {
        $query = Contact::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('subject', 'like', "%{$this->search}%")
                  ->orWhere('message', 'like', "%{$this->search}%");
            });
        }

        if ($this->filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($this->filter === 'read') {
            $query->where('is_read', true);
        }

        $contacts = $query->latest()->paginate(20);

        $stats = [
            'total'  => Contact::count(),
            'unread' => Contact::where('is_read', false)->count(),
            'read'   => Contact::where('is_read', true)->count(),
        ];

        return view('livewire.media.contact-management', compact('contacts', 'stats'))
            ->layout('layouts.app');
    }
}
