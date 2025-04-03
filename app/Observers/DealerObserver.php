<?php

namespace App\Observers;

use App\Models\Dealer;

class DealerObserver
{
    /**
     * Handle the Dealer "created" event.
     */
    public function created(Dealer $dealer): void
    {
        // Create a default banner setting for the dealer
        $dealer->bannerSetting()->create([
            'text' => 'Golden Rugs – Exclusive Deals Just for You',
            'text_color' => '#000000',
            'bg_color' => '#f1c55e',
        ]);
    }

    /**
     * Handle the Dealer "updated" event.
     */
    public function updated(Dealer $dealer): void
    {
        //
    }

    /**
     * Handle the Dealer "deleted" event.
     */
    public function deleted(Dealer $dealer): void
    {
        // Delete the dealer's banner setting when the dealer is deleted
        $dealer->bannerSetting()->delete();
    }

    /**
     * Handle the Dealer "restored" event.
     */
    public function restored(Dealer $dealer): void
    {
        //
    }

    /**
     * Handle the Dealer "force deleted" event.
     */
    public function forceDeleted(Dealer $dealer): void
    {
        //
    }
}
