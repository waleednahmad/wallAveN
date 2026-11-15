<?php

namespace App\Livewire\Dashboard\Dealers;

use App\Models\Dealer;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Dealer applications waiting for a decision, oldest first, as review cards
 * (approve, or decline with a reason that can be emailed). Dealers page, "Applications" tab.
 */
class Applications extends Component
{
    use WithPagination;

    public string $search = '';

    /** The card currently showing the decline form. */
    public ?int $decliningId = null;
    public string $declineReason = '';
    public bool $declineNotify = true;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function applications()
    {
        $term = trim($this->search);

        return Dealer::pendingApplication()
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($q) use ($term) {
                    $q->where('company_name', 'like', "%{$term}%")
                        ->orWhere('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('city', 'like', "%{$term}%")
                        ->orWhere('state', 'like', "%{$term}%");
                });
            })
            ->orderBy('created_at')
            ->paginate(12);
    }

    public function approve(int $id): void
    {
        $dealer = Dealer::pendingApplication()->find($id);
        if (!$dealer) {
            $this->dispatch('error', 'This application was already handled');
            return;
        }

        try {
            $dealer->approveApplication();
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('error', 'Approved, but the email could not be sent');
            $this->refreshCounts('approved');
            return;
        }

        $this->dispatch('success', ($dealer->company_name ?: $dealer->name) . ' approved and emailed');
        $this->refreshCounts('approved');
    }

    public function startDecline(int $id): void
    {
        $this->decliningId = $id;
        $this->declineReason = 'We could not verify a resale certificate for your business.';
        $this->declineNotify = true;
    }

    public function cancelDecline(): void
    {
        $this->reset('decliningId', 'declineReason');
    }

    public function decline(): void
    {
        $this->validate(['declineReason' => 'nullable|string|max:1000']);

        $dealer = $this->decliningId ? Dealer::pendingApplication()->find($this->decliningId) : null;
        if (!$dealer) {
            $this->dispatch('error', 'This application was already handled');
            $this->cancelDecline();
            return;
        }

        try {
            $dealer->declineApplication(trim($this->declineReason), $this->declineNotify);
        } catch (\Throwable $e) {
            report($e);
            $this->dispatch('error', 'Declined, but the email could not be sent');
            $this->cancelDecline();
            $this->refreshCounts('declined');
            return;
        }

        $this->dispatch('success', ($dealer->company_name ?: $dealer->name) . ' declined' . ($this->declineNotify ? ' and emailed' : ''));
        $this->cancelDecline();
        $this->refreshCounts('declined');
    }

    /** Opens the existing "Dealer Details" panel on the dealers page. */
    public function details(int $id): void
    {
        $this->dispatch('openShowOffcanvas', ['id' => $id]);
    }

    /** Re-query the cards and tell the page tabs to move one from "Applications" to $result. */
    private function refreshCounts(string $result): void
    {
        unset($this->applications);
        $this->dispatch('dealer-applications-changed', result: $result);
    }

    public function render()
    {
        return view('livewire.dashboard.dealers.applications');
    }
}
