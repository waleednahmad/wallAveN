<div class="gr-dealer" x-data="{
        on: @js((string) $is_customer_mode_active === '1'),
        pct: @js((float) ($fake_sale_percentage ?: 1)),
        text: @js((string) $text),
        fg: @js($text_color ?: '#FFFCF7'),
        bg: @js($bg_color ?: '#1F2E45'),
        money(n) { return '$' + (Number(n) || 0).toFixed(2); }
    }">
    <h1 style="margin: 0 0 6px; font-size: clamp(32px, 4vw, 44px);">Customer Mode</h1>
    <p style="margin: 0 0 28px; color: var(--gr-muted); max-width: 640px; line-height: 1.6;">Turn this on when a shopper is browsing with you in your store. Prices show at your retail price, wholesale wording is hidden, and your own banner appears on top.</p>

    <div class="row g-4 align-items-start">
        {{-- Settings (same five fields as before) --}}
        <div class="col-lg-5">
            <form wire:submit.prevent="updateBannerSettings" class="gr-card d-flex flex-column" style="padding: 26px 28px; gap: 20px;">
                <div>
                    <label for="is_customer_mode_active" class="form-label">Customer Mode</label>
                    <select id="is_customer_mode_active" wire:model.live="is_customer_mode_active" class="form-control" x-on:change="on = $event.target.value === '1'">
                        <option value="1">On — shoppers see retail prices</option>
                        <option value="0">Off — you see your trade prices</option>
                    </select>
                    @error('is_customer_mode_active') <span class="text-danger" style="font-size: 13px;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="fake_sale_percentage" class="form-label">Sale percentage (price multiplier)</label>
                    <input id="fake_sale_percentage" type="number" step=".01" min="1" max="100" wire:model="fake_sale_percentage" class="form-control" x-on:input="pct = $event.target.value">
                    <p class="gr-help">A <span x-text="money(75)">$75.00</span> trade price shows as <strong style="color: var(--gr-ink);" x-text="money(75 * pct)"></strong> to your shopper.</p>
                    @error('fake_sale_percentage') <span class="text-danger" style="font-size: 13px;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="text" class="form-label">Banner text</label>
                    <textarea id="text" rows="2" placeholder="e.g. Welcome to our store — ask our team about delivery" wire:model="text" class="form-control" style="min-height: 80px;" x-on:input="text = $event.target.value"></textarea>
                    @error('text') <span class="text-danger" style="font-size: 13px;">{{ $message }}</span> @enderror
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <label for="bg_color" class="form-label">Banner color</label>
                        <input id="bg_color" type="color" wire:model="bg_color" class="form-control form-control-color w-100" x-on:input="bg = $event.target.value">
                        @error('bg_color') <span class="text-danger" style="font-size: 13px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-6">
                        <label for="text_color" class="form-label">Text color</label>
                        <input id="text_color" type="color" wire:model="text_color" class="form-control form-control-color w-100" x-on:input="fg = $event.target.value">
                        @error('text_color') <span class="text-danger" style="font-size: 13px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit" class="gr-btn gr-btn-dark w-100" wire:loading.attr="disabled" wire:target="updateBannerSettings">
                    <span wire:loading wire:target="updateBannerSettings" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Save settings
                </button>
            </form>
        </div>

        {{-- Live preview --}}
        <div class="col-lg-7" aria-label="Live preview">
            <p class="gr-eyebrow no-rule" style="margin-bottom: 10px;">Live preview — what your shopper sees</p>
            <div style="border-radius: 16px; overflow: hidden; border: 1px solid var(--gr-line); background: var(--gr-linen); box-shadow: 0 18px 40px rgba(35,26,20,.10);">
                <div aria-hidden="true" style="height: 30px; background: #E9DFD0; display: flex; gap: 6px; align-items: center; padding: 0 12px;">
                    <span style="width: 10px; height: 10px; border-radius: 5px; background: #CFC3B1;"></span>
                    <span style="width: 10px; height: 10px; border-radius: 5px; background: #CFC3B1;"></span>
                    <span style="width: 10px; height: 10px; border-radius: 5px; background: #CFC3B1;"></span>
                </div>
                <template x-if="on">
                    <div x-show="text" x-text="text" x-bind:style="'background:' + bg + ';color:' + fg" style="text-align: center; padding: 10px 14px; font-size: 13px; font-weight: 500;"></div>
                </template>
                <template x-if="!on">
                    <div style="background: var(--gr-indigo); color: #F1E6D2; text-align: center; padding: 10px 14px; font-size: 13px;"><strong style="color: var(--gr-saffron);">Wholesale only</strong> — trade pricing for registered retailers</div>
                </template>
                <div style="background: var(--gr-paper); border-bottom: 1px solid var(--gr-line); padding: 10px 16px; display: flex; justify-content: space-between; align-items: center;">
                    <img src="{{ getMainImage() }}" alt="" style="height: 30px; width: auto;">
                    <span style="font-size: 12px; font-weight: 600; padding: 6px 12px; border-radius: 16px; background: var(--gr-ink); color: #fff;">Cart</span>
                </div>
                <div style="padding: 18px; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px;">
                    @foreach ([1, 2, 3] as $i)
                        <div>
                            <div style="background: #E9DFD0; border-radius: 10px; aspect-ratio: 3 / 4;"></div>
                            <p style="margin: 8px 0 0; font-family: var(--gr-font-display); font-size: 16px;">Sample rug</p>
                            <p style="margin: 2px 0 0; font-size: 14px;"><strong style="font-weight: 600;" x-text="money(on ? 75 * pct : 75)">$75.00</strong> <span style="font-size: 12px; color: var(--gr-muted);" x-show="!on">trade</span></p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
