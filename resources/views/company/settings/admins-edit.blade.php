@extends('company.layout.app')
@section('style')
@endsection
@section('content')

        <div class="box-tebal bg-white">




            <div class="row">
                {{-- <div class="col-sm-12 col-md-4">
                    <div class="sidebar-settings">
                        <ul class="nav nav-pills nav-stacked" role="tablist">
                            @include('admin.settings.sidebar')
                        </ul>
                    </div>

                </div> --}}

                <div class="col-md-12">
                    <div class="col-md-12">

                        <div class="clearfix"></div>

                        <div class="give-per">
                            <form class="form-horizontal m-b-30" action="" method="post" enctype="multipart/form-data">
                            	@csrf
                                <div class="panel panel-default margin-top-20">

                                    <div class="panel-body">
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                  الاسم
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" name="name" value="{{$admin->name}}" class="form-control" placeholder="" required="">
                                            </div>
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                 البريد الالكتروني
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" name="email" value="{{$admin->email}}" class="form-control" placeholder="" required="">
                                            </div>
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                 كلمة المرور
                                            </label>
                                            <div class="col-md-7">
                                                <input type="password" name="password" class="form-control" placeholder="" >
                                            </div>
                                        </div>
                                        <!-- <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                               الجوال
                                            </label>
                                            <div class="col-md-7">
                                                <input type="tel" name="phone" value="{{$admin->phone}}" class="form-control" placeholder="" required="">
                                            </div>
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                               الهوية
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" name="identity" value="{{$admin->identity}}" class="form-control" placeholder="">
                                            </div>
                                        </div> -->
                                        {{-- <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                 نوع الحساب
                                            </label>
                                            <div class="col-md-7">
                                                {!! Form::select("role",AdminTypes(),$admin->role,['class'=>"form-control select2 ", 'style' => "width: 90%; display: inline-block;"])!!}
                                            </div>
                                        </div> --}}
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">الصورة الشخصية</label>
                                            <div class="col-md-7">
                                                <input class="form-control" type="file"  class="file" name="image"/>
                                                <br>

                                            </div>

                                            @if(auth()->User())
                                                @if(auth()->User())
                                                    <label class="control-label col-md-3">الصورة المتواجدة حالياً</label>
                                                    <img src="{{getImage(auth()->User()->image)}}" class="col-md-7" style="width: 300px;">
                                            @endif
                                                @endif
                                        </div>


                                    </div>
                                    <div class="form-group margin-top-15">
                                        <label class="control-label col-md-3">
                                           Webhook Notify URL (Get method)
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text" name="notify_url" value="{{$admin->notify_url}}" class="form-control" placeholder="http://">
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="clearfix">
                                            <div class="col-md-9 col-md-offset-3">
                                                <button type="submit" class="btn blue">
                                                    <i class="fa fa-check"></i>
                                                    موافق
                                                 </button>
                                                <button class="btn default cancel-button-panel">
                                                    <i class="fa fa-times"></i>
                                                    إلغاء الأمر
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
    	$('input[name=email]').attr('value', '');
    	$('input[name=identity]').attr('value', '');
    	$('input[name=phone]').html('');
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

        $('input[name=name]').attr('value', '');
        $('input[name=email]').attr('value', '');
        $('input[name=identity]').attr('value', '');
        $('input[name=phone]').html('');

    	$('form').attr('action', $(this).attr('data-route'));
    	$('input[name=name]').attr('value', $(this).attr('data-name'));
    	$('input[name=email]').attr('value', $(this).attr('data-email'));
    	$('input[name=identity]').attr('value', $(this).attr('data-identity'));
    	$('input[name=phone]').html($(this).attr('data-phone'));

            $('.give-per').removeClass('hidden');
    });


    // $('.buunton-notofication').on('click',function(){
    //     var type = $(this).data('type');
    //     var message = $(this).data('message');
    //     switch(type){
    //         case 'error' : toastr.error(message);  break;
    //         case 'success' : toastr.success(message);  break;
    //         case 'info' : toastr.info(message);  break;
    //         case 'warning' : toastr.warning(message);  break;
    //     }
    //     return false;
    // });
    // TableManageButtons.init();
</script>
@endsection
