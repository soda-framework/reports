@extends(soda_cms_view_path('layouts.inner'))

@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ route('soda.home') }}">Home</a></li>
        <li class="active">Reports</li>
    </ol>
@stop

@section('head.cms')
    <title>Reports</title>
@endsection

@include(soda_cms_view_path('partials.heading'), [
    'icon'        => 'fa fa-bar-chart',
    'title'       => 'Reports',
])

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!!  $grid  !!}
    </div>
@endsection
