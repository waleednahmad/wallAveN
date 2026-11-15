{{-- Email button that also works in Outlook (table cell with a background). Expects $url, $label. --}}
<table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 26px 0 8px;">
    <tr>
        <td align="center" bgcolor="#7B2D26" style="border-radius: 8px; background: #7B2D26;">
            <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 13px 26px; font-family: Helvetica, Arial, sans-serif; font-size: 15px; font-weight: bold; color: #FFFFFF; text-decoration: none; border-radius: 8px;">{{ $label }}</a>
        </td>
    </tr>
</table>
