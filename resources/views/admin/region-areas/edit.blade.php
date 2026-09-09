@extends('admin.layout.app')
@section('style')
<style type="text/css">
    #mapCanvas { width: 100%; height: 480px; margin-top: 10px; }
</style>
@endsection
@section('content')
<div class="row">
    {!! Form::model($area, ['url' => ['/dashboard/region-areas/' . $area->id], 'method' => 'PATCH', 'id' => 'region-area-form']) !!}
    @include('admin.region-areas.form')
    {!! Form::close() !!}
</div>
@endsection
@section('script')
@include('admin.region-areas.map-script')
@endsection
