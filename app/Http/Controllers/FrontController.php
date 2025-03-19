<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use App\Models\Product;
use App\Models\Representative;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::with(['vendor', 'firstVariant'])
            ->whereHas('variants')
            ->active()
            ->take(12)
            ->inRandomOrder()
            ->get();
        return view('frontend.index', compact('products'));
    }

    public function shop()
    {
        return view('frontend.shop');
    }

    public function showProduct($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $firstCategory = $product->categories->first();

        if (!$product) {
            return redirect()->route('frontend.home')->with('error', 'Product not found.');
        }
        return view('frontend.product', compact('product', 'firstCategory'));
    }



    public function register()
    {
        return view('frontend.auth.register');
    }

    public function login()
    {
        return view('frontend.auth.login');
    }

    public function submitRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:dealers', 'email:rfc,dns', 'unique:users', 'unique:representatives'],
            'phone' => ['required', 'string', 'unique:dealers', 'min:10', 'unique:representatives'],
            'company_name' => ['required', 'string'],
            'tax_id' => ['required', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'zip_code' => ['required', 'string'],
            'years_in_business' => ['required', 'numeric', 'min:1'],
            'website' => ['nullable', 'string'],
            'business_type' => ['required', 'string'],
            'message' => ['nullable', 'string'],
            'resale_certificate' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,pdf'],
            'ref' => ['nullable', 'string', 'exists:representatives,code'],
        ], [
            'ref.exists' => 'Invalid referral code.'
        ]);


        $data = $request->only([
            'phone',
            'company_name',
            'tax_id',
            'city',
            'state',
            'zip_code',
            'years_in_business',
            'website',
            'business_type',
            'message',
        ]);

        if ($request->hasFile('resale_certificate')) {
            $file = $request->file('resale_certificate')->store('resale_certificates', 'public');
            $data['resale_certificate'] = "storage/" . $file;
        }
        $data['address'] = explode(',', $request->address)[0];
        $data['name'] = ucwords(strtolower($request->name));
        $data['email'] = strtolower($request->email);

        // Check on referal code
        if ($request->ref) {
            $representative = Representative::where('code', $request->ref)->first();
            if ($representative) {
                $data['referal_id'] = $representative->id;
            }
        }

        $dealer = Dealer::create($data);
        return redirect()->route('frontend.home')->with('success', 'Your registration has been submitted successfully, we will contact you soon.');
    }

    public function submitLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'email:rfc,dns'],
            'password' => ['required', 'string'],
        ]);

        // Try web guard (regular users)
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::guard('web')->login($user);
            return redirect()->route('dashboard');
        }

        // Try dealer guard with additional checks
        $dealer = Dealer::where('email', $request->email)->first();
        if ($dealer) {
            // Check password first
            if (Hash::check($request->password, $dealer->password)) {

                if (!$dealer->is_approved) {
                    return back()->with('error', 'Your dealer account is not approved yet.');
                }

                if (empty($dealer->password)) {
                    return back()->with('error', 'Invalid dealer credentials, please contact support.');
                }

                if (!$dealer->status) {
                    return back()->with('error', 'Your account is disabled, please contact support.');
                }

                Auth::guard('dealer')->login($dealer);
                return redirect()->route('dealer.dashboard')->with('success', 'You have been logged in successfully.');
            }
        }

        // Try representative guard
        $representative = Representative::where('email', $request->email)->first();
        if ($representative && Hash::check($request->password, $representative->password)) {
            Auth::guard('representative')->login($representative);
            return redirect()->route('representative.dashboard')->with('success', 'You have been logged in successfully.');
        }

        // If all checks fail
        return back()->with('error', 'The provided credentials are incorrect.');
    }

    public function logout()
    {
        Auth::guard('web')->logout();
        Auth::guard('dealer')->logout();
        return redirect()->route('frontend.home');
    }
}
