@extends(soda_cms_view_path('layouts.inner'))

@section('content-heading-button')
    @if(isset($report))
        <a class="btn btn-warning btn-lg" href="{{ route('soda.reports.export', $report->id) }}?{{ http_build_query(Request::query()) }}">
            <i class="fa fa-download"></i>
            <span>Export</span>
        </a>
    @endif
@stop

@section('content')
    @if(isset($filter))
        <div class="content-top">
            {!! $filter !!}
        </div>
    @endif

    <div class="content-block">
        {!!  $grid  !!}
    </div>
@endsection
