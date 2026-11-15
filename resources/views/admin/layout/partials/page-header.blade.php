{{--
    Page header used by every admin page: breadcrumb, title, and the page's main action on the right.
    A page sets its action with @section('page-actions') placed BEFORE @section('breadcrumb').
--}}
<div class="content-header adm-pagehead">
    <div class="container-fluid">
        @if (count($links ?? []) > 1)
            <ol class="breadcrumb">
                @foreach ($links as $link)
                    @if (!$loop->last)
                        <li class="breadcrumb-item"><a href="{{ $link['url'] }}">{!! $link['name'] !!}</a></li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{!! $link['name'] !!}</li>
                    @endif
                @endforeach
            </ol>
        @endif
        <div class="adm-pagehead-row">
            <h1 class="m-0">{{ $title ?? '' }}</h1>
            @hasSection('page-actions')
                <div class="adm-pagehead-actions">@yield('page-actions')</div>
            @endif
        </div>
    </div>
</div>
