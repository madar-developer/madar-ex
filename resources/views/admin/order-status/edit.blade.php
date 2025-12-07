@extends('admin.layout.app')
@section('style')
<link href="{{ asset('/adminto/assets/plugins/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">

@endsection
@section('content')

<div class="row">
    {{--  /////////////////////////////////  --}}
                {!!Form::model($status,['url' => ['/dashboard/order-status/'.$status->id] , 'method' => 'PATCH','files'=>true]) !!}
                @include('admin.order-status.form')
                {!!Form::close() !!}
</div>
<!-- end row -->
@endsection
@section('script')<script src="{{ asset('/adminto/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
<script>
    $('.colorpicker-default').colorpicker({
        format: 'hex'
    });
</script>
@endsection