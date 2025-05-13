<?php

namespace App\Livewire\Frontend;

use App\Mail\ContactFormEmail;
use App\Mail\NewDealerApplicationReceived;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ContactForm extends Component
{
    #[Validate('required|email|max:255')]
    public string $email = '';
    #[Validate('required|string|max:255')]
    public string $name = '';
    #[Validate('required|string|max:15')]
    public string $phone = '';
    #[Validate('required|max:255')]
    public string $emailSubject = '';
    #[Validate('required|max:500')]
    public string $emailMessage = '';

    public function sendEmail()
    {
        $this->validate();

        $cacheKey = 'contact_form_sent_' . md5($this->emailMessage);
        if (cache()->has($cacheKey)) {
            return redirect()->back()->with('error', 'You can only send a message once every 24 hours.');
        }

        $admins = User::get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ContactFormEmail($this->name, $this->email, $this->phone, $this->emailSubject, $this->message));
        }

        cache()->put($cacheKey, true, now()->addDay());
        $this->reset();
        return redirect()->route('frontend.home')->with('success', 'Your message has been sent successfully.');
    }

    public function render()
    {
        return view('livewire.frontend.contact-form');
    }
}
