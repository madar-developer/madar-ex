@extends('admin.layout.app')
@section('style')
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box text-left">
                {!!Form::open( ['url' => '/dashboard/notifications/' ,'method' => 'Post','files' => true]) !!}
                @include('admin.notifications.form')
                {!!Form::close() !!}
                
                <div class="clearfix"></div>
        
        </div><!-- end row -->
    </div><!-- end col -->
</div>
<!-- end row -->
@endsection
@section('script')
@endsection