@extends('admin.layout.app')
@section('content')
<div class="row">
    {!! Form::open(['url' => '/dashboard/message-templates/', 'method' => 'POST']) !!}
    @include('admin.message-templates.form')
    {!! Form::close() !!}
</div>
@endsection
