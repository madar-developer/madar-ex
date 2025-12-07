@extends('admin.layout.app')
@section('style')
<style>
    .m-r-30 {
    margin-right: 30px;
}
.action-master-wrapper>.action-inner:first-child label {
    color: #216d94;
}
.action-master-wrapper {
    margin-bottom: 30px;
    padding: 10px;
    border: 1px solid #dddddd;
}
.action-master-wrapper-alone {
    padding: 10px;
    border: 1px solid #dddddd;
    margin-bottom: 30px;
}
</style>
@endsection
@section('content')
 

        <div class="box-tebal bg-white">


            <div class="row">

                    <div class="col-md-12">
                       
                        <div class="clearfix m-b-15">
                            <a class="btn blue pull-right create-button-auth-panel  m-l-10" href="#" id="unit-plus">
                                <i class="fa fa-plus"></i> إضافة صلاحية جديدة
                            </a>            
                            <a class="btn blue pull-right" id="grant-button" href="#">
                                <i class="fa fa-retweet"></i> منح صلاحية 
                            </a>        
                        </div>
                        <div class="add-per hidden">
                            <form action="" method="post">
                                <div class="panel panel-default m-t-20">
                                    <div class="panel-heading">
                                         صلاحية                 
                                    </div>
                                    <div class="panel-body">
                                        <div class="bg-white m-r-15">
                                            <div class="clearfix"></div>
            
                                            <div class="row">
                                                    
                                                        @csrf
                                                <div class="content" style="padding: 15px;">
                                                    <div class="col-lg-12">
                                                        <div class="form-horizontal">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">اسم الصلاحية<span></span></label>
                                                                <div class="col-md-9">
                                                                        <input type="text" name="role" class="form-control" placeholder="يرجي ادخال اسم الصلاحية هنا">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
            
                                                    <div class=" col-md-6">
                                                        <div class=" action-master-wrapper with-height">
                                                            
                                                            @php
                                                            $half = round(count(PermissionModels() ) /2);
                                                            $i = 0;
                                                            @endphp
                                                            @foreach(PermissionModels() as $model)
                                                            @php
                                                            $i++;
                                                            @endphp
                                                            <div class="action-inner action-inner-frist">
                                                                <input value="1" class="selecctall" id="1" type="checkbox" name="" data-i="{{$i}}">
                                                                <label for="1">{{__('translation.permission.'.$model)}}</label>
                                                            </div>
                                                            <div class="action-wrapper m-r-30">
                                                                <div class="action-wrapper m-r-30">
                                                                    <div class="action-inner">
                                                                        <input id="3" value="1" type="checkbox" name="<?=$model?>_show" class="inside-{{$i}}">
                                                                        <label for="3">عرض </label>
                                                                    </div>
                                                                </div>
                                                                <div class="action-wrapper m-r-30">
                                                                    <div class="action-inner">
                                                                        <input id="4" value="1" type="checkbox" name="<?=$model?>_edit" class="inside-{{$i}}">
                                                                        <label for="4">تعديل </label>
                                                                    </div>
                                                                </div>
                                                                <div class="action-wrapper m-r-30">
                                                                    <div class="action-inner">
                                                                        <input id="5" value="1" type="checkbox" name="<?=$model?>_delete" class="inside-{{$i}}">
                                                                        <label for="5">حذف </label>
                                                                    </div>
                                                                </div>
                                                                <div class="action-wrapper m-r-30">
                                                                    <div class="action-inner">
                                                                        <input id="6" value="1" type="checkbox" name="<?=$model?>_add" class="inside-{{$i}}">
                                                                        <label for="6">إضافة  جديد</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if($i % $half == 0 && count(PermissionModels()) > $i)
                                                                    </div>
                                                                </div>
                            
                                                                <div class=" col-md-6">
                                                                    <div class=" action-master-wrapper with-height">
                                                            @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
            
            
                                                    <div class=" col-md-4">
                                                        <div class=" action-master-wrapper-alone">
                                                            <div class="action-inner">
                                                                <input value="41" class="selecctall" id="41" type="checkbox" name="notification_show">
                                                                <label for="41"> ارسال التنبيهات </label>
                                                            </div>
                                                        </div>
                                                    </div>
            
                                                    <div class=" col-md-4">
                                                        <div class=" action-master-wrapper-alone">
                                                            <div class="action-inner">
                                                                <input value="41" class="selecctall" id="41" type="checkbox" name="settings_show">
                                                                <label for="41"> الاعدادات </label>
                                                            </div>
                                                        </div>
                                                    </div>
            
                                                    <div class=" col-md-4">
                                                        <div class=" action-master-wrapper-alone">
                                                            <div class="action-inner">
                                                                <input value="41" class="selecctall" id="41" type="checkbox" name="finance_show">
                                                                <label for="41"> الادارة المالية </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class=" col-md-4">
                                                        <div class=" action-master-wrapper-alone">
                                                            <div class="action-inner">
                                                                <input value="41" class="selecctall" id="41" type="checkbox" name="contact_us_show">
                                                                <label for="41"> استفسارات الزوار </label>
                                                            </div>
                                                        </div>
                                                    </div>
            
                                                </div>
                                            </div>
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

                        <div class="clearfix"></div>

                        <div class="give-per hidden">
                                <div class="panel panel-default margin-top-20">
                                    <div class="panel-heading">
                                        منح صلاحية                 
                                    </div>
                            <form action="{{url('/dashboard/settings/user-role')}}" method="post" id="user-role" class="form-horizontal m-b-30" >
                                @csrf
                                    <div class="panel-body">
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                اختر المستخدم                        
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control" name="admin_id">
                                                    @foreach(Admins() as $key => $value )
                                                    <option value="{{$key}}"> {{$value}} </option>
                                                    @endforeach
                                                </select>
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                اختر الصلاحية                        
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control" name="role_id">
                                                    @foreach(RolesList() as $key => $value )
                                                    <option value="{{$key}}"> {{$value}} </option>
                                                    @endforeach
                                                </select>
                                            </div>          
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
                            </form>
                                </div>
                        </div>

                        <div role="tabpanel" class="tab-pane  " style="overflow: hidden;">
                            <table class="datatable-buttons table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الصلاحية</th>
                                        <th>تعديل</th>
                                        <th>حذف </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach(Roles() as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <a href="#" data-route="{{url('/dashboard/settings/permissions-update/'.$role->id)}}" data-name="{{ $role->name }}" class="edit-permission" data="{{$role->Permission()->pluck('permission')}}">تعديل</a>
                                        </td>
                                        <td> <a href="{{url('/dashboard/settings/permissions-delete/'.$role->id)}}" id="delete-btn" ><i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a></td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <div role="tabpanel" class="tab-pane  " style="overflow: hidden;">
                            <h4>صلاحيات المستخدمين</h4>
                            <table class="datatable-buttons table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم  المستخدم</th>
                                        <th>بريد المستخدم</th>
                                        <th>الصلاحية</th>
                                        <th>تعديل</th>
                                        <th>حذف </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($admin_roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->User->name ?? '' }}</td>
                                        <td>{{ $role->User->email ?? '' }}</td>
                                        <td>{{ $role->Role->name ?? '' }}</td>
                                        <td>
                                         <a class="user-role" data-role="{{ $role->Role->id ?? '' }}" data-user="{{ $role->User->id ?? '' }}" data-route="">تعديل</a>
                                        </td>
                                        <td> <a href="{{url('/dashboard/settings/user-permissions-delete/'.$role->id)}}" id="delete-btn" ><i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a></td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
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
            $('.add-per form').attr('action', '');
            $('.add-per input[name=role]').attr('value', '');
            $('.add-per input[type=checkbox]').prop('checked', false);
        return false;
    });

    $('#grant-button').on('click', function () {
    	// $('form').attr('action', '');
    	$('input[name=name]').attr('value', '');
    	$('textarea[name=description]').html('');
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
    	$('textarea[name=description]').html($(this).attr('data-description'));
    	
            $('.give-per').removeClass('hidden');
    });
    
    $(document).ready(function(){

        $('.user-role').on('click', function(e){
            //remove selected one
$('option:selected', 'select[name="role_id"]').removeAttr('selected');
//Using the value
$('select[name="role_id"]').find('option[value="'+$(this).attr('data-role')+'"]').attr("selected",true);
            //remove selected one
$('option:selected', 'select[name="admin_id"]').removeAttr('selected');
//Using the value
$('select[name="admin_id"]').find('option[value="'+$(this).attr('data-user')+'"]').attr("selected",true);
            //$('form#user-role > select[name=role_id]').val($(this).attr('data-role')).change();
            //$('form#user-role > select[name=admin_id]').val($(this).attr('data-user')).change();
            
                $('.give-per').removeClass('hidden');
        });
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
    // TableManageButtons.init();
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $('.edit-permission').on('click', function(){
            var list = $.parseJSON( $(this).attr('data'));
            $.each(list, function( index, value ) {
                $('input[name='+value+']').prop('checked', true);
            });
            console.log(list);
            $('.add-per form').attr('action', $(this).attr('data-route'));
            $('.add-per input[name=role]').attr('value', $(this).attr('data-name'));
            $('.add-per').removeClass('hidden');
        });

        $('.selecctall').on('click', function(){
            var i = $(this).attr('data-i');
            if ($(this).is(':checked') ) {
                // 
                $('.inside-'+i).prop('checked', true);
            } else {
                // 
                $('.inside-'+i).prop('checked', false);
            }
        } );
    });
</script>
@endsection