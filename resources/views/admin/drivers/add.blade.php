@extends('admin.layout.app')
@section('style')
<link href="{{ asset('/adminto/assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" >
<link href="{{ asset('/adminto/assets/plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/dashboard/drivers/' ,'method' => 'Post','files' => true , 'class'=>'class1']) !!}
                @include('admin.drivers.form')
                {!!Form::close() !!}
</div>
<!-- end row -->

@endsection
@section('script')
    <script src="{{ asset('/adminto/assets/plugins/moment/moment.js')}}"></script>
         <script src="{{ asset('/adminto/assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
         <script src="{{ asset('/adminto/assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script>




        $(document).on('submit','.class1',function(){
            if($('select[name=counrty]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
                return false;
            }
            if($('select[name=city]').val() === ''){
                $(".append").append('<p style="color:red;"> this field is required </p>');
                return false;
            }
            if($('select[name=the_car]').val() === ''){
                $(".append").append('<p style="color:red;"> this field is required </p>');
                return false;
            }
        }); 

        
        
    
    jQuery('.datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true
    });
    jQuery('.timepicker').timepicker({
        defaultTIme : false
    });
  









 
    </script>
@endsection