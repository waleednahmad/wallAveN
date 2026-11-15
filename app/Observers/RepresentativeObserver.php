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

        // Confirmation to the applicant, then tell the admins. A mail problem never blocks the sign-up.
        try {
            Mail::to($representative->email)->send(new RepresentativeApplicationReceived($representative));
        } catch (\Throwable $e) {
            report($e);
        }

        foreach (User::whereNotNull('email')->pluck('email') as $adminEmail) {
            try {
                Mail::to($adminEmail)->send(new NewRepresentativeApplicationReceived($representative));
            } catch (\Throwable $e) {
                report($e);
            }
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
