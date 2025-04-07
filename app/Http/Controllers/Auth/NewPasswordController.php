<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\Representative;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Search for the user in users, dealers, and representatives tables
        $user = collect([User::class, Dealer::class, Representative::class])
            ->map(fn($model) => $model::where('email', $request->email)->first())
            ->filter()
            ->first();

        // If no user is found, redirect back with an error
        if (!$user->exists()) {
            dd('not exists');
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('We can\'t find a user with that email address.')]);
        }

        // Admin Check
        $admin = User::where('email', $request->email)->first();
        if ($admin) {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }

            );
        }

        // Dealer Check
        $dealer = Dealer::where('email', $request->email)->first();
        if ($dealer) {
            $status = Password::broker('dealers')->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (Dealer $dealer) use ($request) {
                    $dealer->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    dd('dealer dealer', $dealer);
                    event(new PasswordReset($dealer));
                }
            );
        }
        // Representative Check
        $representative = Representative::where('email', $request->email)->first();
        if ($representative) {
            $status = Password::broker('representatives')->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (Representative $representative) use ($request) {
                    $representative->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($representative));
                }
            );
        }

        // Redirect based on the reset status
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
