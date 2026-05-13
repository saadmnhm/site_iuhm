<?php

namespace App\Livewire\Contact;

use App\Services\ContentApiService;
use Livewire\Component;

class ContactForm extends Component
{
    public string $name    = '';
    public string $email   = '';
    public string $phone   = '';
    public string $subject = '';
    public string $message = '';
    public bool $success   = false;

    protected function rules(): array
    {
        return [
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:255',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required'    => 'Le nom est obligatoire.',
            'email.required'   => 'L\'adresse e-mail est obligatoire.',
            'email.email'      => 'Veuillez saisir une adresse e-mail valide.',
            'message.required' => 'Le message est obligatoire.',
        ];
    }

    public function send(ContentApiService $api): void
    {
        $this->validate();

        $result = $api->submitContact(
            $this->name,
            $this->email,
            $this->phone ?: null,
            $this->subject ?: null,
            $this->message,
        );

        if (isset($result['error'])) {
            $this->addError('message', $result['error']);
            return;
        }

        $this->success = true;
        $this->reset(['name', 'email', 'phone', 'subject', 'message']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.contact.contact-form');
    }
}
