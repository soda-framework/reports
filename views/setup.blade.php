@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li><a href="{{ route('soda.reports.index') }}">Reports</a></li>
        <li class="active">Setup: {{ $report->name  }}</li>
    </ol>
@stop

@section('head.title')
    <title>Setup Report</title>
@endsection

@section('content-heading-button')
    <button class="btn btn-info btn-lg" data-submits="#report-form">
        <i class="fa fa-bolt"></i>
        <span>Run</span>
    </button>
@stop

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-bar-chart',
    'title'       => $report->name,
])

@section('content')
    <div class="content-block">
        <form id="report-form" method="GET" action='{{route('soda.reports.view',['id' => $report->id])}}' enctype="multipart/form-data">
            @foreach($report->fields as $field)
                {!! app('soda.form')->field($field)->setModel($report) !!}
            @endforeach
        </form>
    </div>

    <div class="content-bottom">
        <button class="btn btn-info btn-lg" data-submits="#report-form">
            <i class="fa fa-bolt"></i>
            <span>Run</span>
        </button>
    </div>
@endsection
