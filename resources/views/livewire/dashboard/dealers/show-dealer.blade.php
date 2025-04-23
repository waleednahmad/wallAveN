<div>
    <h3>
        {{ $dealer?->name ?? '-' }}
    </h3>
    <p class="card-text">
        <strong>
            Email:
        </strong>
        <br>
        {{ $dealer?->email ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            Phone:
        </strong>
        <br>
        {{ $dealer?->phone ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            Company Name:
        </strong>
        <br>
        {{ $dealer?->company_name ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            Tax ID:
        </strong>
        <br>
        {{ $dealer?->tax_id ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            Address:
        </strong>
        <br>
        {{ $dealer?->address ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            City:
        </strong>
        <br>
        {{ $dealer?->city ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            State:
        </strong>
        <br>
        {{ $dealer?->state ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            Zip Code:
        </strong>
        <br>
        {{ $dealer?->zip_code ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            Years in Business:
        </strong>
        <br>
        {{ $dealer?->years_in_business ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            Website:
        </strong>
        <br>
        <a href="{{ $dealer?->website ? (parse_url($dealer->website, PHP_URL_SCHEME) ? $dealer->website : '//' . $dealer->website) : '#' }}"
            target="_blank">
            {{ $dealer?->website ? strtolower(parse_url($dealer->website, PHP_URL_HOST) . parse_url($dealer->website, PHP_URL_PATH)) : '-' }}
        </a>
    </p>
    <p class="card-text">
        <strong>
            Business Type:
        </strong>
        <br>
        {{ $dealer?->business_type ?? '-' }}
    </p>
    <p class="card-text">
        <strong>
            Resale Certificate:
        </strong>
        <br>
        @if ($dealer?->resale_certificate && file_exists(public_path($dealer?->resale_certificate)))
            <a href="{{ asset($dealer?->resale_certificate) }} " target='_blank'>
                View
            </a>
        @else
            -
        @endif
    </p>
    <p class="card-text">
        <strong>
            Message:
        </strong>
        <br>
        {{ $dealer?->message ?? '-' }}
    </p>
    <div class="row">
        <div class="col-md-6">
            <p class="card-text">
                <strong>
                    Status:
                </strong>
                <br>
                @if ($dealer?->status)
                    <span class="badge bg-success">
                        Active
                    </span>
                @else
                    <span class="badge bg-danger">
                        Inactive
                    </span>
                @endif
            </p>
        </div>

        <div class="col-md-6">
            <p class="card-text">
                <strong>
                    Approved:
                </strong>
                <br>
                @if ($dealer?->is_approved)
                    <span class="badge bg-success">
                        Yes
                    </span>
                @else
                    <span class="badge bg-danger">
                        No
                    </span>
                @endif
            </p>
        </div>

        <div class="mt-3 col-md-6">
            <p class="card-text">
                <strong>
                    Approved At:
                </strong>
                <br>
                {{ $dealer?->approved_at ?? '-' }}
            </p>
        </div>

        <div class="mt-3 col-md-6">
            <p class="card-text">
                <strong>
                    Created At:
                </strong>
                <br>
                {{ $dealer?->created_at ?? '-' }}
            </p>
        </div>
    </div>
</div>
