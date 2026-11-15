<div>
    <div class="adm-apps-tools">
        <label class="adm-apps-search">
            <i class="fas fa-search" aria-hidden="true"></i>
            <input type="search" wire:model.live.debounce.400ms="search" placeholder="Company, contact, email, city" aria-label="Search applications">
        </label>
        <span class="adm-apps-hint">Oldest first, so nobody waits forever</span>
    </div>

    @php $apps = $this->applications; @endphp

    @if ($apps->isEmpty())
        <div class="adm-empty">
            <p class="adm-empty-title">{{ $search ? 'No applications match your search' : 'No applications waiting' }}</p>
            <p class="adm-empty-text">{{ $search ? 'Try another name, email or city.' : 'New dealer applications show up here for review.' }}</p>
        </div>
    @else
        <div class="adm-apps">
            @foreach ($apps as $dealer)
                @php
                    $days = (int) $dealer->created_at?->diffInDays(now());
                    $ageClass = $days > 60 ? 'is-old' : ($days > 14 ? 'is-mid' : 'is-new');
                    $place = trim(implode(', ', array_filter([$dealer->city, $dealer->state])));
                    $hasCert = $dealer->resale_certificate && file_exists(public_path($dealer->resale_certificate));
                    $declining = $decliningId === $dealer->id;
                @endphp
                <article wire:key="app-{{ $dealer->id }}" @class(['adm-app', 'is-declining' => $declining])>
                    <header class="adm-app-head">
                        <div class="adm-app-title">
                            <h3>{{ $dealer->company_name ?: ($dealer->name ?: 'Unnamed business') }}</h3>
                            <p>{{ $dealer->name }}{{ $dealer->name && $place ? ' · ' : '' }}{{ $place }}</p>
                        </div>
                        <span class="adm-age {{ $ageClass }}" title="Applied {{ $dealer->created_at?->format('M j, Y') }}">
                            Waiting {{ $dealer->created_at?->diffForHumans(null, \Carbon\CarbonInterface::DIFF_ABSOLUTE) }}
                        </span>
                    </header>

                    @if (!$declining)
                        <dl class="adm-app-meta">
                            <div><dt>Email</dt><dd><a href="mailto:{{ $dealer->email }}">{{ $dealer->email ?: '—' }}</a></dd></div>
                            <div><dt>Phone</dt><dd>{{ $dealer->phone ?: '—' }}</dd></div>
                            <div><dt>Business type</dt><dd>{{ $dealer->business_type ?: '—' }}</dd></div>
                            <div><dt>Years in business</dt><dd>{{ $dealer->years_in_business ?: '—' }}</dd></div>
                            @if ($dealer->website)
                                <div><dt>Website</dt><dd><a href="{{ \Illuminate\Support\Str::startsWith($dealer->website, ['http://', 'https://']) ? $dealer->website : 'https://' . $dealer->website }}" target="_blank" rel="noopener nofollow">{{ $dealer->website }}</a></dd></div>
                            @endif
                        </dl>

                        @if ($dealer->message)
                            <p class="adm-app-msg">“{{ \Illuminate\Support\Str::limit($dealer->message, 220) }}”</p>
                        @endif

                        @if ($hasCert)
                            <a class="adm-app-doc" href="{{ asset($dealer->resale_certificate) }}" target="_blank" rel="noopener">
                                <i class="far fa-file-alt" aria-hidden="true"></i> Resale certificate · open in a new tab
                            </a>
                        @else
                            <div class="adm-app-doc is-missing"><i class="fas fa-exclamation-circle" aria-hidden="true"></i> No resale certificate uploaded</div>
                        @endif

                        <div class="adm-app-actions">
                            <button type="button" class="btn btn-success flex-fill" wire:click="approve({{ $dealer->id }})"
                                wire:confirm="Approve {{ $dealer->company_name ?: $dealer->name }}? They will get an email and can start ordering."
                                wire:loading.attr="disabled" wire:target="approve({{ $dealer->id }})">
                                <span wire:loading wire:target="approve({{ $dealer->id }})" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                <i class="fas fa-check" wire:loading.remove wire:target="approve({{ $dealer->id }})" aria-hidden="true"></i> Approve
                            </button>
                            <button type="button" class="btn btn-secondary flex-fill" wire:click="startDecline({{ $dealer->id }})">Decline…</button>
                            <button type="button" class="btn btn-secondary" wire:click="details({{ $dealer->id }})">Details</button>
                        </div>
                    @else
                        <form wire:submit="decline" class="adm-app-decline">
                            <p class="adm-app-decline-q">Decline this application?</p>
                            <label class="form-label" for="decline-reason-{{ $dealer->id }}">Reason <span class="text-muted fw-normal">(included in the email)</span></label>
                            <textarea id="decline-reason-{{ $dealer->id }}" class="form-control" rows="3" wire:model="declineReason" maxlength="1000"></textarea>
                            @error('declineReason') <span class="text-danger small">{{ $message }}</span> @enderror
                            <label class="adm-check mt-2">
                                <input type="checkbox" wire:model="declineNotify"> Email the applicant
                            </label>
                            <div class="adm-app-actions">
                                <button type="submit" class="btn btn-primary flex-fill" wire:loading.attr="disabled" wire:target="decline">
                                    <span wire:loading wire:target="decline" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                    Decline application
                                </button>
                                <button type="button" class="btn btn-secondary" wire:click="cancelDecline">Cancel</button>
                            </div>
                        </form>
                    @endif
                </article>
            @endforeach
        </div>

        @if ($apps->hasPages())
            <div class="mt-3">{{ $apps->links() }}</div>
        @endif
    @endif
</div>
