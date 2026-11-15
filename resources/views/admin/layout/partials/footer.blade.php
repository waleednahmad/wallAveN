{{-- Admin footer: company on the left, developer credit on the right (quiet, never competes with the page) --}}
<footer class="main-footer adm-footer">
    <span>&copy; {{ date('Y') }} {{ getWebsiteTitle() ?: config('app.name') }}</span>
    <span class="adm-footer-credit">Designed &amp; developed by <a href="https://zenith-tech.digital/" target="_blank" rel="noopener">Zenith Tech</a></span>
</footer>
