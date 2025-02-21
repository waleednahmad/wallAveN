<?php

namespace App\Livewire\Frontend\Dealer\Dashboard;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersPage extends Component
{
    use WithPagination;

    public $search = '';
    public $status = 'all';
    public $from_date = '';
    public $to_date = '';

    #[Computed()]
    public function statuses()
    {
        return [
            'all',
            'pending',
            'processing',
            'completed',
            'canceled',
            'declined',
        ];
    }

    public function showOrderDetails($orderId)
    {
        $this->dispatch('showOrderDetails', ['order' => $orderId]);
    }

    public function showConfirmCancelOrderModal($orderId)
    {
        $this->dispatch('showConfirmCancelOrder', ['order' => $orderId]);
    }

    #[On('refreshOrders')]
    public function render()
    {
        $ordersQuery = Order::query()
            ->where('dealer_id', auth('dealer')->id())
            ->when($this->status !== 'all', function ($query) {
                return $query->where('status', $this->status);
            })
            ->when($this->status === 'all', function ($query) {
                return $query->where('status', '!=', 'cart');
            })
            ->when($this->from_date, function ($query) {
                return $query->whereDate('created_at', '>=', $this->from_date);
            })
            ->when($this->to_date, function ($query) {
                return $query->whereDate('created_at', '<=', $this->to_date);
            })
            ->when($this->search, function ($query) {
                return $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('total', 'like', '%' . $this->search . '%')
                    ->orWhere('po_number', 'like', '%' . $this->search . '%');;
            })
            ->latest();

        return view('livewire.frontend.dealer.dashboard.orders-page')->with([
            'orders' => $ordersQuery->paginate(10),
        ]);
    }
}
