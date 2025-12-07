@extends('company.layout.app')
@section('style')
@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/company/request-drivers/' ,'method' => 'Post','files' => true,'class'=>'class1']) !!}
                @include('admin.request-drivers.form')
                {!!Form::close() !!}
</div>
<!-- end row -->

@endsection
@section('script')
@endsection
