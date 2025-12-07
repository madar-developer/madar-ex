@extends('company.layout.app')
@section('style')
@endsection
@section('content')
 
        <div class="box-tebal bg-white">

            <div class="title">
                <h4>
                    <i class="fa fa-cog" aria-hidden="true"></i>
                   إعدادات عامة
                </h4>
            </div>


            <div class="row">
                <div class="col-sm-12 col-md-2">
                    <div class="sidebar-settings">
                        <ul class="nav nav-pills nav-stacked" role="tablist">
                            @include('admin.settings.sidebar')
                        </ul>
                    </div>

                </div>

                <div class="col-md-10">
                    <div class="col-md-12">
                       
                        <div class="clearfix m-b-15">         
                            <a class="btn blue pull-right" id="grant-button" href="#">
                                <i class="fa fa-plus"></i>اضافة  جديد
                            </a>        
                        </div>
     
                        <div class="clearfix"></div>

                        <div class="give-per hidden">
                            <form class="form-horizontal m-b-30" action="" method="post">
                            	@csrf
                                <div class="panel panel-default margin-top-20">
                                    <div class="panel-heading">
                                        البيانات                 
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                  الاسم                     
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" name="name" class="form-control" placeholder="" required="">
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                 البريد الالكتروني                      
                                            </label>
                                            <div class="col-md-7">
                                                <input type="email" name="email" class="form-control" placeholder="" required="">
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                 كلمة المرور                      
                                            </label>
                                            <div class="col-md-7">
                                                <input type="password" name="password" class="form-control" placeholder="" required="">
                                            </div>          
                                        </div>
                                        <!-- <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                               الهوية                        
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" name="identity" class="form-control" placeholder="">
                                            </div>          
                                        </div> -->


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

                        <div role="tabpanel" class="tab-pane" style="overflow: hidden;">
                            <table class="datatable-buttons table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> اسم </th>
                                        <th> البريد الالكتروني </th>
                                        <!-- <th>  نوع الحساب</th> -->
                                        <th>تعديل</th>
                                        <th>حذف </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach(\App\Models\Admin::get() as $item )
                                    
                                    <tr>
                                        <td>1</td>
                                        <td> {{$item->name}} </td>
                                        <td> {{$item->email}} </td>
                                        <!-- <td> {{__('admin.' . $item->role)}} </td> -->
                                        <td> <a href="#" data-route="{{url('/dashboard/settings/admins-update/'.$item->id)}}" data-name="{{ $item->name }}" data-email="{{ $item->email }}" data-identity="{{ $item->identity }}" data-phone="{{ $item->phone }}" data-id="{{$item->id}}" data-role="{{$item->role}}" class="edit"><i class="fa fa-pencil m-r-10" style="color: #5b69bc;"></i> تعديل</a></td>
                                        <td> 
                                            <a href="{{url('/dashboard/settings/admins-delete/'.$item->id)}}" id="delete-btn" ><i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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


    $(document).ready(function(){
    $('.edit').on('click', function(e){
    	e.preventDefault();

        $('input[name=name]').attr('value', '');
        $('input[name=email]').attr('value', '');
        $('input[name=identity]').attr('value', '');
        $('input[name=phone]').html('');
        
    	$('form').attr('action', $(this).attr('data-route'));
    	$('input[name=name]').attr('value', $(this).attr('data-name'));
    	$('input[name=email]').attr('value', $(this).attr('data-email'));
    	$('input[name=identity]').attr('value', $(this).attr('data-identity'));
    	$('input[name=phone]').attr('value', $(this).attr('data-phone'));
        $('option:selected', 'select[name=role]').removeAttr('selected');
        //Using the value
        $('select[name=role]').find('option[value="' + $(this).attr('data-role') + '"]').attr("selected",true);
    	
            $('select[name=role]').val($(this).attr('data-role')).change();
            $('.give-per').removeClass('hidden');
    });
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