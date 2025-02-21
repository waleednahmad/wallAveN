@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp


<div class="pagination-area">
    @if ($paginator->hasPages())
        <div class="pagination-text">
            {!! __('Showing') !!}
            <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
            {!! __('to') !!}
            <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
            {!! __('of') !!}
            <span class="fw-semibold">{{ $paginator->total() }}</span>
            {!! __('results') !!}
        </div>
        <div class="pagination-button">
            <ul>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li>
                        <a aria-hidden="true">@lang('pagination.previous')</a>
                    </li>
                @else
                    <li>
                        <a dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled">
                            @lang('pagination.previous')
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li  aria-disabled="true"><span class="arteem-page-link">{{ $element }}</span></li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li  wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}" aria-current="page">
                                        <a style="background-color: #000; color: #fff;" >
                                            {{ $page }} 
                                        </a>
                                    </li>
                                @else
                                    <li  wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}"><a  wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li >
                        <a dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled">
                            @lang('pagination.next')
                        </a>
                    </li>
                @else
                    <li aria-disabled="true">
                        <a aria-hidden="true">
                            @lang('pagination.next')
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    @endif
</div>