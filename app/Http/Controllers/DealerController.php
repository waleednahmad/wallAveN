<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DealerController extends Controller
{
    public function dashboard()
    {
        $ordersCountByStatus = auth('dealer')->user()->orders()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        return view('dealer.dashboard', compact('ordersCountByStatus'));
    }

    public function profile()
    {
        return view('dealer.profile');
    }

    public function orders()
    {
        return view('dealer.orders');
    }

    public function customerMode()
    {
        return view('dealer.customer-mode');
    }
}
