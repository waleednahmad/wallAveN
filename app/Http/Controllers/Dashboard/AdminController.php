<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admins\CreateAdminRequest;
use App\Http\Requests\Dashboard\Admins\UpdateAdminRequest;
use App\Models\Campaign;
use App\Models\SupportTicket;
use App\Models\User;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Route $route)
    {
        return view('admin.admins.index');
    }
}
