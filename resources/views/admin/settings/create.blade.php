@extends('admin.layout.app')
@section('style')
@endsection
@section('content')
 
        <div class="box-tebal bg-white">
            <div class="row">

                <div class="col-md-12">
                    <div class="col-md-12">
                       
     
                        <div class="clearfix"></div>

                        <div class="give-per ">
                            <form class="form-horizontal m-b-30" action="" method="post">
                            	@csrf
                                <div class="panel panel-default margin-top-20">
                                    <div class="panel-heading">
                                         اعدادات الموقع                 
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                البريد الالكتروني                       
                                            </label>
                                            <div class="col-md-7">
                                                {!! Form::email("setting[email]",(isset($setting['email']))? $setting['email'] : null,['class'=>'form-control'])!!}
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                الموبايل                   
                                            </label>
                                            <div class="col-md-7">
                                                {!! Form::text("setting[phone]",(isset($setting['phone']))? $setting['phone'] : null,['class'=>'form-control'])!!}
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                      facebook            
                                            </label>
                                            <div class="col-md-7">
                                                {!! Form::url("setting[facebook]",(isset($setting['facebook']))? $setting['facebook'] : null,['class'=>'form-control'])!!}
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                      twitter            
                                            </label>
                                            <div class="col-md-7">
                                                {!! Form::url("setting[twitter]",(isset($setting['twitter']))? $setting['twitter'] : null,['class'=>'form-control'])!!}
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                      instagram            
                                            </label>
                                            <div class="col-md-7">
                                                {!! Form::url("setting[instagram]",(isset($setting['instagram']))? $setting['instagram'] : null,['class'=>'form-control'])!!}
                                            </div>          
                                        </div>


                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                سياسة الاستخدام              
                                            </label>
                                            <div class="col-md-7">
                                                {!! Form::textarea("setting[privacy]",(isset($setting['privacy']))? $setting['privacy'] : null,['class'=>'form-control', 'style' => 'width: 100%; height: 150px; border: 1px solid #E3E3E3;border-radius: 4px;color: #565656;padding: 7px 12px;'])!!}
                                            </div>          
                                        </div>


                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                من نحن              
                                            </label>
                                            <div class="col-md-7">
                                                {!! Form::textarea("setting[about]",(isset($setting['about']))? $setting['about'] : null,['class'=>'form-control', 'style' => 'width: 100%; height: 150px; border: 1px solid #E3E3E3;border-radius: 4px;color: #565656;padding: 7px 12px;'])!!}
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                القواعد و الاحكام              
                                            </label>
                                            <div class="col-md-7">
                                                {!! Form::textarea("setting[terms]",(isset($setting['terms']))? $setting['terms'] : null,['class'=>'form-control', 'style' => 'width: 100%; height: 150px; border: 1px solid #E3E3E3;border-radius: 4px;color: #565656;padding: 7px 12px;'])!!}
                                            </div>          
                                        </div>


                                    </div>
                                    <div class="panel-footer">
                                        <div class="clearfix">
                                            <div class="col-md-9 col-md-offset-3 text-center">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="fa fa-check"></i>
                                                    حفظ                           
                                                 </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>


        </div>            

@endsection
@section('script')

<script>
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function () {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }

     $('#unit-plus').on('click', function () {
        if (
            $('.add-per').hasClass('hidden')) {
            $('.add-per').removeClass('hidden');
        } else {
            $('.add-per').addClass('hidden');
        }
        return false;
    });

    $('#grant-button').on('click', function () {
    	$('form').attr('action', '');
    	$('input[name=name]').attr('value', '');
    	$('input[name=country]').attr('value', '');
    	$('input[name=city]').attr('value', '');
    	$('textarea[name=details]').html('');
        if (
            $('.give-per').hasClass('hidden')) {
            $('.give-per').removeClass('hidden');
        } else {
            $('.give-per').addClass('hidden');
        }
        return false;
    });


    
    $(document).on('click', '.edit', function(e){
    	e.preventDefault();
    	$('form').attr('action', $(this).attr('data-route'));
    	$('input[name=name]').attr('value', $(this).attr('data-name'));
    	$('input[name=country]').attr('value', $(this).attr('data-country'));
    	$('input[name=city]').attr('value', $(this).attr('data-city'));
    	$('textarea[name=details]').html($(this).attr('data-details'));
    	
            $('.give-per').removeClass('hidden');
    });


    $('.buunton-notofication').on('click',function(){
        var type = $(this).data('type');     
        var message = $(this).data('message');     
        switch(type){
            case 'error' : toastr.error(message);  break;
            case 'success' : toastr.success(message);  break;
            case 'info' : toastr.info(message);  break;
            case 'warning' : toastr.warning(message);  break; 
        }
        return false;
    });
    TableManageButtons.init();
</script>

@endsection