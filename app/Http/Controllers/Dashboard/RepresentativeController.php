<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        return view('admin.representatives.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function register()
    {
        if (!showBecomeARepInMenu()) {
            return redirect()->route('frontend.home')->with('error', 'This page is not available.');
        }
        return view('representative.auth.register');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function submitRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:representatives', 'unique:users', 'unique:dealers', 'email:rfc,dns'],
            'phone' => ['required', 'string', 'unique:representatives', 'unique:dealers', 'min:10'],
            'bussiness_name' => ['required', 'string'],
            'faderal_tax_classification' => ['required', 'string', 'in:individual,c_corporation,s_corporation,partnership,trust,limited_liability_company,other'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip_code' => ['required', 'string'],
            'taxpayer_identification_number' => ['required', 'string', 'in:social_security_number,employer_identification_number'],
            'social_security_number' => ['nullable', 'string', 'required_if:taxpayer_identification_number,social_security_number'],
            'employer_identification_number' => ['nullable', 'string', 'required_if:taxpayer_identification_number,employer_identification_number'],
            'bank_account_type' => ['required', 'string'],
            'bank_routing_number' => ['required', 'string'],
            'bank_account_number' => ['required', 'string'],
            'signature' => ['nullable', 'string'],
            'other_info' => ['nullable', 'required_if:faderal_tax_classification,other', 'string'],
            'message' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
            'g-recaptcha-response' => ['required', 'recaptchav3:register,0.5'],
        ], [
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
            'g-recaptcha-response.recaptchav3' => 'reCAPTCHA verification failed. Please try again.',
        ]);
        $data = $request->except(['_token', 'signature', 'code', 'password', 'password_confirmation']);
        $data['password'] = Hash::make($request->password);

        // ==== Generate a unique code for the representative ====
        $code = strtoupper(substr(md5(uniqid()), 0, 6));
        while (Representative::where('code', $code)->exists()) {
            $code = strtoupper(substr(md5(uniqid()), 0, 6));
        }
        $data['code'] = $code;

        // Save the data to the database
        $reprensentative = Representative::create($data);

        // Login the user
        // auth()->guard('representative')->login($reprensentative);
        return redirect()->route('frontend.home')->with('success', 'Your account has been created successfully, we will review your application and get back to you soon.');
    }



    /**
     * Dispaly representative dashboard
     */
    public function dashboard()
    {
        $ordersCountByStatus = auth('representative')->user()->orders()
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        $referredDealersCount = auth('representative')->user()->referredDealers('approved')->count();
        return view('representative.dashboard', compact('ordersCountByStatus', 'referredDealersCount'));
    }



    /**
     * Log the user out of the application.
     */
    public function logout()
    {
        auth()->guard('representative')->logout();
        return redirect()->route('frontend.home');
    }
}
