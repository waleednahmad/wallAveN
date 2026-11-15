{{-- Bot guard for public forms (see App\Http\Middleware\BlockFormBots). Hidden from people and screen readers. --}}
<div style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;" aria-hidden="true">
    <label for="contact_me_by_fax_only">Leave this field empty</label>
    <input type="text" id="contact_me_by_fax_only" name="contact_me_by_fax_only" value="" tabindex="-1" autocomplete="off">
</div>
<input type="hidden" name="form_started_at" value="{{ \Illuminate\Support\Facades\Crypt::encryptString((string) time()) }}">
