<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

/**
 * reCAPTCHA v2 Validation Rule
 * 
 * This rule validates Google reCAPTCHA v2 responses by verifying them with Google's API.
 * 
 * Usage in validation:
 * 'g-recaptcha-response' => ['required', new \App\Rules\RecaptchaRule()],
 * 
 * Requirements:
 * - RECAPTCHA_SITE_KEY and RECAPTCHA_SECRET_KEY must be set in .env
 * - reCAPTCHA v2 script must be loaded in the frontend
 * - Form must include <div class="g-recaptcha" data-sitekey="{{ get_recaptcha_site_key() }}"></div>
 */
class RecaptchaRule implements ValidationRule
{
    protected $recaptchaResponse = null;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if reCAPTCHA response is provided
        if (empty($value)) {
            $fail('reCAPTCHA is required.');
            return;
        }

        // Verify reCAPTCHA with Google
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $value,
            'remoteip' => request()->ip(), // Optional: Add user's IP for additional verification
        ]);

        $this->recaptchaResponse = $response->json();

        // Check if verification was successful
        if (!isset($this->recaptchaResponse['success']) || !$this->recaptchaResponse['success']) {
            $errorCodes = $this->recaptchaResponse['error-codes'] ?? [];
            
            // Provide more specific error messages based on error codes
            if (in_array('timeout-or-duplicate', $errorCodes)) {
                $fail('reCAPTCHA has expired. Please try again.');
            } elseif (in_array('invalid-input-response', $errorCodes)) {
                $fail('Invalid reCAPTCHA response. Please try again.');
            } else {
                $fail('reCAPTCHA validation failed. Please try again.');
            }
        }
    }

    /**
     * Get the reCAPTCHA response data (for debugging or additional processing)
     */
    public function getRecaptchaResponse()
    {
        return $this->recaptchaResponse;
    }

    /**
     * Static method to verify reCAPTCHA outside of validation context
     */
    public static function verify($recaptchaResponse)
    {
        if (empty($recaptchaResponse)) {
            return ['success' => false, 'error' => 'reCAPTCHA response is required'];
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $recaptchaResponse,
            'remoteip' => request()->ip(),
        ]);

        return $response->json();
    }
}
