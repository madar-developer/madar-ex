@extends('admin.layout.app')
@section('style')
@endsection
@section('content')
    <div class="row">
        {!! Form::open(['url' => '/dashboard/circulars/', 'method' => 'POST', 'files' => true]) !!}
        @include('admin.circulars.form')
        {!! Form::close() !!}
    </div>
@endsection
@section('script')
@endsection
