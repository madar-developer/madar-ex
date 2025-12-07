@extends('company.layout.app')
@section('style')
@endsection
@section('content')

<div class="row">
                {!!Form::model($request_driver , ['url' => ['/company/request-drivers/'.$request_driver->id] , 'method' => 'PATCH','files'=>true]) !!}
                @include('admin.request-drivers.form')
                {!!Form::close() !!}
</div>
@endsection
@section('script')
@endsection
