@extends(soda_cms_view_path('layouts.inner'))

@section('content')
    <div class="content-top">
        {!! $filter !!}
    </div>

    <div class="content-block">
        {!!  $grid  !!}
    </div>
@endsection
