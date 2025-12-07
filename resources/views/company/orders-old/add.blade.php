@extends('company.layout.app')
@section('style')
<link href="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}"  rel="stylesheet">
<link href="{{ asset('/adminto/assets/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet" >
<link href="{{ asset('/adminto/assets/plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">
@endsection
@section('header')
<!-- Page title -->
                            <ul class="nav navbar-nav navbar-left">
                                <li>
                                    <button class="button-menu-mobile  ">
                                        <i class="fa fa-bars"></i>
                                    </button>
                                </li>
                                <li>
                                    <h4 class="page-title"> اضافة متجر جديد   </h4>
                                </li>
                            </ul>

@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/company/company-orders/' ,'method' => 'Post','files' => true,'class'=>'class1']) !!}
                @include('company.orders.form')
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
        if($('select[name=driver]').val() === ''){
         $(".append").append('<p style="color:red;"> this field is required </p>');
            return false;
        }
        if($('select[name=suggested_drivers]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=receiving_city]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=same_zone]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=type_of_car]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=shipping_size]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=case]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=return_package]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=plug_type]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=payment_method]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=city]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=store_or_company]').val() === ''){
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
