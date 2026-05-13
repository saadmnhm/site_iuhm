<?php

namespace App\Livewire\Newsletter;

use App\Services\ContentApiService;
use Livewire\Component;

class SubscribeForm extends Component
{
    public string $email = '';
    public string $name  = '';
    public bool $success = false;
    public bool $stack = false;

    protected function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.required' => 'L\'adresse e-mail est obligatoire.',
            'email.email'    => 'Veuillez saisir une adresse e-mail valide.',
        ];
    }

    public function subscribe(ContentApiService $api): void
    {
        $this->validate();

        $result = $api->subscribeNewsletter($this->email, $this->name ?: null);

        if (isset($result['error'])) {
            $this->addError('email', $result['error']);
            return;
        }

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

