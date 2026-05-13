<?php

namespace App\Livewire\Newsletter;

use App\Models\Media\NewsletterSubscriber;
use Livewire\Component;

class SubscribeForm extends Component
{
    public string $email = '';
    public string $name  = '';
    public bool $success = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:iuhm.newsletter_subscribers,email',
            'name'  => 'nullable|string|max:100',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email'    => 'Veuillez saisir une adresse e-mail valide.',
            'email.unique'   => 'Cette adresse e-mail est déjà inscrite.',
        ];
    }

    public function subscribe(): void
    {
        $this->validate();

        NewsletterSubscriber::create([
            'email'         => $this->email,
            'name'          => $this->name ?: null,
            'is_active'     => true,
            'subscribed_at' => now(),
        ]);

        $this->success = true;
        $this->email   = '';
        $this->name    = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.newsletter.subscribe-form');
    }
}

