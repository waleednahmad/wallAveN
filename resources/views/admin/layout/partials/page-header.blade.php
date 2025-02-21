<div class="content-header">
    <div class="container-fluid">
        <div class="mb-2 row">
            <div class="col-sm-6">
                <h1 class="m-0">
                    {{ $title ?? '' }}
                </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @foreach ($links as $link)
                        @if (!$loop->last)
                            <li class="breadcrumb-item">
                                <a href="{{ $link['url'] }}">
                                    {!! $link['name'] !!}
                                </a>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">
                                {!! $link['name'] !!}
                            </li>
                        @endif
                    @endforeach
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
