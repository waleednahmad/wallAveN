{{-- Orders table: status tabs with counts + bulk bar for the selected rows (included on top of the PowerGrid header) --}}
@php
    $grCounts = $this->statusCounts;
    $grTabs = [
        'all' => 'All',
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed',
        'declined' => 'Declined',
        'canceled' => 'Canceled',
    ];
    $grTable = $this->tableName;
@endphp
<nav class="adm-tabs" aria-label="Order status">
    @foreach ($grTabs as $grKey => $grLabel)
        @php $grOn = $this->statusTab === $grKey; @endphp
        <button type="button" wire:click="setStatusTab('{{ $grKey }}')" @class(['adm-tab', 'is-on' => $grOn]) @if ($grOn) aria-current="page" @endif>
            {{ $grLabel }}
            <span class="adm-tab-n">{{ number_format($grKey === 'all' ? array_sum($grCounts) : ($grCounts[$grKey] ?? 0)) }}</span>
        </button>
    @endforeach
</nav>

<div class="adm-bulk" role="region" aria-label="Bulk actions" style="display: none;" x-cloak
    x-data="{
        notify: false,
        get n() { return $store.pgBulkActions.count(@js($grTable)) },
        run(status, label) {
            const ids = [...$store.pgBulkActions.get(@js($grTable))];
            if (!ids.length) return;
            const msg = 'Mark ' + ids.length + (ids.length === 1 ? ' order' : ' orders') + ' as ' + label + '?'
                + (this.notify ? '\nEach dealer will get an email.' : '\nDealers will not be emailed.');
            if (!window.confirm(msg)) return;
            $wire.bulkStatus(status, ids, this.notify);
        }
    }"
    x-show="n > 0">
    <strong class="adm-bulk-count" x-text="n + (n === 1 ? ' order selected' : ' orders selected')"></strong>
    <label class="adm-bulk-notify">
        <input type="checkbox" x-model="notify"> Email the dealers
    </label>
    <div class="adm-bulk-actions">
        <button type="button" class="btn btn-sm adm-bulk-btn" x-on:click="run('processing', 'processing')" wire:loading.attr="disabled" wire:target="bulkStatus">Mark processing</button>
        <button type="button" class="btn btn-sm adm-bulk-btn is-main" x-on:click="run('completed', 'completed')" wire:loading.attr="disabled" wire:target="bulkStatus">
            <span wire:loading wire:target="bulkStatus" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
            Mark completed
        </button>
        <button type="button" class="btn btn-sm adm-bulk-btn" x-on:click="run('declined', 'declined')" wire:loading.attr="disabled" wire:target="bulkStatus">Decline</button>
        <button type="button" class="btn btn-sm adm-bulk-btn" wire:click="clearSelection">Clear</button>
    </div>
</div>
