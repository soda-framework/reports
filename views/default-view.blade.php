@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.reports.index') }}">Reports</a></li>
        <li class="active">{{ isset($report) ? $report->name : 'Report' }}</li>
    </ol>
@stop

@section('head.title')
    <title>{{ isset($report) ? $report->name : 'Report' }}</title>
@endsection

@section('content-heading-button')
    @if(isset($report))
    <a class="btn btn-warning btn-lg" href="{{ route('soda.reports.export', $report->id) }}">
        <i class="fa fa-download"></i>
        <span>Export</span>
    </a>
    @endif
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-bar-chart',
    'title'       => isset($report) ? $report->name : 'Report',
])

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
