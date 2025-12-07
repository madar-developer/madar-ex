@extends('admin.layout.app')
@section('style')
@endsection
@section('content')
<div class="row">
    {{--  //////////////////////////////////  --}}
                {!!Form::open( ['url' => '/dashboard/partners/' ,'method' => 'Post','files' => true,'class'=>'class1']) !!}
                @include('admin.partners.form')
                {!!Form::close() !!}
</div>
<!-- end row -->

@endsection
@section('script')
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
           if($('select[name=type_of_partnerder]').val() === ''){
            $(".append").append('<p style="color:red;"> this field is required </p>');
               return false;
           }
           if($('select[name=the_city_where_you_work]').val() === ''){
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
{{-- jQuery('.timepicker2').timepicker({
    showMeridian : false
});
jQuery('.timepicker3').timepicker({
    minuteStep : 15
}); --}}
</script>
@endsection