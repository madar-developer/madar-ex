@extends('admin.layout.app')
@section('style')
    <link href="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}"
        rel="stylesheet">
    <link href="{{ asset('/adminto/assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">
    <link href="{{ asset('/adminto/assets/plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
    <link href="{{ asset('/adminto/assets/plugins/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">

@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
    {!!Form::open( ['url' => '/dashboard/order-status/' ,'method' => 'POST','files' => true]) !!}
    @include('admin.order-status.form')
    {!!Form::close() !!}
</div>
<!-- end row -->

@endsection
@section('script')
<script src="{{ asset('/adminto/assets/plugins/moment/moment.js')}}"></script>
<script src="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{ asset('/adminto/assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{ asset('/adminto/assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{ asset('/adminto/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
    <script>
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
    </script>
@endsection
