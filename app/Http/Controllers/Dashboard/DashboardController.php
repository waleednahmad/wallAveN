<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function logout()
    {
        auth('web')->logout();
        return redirect()->route('frontend.home');
    }
}
