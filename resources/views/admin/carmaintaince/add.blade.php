@extends('admin.layout.app')
@section('style')
<link href="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}"  rel="stylesheet">
<link href="{{ asset('/adminto/assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" >
<link href="{{ asset('/adminto/assets/plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/dashboard/carmaintaince/' ,'method' => 'Post','files' => true,'class'=>'class1']) !!}
                @include('admin.carmaintaince.form')
                {!!Form::close() !!}
</div>
<!-- end row -->

@endsection
@section('script')
    <script src="{{ asset('/adminto/assets/plugins/moment/moment.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
         <script src="{{ asset('/adminto/assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
         <script src="{{ asset('/adminto/assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script>


    $(document).on('submit','.class1',function(){
        if($('select[name=the_color]').val() === ''){
         $(".append").append('<p style="color:red;"> this field is required </p>');
            return false;
        }
        if($('select[name=manufacturing_year]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=type_of_car]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=the_city_where_you_work]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
    }); 

    

    // Date Picker
jQuery('.datepicker').datepicker();
jQuery('.datepicker-autoclose').datepicker({
    autoclose: true,
    todayHighlight: true
});
jQuery('.timepicker').timepicker({
    defaultTIme : false
});
{{-- jQuery('.timepicker2').timepicker({
    showMeridian : false
});
jQuery('.timepicker3').timepicker({
    minuteStep : 15
}); --}}
</script>
@endsection