<?php

namespace App\Http\Controllers;

use App\Mail\DealerApplicationReceived;
use App\Mail\NewDealerApplicationReceived;
use App\Mail\TestEmail;
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Page;
use App\Models\PriceList;
use App\Models\Product;
use App\Models\Representative;
use App\Models\SeoPage;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FrontController extends Controller
{
    public function index()
    {
        $page = Page::where('title', 'Home')->first();
        $products = Product::with(['vendor', 'firstVariant', 'variants'])
            ->whereHas('variants')
            ->active()
            ->take(12)
            ->inRandomOrder()
            ->get();
        $categories  = Category::active()
            ->whereHas('subcategories', function ($subQuery) {
                $subQuery->whereHas('products', function ($query) {
                    $query->where('status', 1)->whereHas('variants');
                });
            })
            ->orWhereHas('products', function ($query) {
                $query->where('status', 1)->whereHas('variants');
            })
            ->orderBy('name')
            ->get();
        $seoContent = SeoPage::where('name', 'home')->first();
        return view('frontend.index', compact('products', 'page', 'categories', 'seoContent'));
    }

    public function shop()
    {
        $seoContent = SeoPage::where('name', 'shop')->first();
        if (showCategoryAndShopPages() || auth('dealer')->check() || auth('representative')->check() || auth('web')->check()) {
            return view('frontend.shop', compact('seoContent'));
        }
        return redirect()->route('frontend.home')->with('error', 'Shop page is disabled.');
    }

    public function showProduct($slug)
    {
        if (showCategoryAndShopPages() || auth('dealer')->check() || auth('representative')->check() || auth('web')->check()) {
            $product = Product::where('slug', $slug)->first();
            $firstCategory = $product?->categories->first();

            if (!$product) {
                return redirect()->route('frontend.home')->with('error', 'Product not found.');
            }
            return view('frontend.product', compact('product', 'firstCategory'));
        }

        return redirect()->route('frontend.home')->with('error', 'Product page is disabled.');
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
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string', 'min:8'],
            'g-recaptcha-response' => ['required', 'recaptchav3:dealer_register,0.5'],
        ], [
            'ref.exists' => 'Invalid referral code.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
            'g-recaptcha-response.recaptchav3' => 'reCAPTCHA verification failed. Please try again.',
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
        $data['password'] = Hash::make($request->password);
        // $data['price_list_id'] = PriceList::where('is_default', 1)->first()->id;
        $data['fake_sale_percentage'] = getMinimumDealerSalePercentage();

        // Check on referal code
        if ($request->ref) {
            $representative = Representative::where('code', $request->ref)->first();
            if ($representative) {
                $data['referal_id'] = $representative->id;
            }
        }

        $dealer = Dealer::create($data);

        // =================== Send Emails ===================
        // Send a welcome email to the dealer
        Mail::to($dealer->email)->send(new DealerApplicationReceived());

        // Verify the dealer's email address (from the laravel auth)
        // event(new Registered($dealer));

        // Send a notification email to the admin
        $admins = User::get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewDealerApplicationReceived($dealer));
        }
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

            if (!$representative->is_approved) {
                return back()->with('error', 'Your account is not approved yet.');
            }

            if (empty($representative->password)) {
                return back()->with('error', 'Invalid representative credentials, please contact support.');
            }

            if (!$representative->status) {
                return back()->with('error', 'Your account is disabled, please contact support.');
            }

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


    public function aboutUs()
    {
        $page = Page::where('title', 'About Us')->first();
        $seoContent = SeoPage::where('name', 'about_us')->first();
        return view('frontend.about-us', compact('page', 'seoContent'));
    }
    public function contactUs()
    {
        $page = Page::where('title', 'Contact Us')->first();
        $seoContent = SeoPage::where('name', 'contact_us')->first();
        return view('frontend.contact-us', compact('page', 'seoContent'));
    }



    // Send Test Email
    public function sendEmail()
    {
        try {
            Mail::to('ahmadalsakhen36@gmail.com')->send(new TestEmail());
            return "Email sent successfully!";
        } catch (\Exception $e) {
            return "Failed to send email: " . $e->getMessage();
        }
    }
}
