<?php

namespace App\Models;

use App\Observers\DealerObserver;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[ObservedBy([DealerObserver::class])]
class Dealer extends Authenticatable implements MustVerifyEmail
{

    use Notifiable;

    protected $guarded = [];

    // ==================
    // Application review (used by the dealers table and the applications screen)
    // ==================

    /** Waiting for a decision: not approved and not declined. */
    public function scopePendingApplication($query)
    {
        return $query->where('is_approved', false)->whereNull('rejected_at');
    }

    /**
     * Approve the application. Same behaviour as the original table action: a dealer without a
     * password gets the default one, and the "Dealer Accepted" email is sent.
     */
    public function approveApplication(): void
    {
        $this->update([
            'is_approved' => true,
            'approved_at' => now(),
            'rejected_at' => null,
            'rejection_reason' => null,
        ]);

        if (empty($this->password)) {
            $this->update(['password' => \Illuminate\Support\Facades\Hash::make('Password123')]);
        }

        if ($this->email) {
            \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\DealerAccepted($this));
        }
    }

    /** Decline the application, keeping the reason; optionally email it to the applicant. */
    public function declineApplication(?string $reason, bool $notify = true): void
    {
        $this->update([
            'is_approved' => false,
            'approved_at' => null,
            'rejected_at' => now(),
            'rejection_reason' => $reason ?: null,
        ]);

        if ($notify && $this->email) {
            \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\DealerDeclined($this));
        }
    }

    // ==================
    // Relationships
    // ==================
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cartTemps()
    {
        return $this->hasMany(CartTemp::class);
    }

    public function bannerSetting()
    {
        return $this->hasOne(DealerBannerSetting::class);
    }

    public function referalTo()
    {
        return $this->belongsTo(Representative::class, 'referal_id');
    }


    public function priceList()
    {
        return $this->hasOne(PriceList::class, 'id', 'price_list_id')
            ->selectRaw('id, percentage / 100 as percentage, name');
    }

    // ==================
    // Scopes
    // ==================
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
