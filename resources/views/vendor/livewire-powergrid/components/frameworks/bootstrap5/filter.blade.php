{{-- Admin table filter panel (overrides PowerGrid's Bootstrap 5 outside filters). Opened by the toolbar's Filters button. --}}
@props([
    'columns' => null,
    'theme' => null,
    'tableName' => null,
    'filtersFromColumns' => null,
    'showFilters' => false,
])
<div x-data="{ open: @entangle('showFilters').live }">
    <div x-show="open" x-cloak class="adm-fpanel" id="pg-filters-{{ $tableName }}" role="region" aria-label="Filters">
        <div class="adm-fpanel-grid">
            @foreach ($filtersFromColumns as $column)
                @php
                    $filter = data_get($column, 'filters');
                    $title = data_get($column, 'title');
                    $baseClass = data_get($filter, 'baseClass');
                    $className = str(data_get($filter, 'className'));
                @endphp
                <div class="adm-fpanel-item {{ $baseClass }}" wire:key="pg-fp-{{ $tableName }}-{{ data_get($filter, 'field') }}">
                    @if ($className->contains('FilterMultiSelect'))
                        <x-livewire-powergrid::inputs.select
                            :inline="false"
                            :theme="$theme"
                            :table-name="$tableName"
                            :filter="$filter"
                            :title="$title"
                            :initial-values="data_get(data_get($filter, 'multi_select'), data_get($filter, 'field'), [])"
                        />
                    @elseif ($className->contains(['FilterDateTimePicker', 'FilterDatePicker']))
                        @includeIf(theme_style($theme, 'filterDatePicker.view'), [
                            'filter' => $filter,
                            'tableName' => $tableName,
                            'classAttr' => 'w-100',
                            'type' => $className->contains('FilterDateTimePicker') ? 'datetime' : 'date',
                        ])
                    @elseif ($className->contains(['FilterSelect', 'FilterEnumSelect']))
                        @includeIf(theme_style($theme, 'filterSelect.view'), ['filter' => $filter])
                    @elseif ($className->contains('FilterNumber'))
                        @includeIf(theme_style($theme, 'filterNumber.view'), ['filter' => $filter])
                    @elseif ($className->contains('FilterInputText'))
                        @includeIf(theme_style($theme, 'filterInputText.view'), ['filter' => $filter])
                    @elseif ($className->contains('FilterBoolean'))
                        @includeIf(theme_style($theme, 'filterBoolean.view'), ['filter' => $filter])
                    @elseif ($className->contains('FilterDynamic'))
                        <x-dynamic-component
                            :component="data_get($filter, 'component', '')"
                            :attributes="new \Illuminate\View\ComponentAttributeBag(data_get($filter, 'attributes', []))"
                        />
                    @endif
                </div>
            @endforeach
        </div>
        <div class="adm-fpanel-foot">
            <button type="button" class="btn btn-link btn-sm" wire:click.prevent="clearAllFilters()">Reset filters</button>
            <button type="button" class="btn btn-primary btn-sm" x-on:click="open = false">Done</button>
        </div>
    </div>
</div>
