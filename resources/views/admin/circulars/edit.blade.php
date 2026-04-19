@extends('admin.layout.app')
@section('style')
@endsection
@section('content')
    <div class="row">
        {!! Form::model($circular, ['url' => ['/dashboard/circulars/' . $circular->id], 'method' => 'PATCH', 'files' => true]) !!}
        @include('admin.circulars.form')
        {!! Form::close() !!}
    </div>
@endsection
@section('script')
@endsection
