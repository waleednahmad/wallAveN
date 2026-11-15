{{-- Label / value rows. Expects $rows: ['Label' => 'value' or HtmlString]; empty values are skipped. --}}
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 20px 0 4px; border-top: 1px solid #E3D8C8;">
    @foreach ($rows as $label => $value)
        @continue($value === null || $value === '')
        <tr>
            <td style="padding: 10px 12px 10px 0; border-bottom: 1px solid #EFE6D8; width: 34%; font-family: Helvetica, Arial, sans-serif; font-size: 13px; color: #8C8178; vertical-align: top;">{{ $label }}</td>
            <td style="padding: 10px 0; border-bottom: 1px solid #EFE6D8; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: #231A14; vertical-align: top;">{{ $value }}</td>
        </tr>
    @endforeach
</table>
