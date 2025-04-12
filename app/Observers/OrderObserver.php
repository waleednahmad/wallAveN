<?php

namespace App\Observers;

use App\Mail\NewOrderPlacedForAdmin;
use App\Mail\NewOrderPlacedForDealer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // $dealer = $order->dealer;
        // Send a welcome email to the dealer
        // Mail::to($dealer->email)->send(new NewOrderPlacedForDealer($order));

        // Verify the dealer's email address (from the laravel auth)
        // event(new Registered($dealer));

        // Send a notification email to the admin
        // $admins =  User::all();
        // foreach ($admins as $admin) {
        //     Mail::to($admin->email)->send(new NewOrderPlacedForAdmin($dealer));
        // }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
