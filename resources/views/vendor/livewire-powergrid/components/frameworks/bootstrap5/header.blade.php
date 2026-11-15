{{--
    Admin table toolbar (overrides PowerGrid's Bootstrap 5 header).
    One layout for every table: search · Filters button · active filter chips · table actions on the right.
    The filter panel itself opens under this bar (filter.blade.php).
--}}
@php
    $grFilterCols = collect($columns ?? [])->filter(fn ($c) => filled(data_get($c, 'filters')));
    $grHasFilters = config('livewire-powergrid.filter') === 'outside' && $grFilterCols->isNotEmpty();
    $grActive = collect($enabledFilters ?? [])->filter(fn ($f) => isset($f['label']))->values();

    // "Status: Active", "Created: 2026-01-01 to …" — show what each filter is set to, not only its name
    $grChipText = function (array $enabled) use ($grFilterCols) {
        $field = $enabled['field'];
        $col = $grFilterCols->first(fn ($c) => data_get($c, 'filters.field') === $field);
        $filter = (array) data_get($col, 'filters', []);
        $class = (string) data_get($filter, 'className', '');
        $key = (string) data_get($filter, 'key', '');
        $raw = $key !== '' ? ($this->filters[$key][$field] ?? data_get($this->filters, $key . '.' . $field)) : null;
        $value = null;

        if (str_contains($class, 'FilterBoolean')) {
            $value = $raw === 'true' ? data_get($filter, 'trueLabel') : ($raw === 'false' ? data_get($filter, 'falseLabel') : null);
        } elseif (str_contains($class, 'FilterMultiSelect')) {
            $n = count(array_filter((array) $raw, 'filled'));
            $value = $n ? $n . ' selected' : null;
        } elseif (str_contains($class, 'FilterSelect')) {
            $item = collect((array) data_get($filter, 'dataSource', []))
                ->first(fn ($i) => (string) data_get($i, data_get($filter, 'optionValue', 'value')) === (string) $raw);
            $value = $item ? data_get($item, data_get($filter, 'optionLabel', 'label')) : $raw;
        } elseif (str_contains($class, 'Date')) {
            $value = is_array($raw) ? data_get($raw, 'formatted') : null;
        } elseif (is_scalar($raw)) {
            $value = $raw;
        }

        return ucfirst(trim(strip_tags((string) $enabled['label']))) . ($value !== null && $value !== '' ? ': ' . ucfirst((string) $value) : '');
    };
@endphp
<div>
    @includeIf(data_get($setUp, 'header.includeViewOnTop'))

    <div class="adm-tbar">
        @if (data_get($setUp, 'header.searchInput'))
            <div class="adm-tbar-search" x-data>
                <i class="fas fa-search" aria-hidden="true"></i>
                <label class="sr-only" for="pg-search-{{ $tableName }}">Search</label>
                <input
                    id="pg-search-{{ $tableName }}"
                    wire:model.live.debounce.500ms="search"
                    type="search"
                    class="form-control"
                    placeholder="Search…"
                    autocomplete="off"
                >
                @if (filled($this->search))
                    <button type="button" class="adm-tbar-clear" wire:click="$set('search', '')" aria-label="Clear search">
                        <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                @endif
            </div>
        @endif

        @if ($grHasFilters)
            <button
                type="button"
                wire:click="toggleFilters"
                @class(['btn btn-secondary adm-tbar-filter', 'is-on' => $this->showFilters])
                aria-expanded="{{ ($this->showFilters) ? 'true' : 'false' }}"
                aria-controls="pg-filters-{{ $tableName }}"
            >
                <i class="fas fa-sliders-h" aria-hidden="true"></i> Filters
                @if ($grActive->count())
                    <span class="adm-tbar-count">{{ $grActive->count() }}</span>
                @endif
            </button>
        @endif

        @if ($grActive->count())
            <div class="adm-chips" aria-label="Active filters">
                @foreach ($grActive as $grF)
                    <button type="button" class="adm-chip" wire:click.prevent="clearFilter('{{ $grF['field'] }}')" title="Remove this filter">
                        {{ $grChipText($grF) }} <i class="fas fa-times" aria-hidden="true"></i>
                    </button>
                @endforeach
                @if ($grActive->count() > 1)
                    <button type="button" class="adm-chip-clear" wire:click.prevent="clearAllFilters()">Clear all</button>
                @endif
            </div>
        @endif

        <div class="adm-tbar-right">
            <span class="adm-tbar-busy" wire:loading.delay aria-live="polite">
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span class="sr-only">Loading…</span>
            </span>
            <div x-data="pgRenderActions">
                <span class="{{ theme_style($theme, 'table.layout.actions') }}" x-html="toHtml"></span>
            </div>
            @includeWhen(data_get($setUp, 'exportable'), data_get($theme, 'root') . '.header.export')
            @include(data_get($theme, 'root') . '.header.toggle-columns')
            @includeIf(data_get($theme, 'root') . '.header.soft-deletes')
        </div>
    </div>

    @includeWhen(data_get($setUp, 'exportable.batchExport.queues', 0), data_get($theme, 'root') . '.header.batch-exporting')
    @includeWhen($multiSort, data_get($theme, 'root') . '.header.multi-sort')
    @includeIf(data_get($setUp, 'header.includeViewOnBottom'))
    @includeIf(data_get($theme, 'root') . '.header.message-soft-deletes')
</div>
