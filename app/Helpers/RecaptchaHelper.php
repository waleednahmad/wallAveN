<?php

if (!function_exists('verify_recaptcha')) {
    /**
     * Helper function to verify reCAPTCHA response
     * 
     * @param string $recaptchaResponse
     * @return array
     */
    function verify_recaptcha($recaptchaResponse)
    {
        return \App\Rules\RecaptchaRule::verify($recaptchaResponse);
    }
}

if (!function_exists('is_recaptcha_enabled')) {
    /**
     * Check if reCAPTCHA is properly configured
     * 
     * @return bool
     */
    function is_recaptcha_enabled()
    {
        return !empty(config('recaptcha.site_key')) && !empty(config('recaptcha.secret_key'));
    }
}

if (!function_exists('get_recaptcha_site_key')) {
    /**
     * Get the reCAPTCHA site key
     * 
     * @return string
     */
    function get_recaptcha_site_key()
    {
        return config('recaptcha.site_key', '');
    }
}
