@extends('admin.layout.app')
@section('content')
<div class="row">
    {!! Form::model($template, ['url' => ['/dashboard/message-templates/'.$template->id], 'method' => 'PATCH']) !!}
    @include('admin.message-templates.form')
    {!! Form::close() !!}
</div>
@endsection
