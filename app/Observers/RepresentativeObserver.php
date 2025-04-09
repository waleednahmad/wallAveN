<?php

namespace App\Observers;

use App\Mail\NewRepresentativeApplicationReceived;
use App\Mail\RepresentativeApplicationReceived;
use App\Models\Representative;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class RepresentativeObserver
{
    /**
     * Handle the Representative "created" event.
     */
    public function created(Representative $representative): void
    {

        // Send a welcome email to the dealer
        Mail::to($representative->email)->send(new RepresentativeApplicationReceived());

        // Verify the dealer's email address (from the laravel auth)
        // event(new Registered($dealer)); 

        // Send a notification email to the admin
        $admins =  User::all();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewRepresentativeApplicationReceived($representative));
        }
    }

    /**
     * Handle the Representative "updated" event.
     */
    public function updated(Representative $representative): void
    {
        //
    }

    /**
     * Handle the Representative "deleted" event.
     */
    public function deleted(Representative $representative): void
    {
        //
    }

    /**
     * Handle the Representative "restored" event.
     */
    public function restored(Representative $representative): void
    {
        //
    }

    /**
     * Handle the Representative "force deleted" event.
     */
    public function forceDeleted(Representative $representative): void
    {
        //
    }
}
