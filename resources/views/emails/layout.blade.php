{{--
    Shared email layout. Email clients ignore most CSS, so this is tables and inline styles,
    600px wide, web-safe fonts, and colours written out (CSS variables don't work in email).
    Sections: preheader (the grey line under the subject in the inbox), eyebrow, heading, content.
--}}
@php
    $site = getWebsiteTitle() ?: config('app.name');
    $c = ['primary' => '#7B2D26', 'ink' => '#231A14', 'muted' => '#5F534A', 'line' => '#E3D8C8', 'bg' => '#F5EFE6', 'card' => '#FFFFFF'];
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="color-scheme" content="light">
    <title>@yield('heading') · {{ $site }}</title>
</head>
<body style="margin: 0; padding: 0; background: {{ $c['bg'] }}; -webkit-text-size-adjust: 100%;">
    {{-- Inbox preview text, hidden in the email itself --}}
    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all; font-size: 1px; line-height: 1px; color: {{ $c['bg'] }};">@yield('preheader')&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;&#8199;&#847;</div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background: {{ $c['bg'] }};">
        <tr>
            <td align="center" style="padding: 32px 16px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 600px;">
                    {{-- Logo --}}
                    <tr>
                        <td align="left" style="padding: 0 4px 20px;">
                            <a href="{{ url('/') }}" style="text-decoration: none;">
                                <img src="{{ getMainImage() }}" alt="{{ $site }}" height="48" style="display: block; height: 48px; width: auto; border: 0;">
                            </a>
                        </td>
                    </tr>

                    {{-- Card --}}
                    <tr>
                        <td style="background: {{ $c['card'] }}; border: 1px solid {{ $c['line'] }}; border-top: 4px solid {{ $c['primary'] }}; border-radius: 12px; padding: 36px 36px 32px;">
                            @hasSection('eyebrow')
                                <p style="margin: 0 0 10px; font-family: Helvetica, Arial, sans-serif; font-size: 12px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; color: {{ $c['primary'] }};">@yield('eyebrow')</p>
                            @endif
                            <h1 style="margin: 0 0 16px; font-family: Georgia, 'Times New Roman', serif; font-size: 26px; line-height: 1.25; font-weight: normal; color: {{ $c['ink'] }};">@yield('heading')</h1>
                            <div style="font-family: Helvetica, Arial, sans-serif; font-size: 15px; line-height: 1.6; color: {{ $c['muted'] }};">
                                @yield('content')
                            </div>
                        </td>
                    </tr>

                    {{-- Footer: who sent it and how to reach them --}}
                    <tr>
                        <td style="padding: 24px 8px 0; font-family: Helvetica, Arial, sans-serif; font-size: 12.5px; line-height: 1.6; color: {{ $c['muted'] }};" align="center">
                            <strong style="color: {{ $c['ink'] }};">{{ $site }}</strong><br>
                            @if (companyAddress()){{ companyAddress() }}<br>@endif
                            @if (companyPhone())<a href="tel:{{ companyTel() }}" style="color: {{ $c['muted'] }}; text-decoration: none;">{{ companyPhone() }}</a>@endif
                            @if (companyPhone() && companyEmail()) &nbsp;·&nbsp; @endif
                            @if (companyEmail())<a href="mailto:{{ companyEmail() }}" style="color: {{ $c['muted'] }}; text-decoration: none;">{{ companyEmail() }}</a>@endif
                            <br><a href="{{ url('/') }}" style="color: {{ $c['primary'] }}; text-decoration: none;">{{ preg_replace('#^https?://#', '', url('/')) }}</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
