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
                            <form class="form-horizontal m-b-30" action="" method="post" enctype="multipart/form-data">
                            	@csrf
                                <div class="panel panel-default margin-top-20">
                                    <div class="panel-heading">
                                        إضافة مهمه جديد                 
                                    </div>
                                    <div class="panel-body">
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                اسم  (AR)                      
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" name="name_ar" class="form-control" placeholder="أدخل الاسم هنا (AR)" required>
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                اسم  (EN)                      
                                            </label>
                                            <div class="col-md-7">
                                                <input type="text" name="name_en" class="form-control" placeholder="أدخل الاسم هنا (EN)" required>
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                الصورة                        
                                            </label>
                                            <div class="col-md-7">
                                                <input type="file" name="image" class="form-control">
                                                <img src="" id="img" height="150" width="200">
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
                                                <button class="btn default cancel-button-panel closewindowopened">
                                                    <i class="fa fa-times"></i>
                                                    إلغاء الأمر                            
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <fieldset>
                            <legand>المهمه</legand>

                        <div role="tabpanel" class="tab-pane" style="overflow: hidden;">
                            <table class="datatable-buttons table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th> اسم  (AR)</th>
                                        <th> اسم  (EN)</th>
                                        <th> الصوره</th>
                                        <th>تعديل</th>
                                        <th>حذف </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach(Missions() as $item )
                                    
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td> {{$item->name_ar}} </td>
                                        <td> {{$item->name_en}} </td>
                                        <td> <img src="{{$item->image}}" style="width: 50px; height: 50px;"/> </td>
                                        <td> <a href="#" data-route="{{url('/dashboard/settings/missions-update/'.$item->id)}}" data-name_ar="{{ $item->name_ar }}" data-name_en="{{ $item->name_en }}" data-id="{{$item->id}}"  data-value="{{$item->image}}" class="edit"><i class="fa fa-pencil m-r-10" style="color: #5b69bc;"></i> تعديل</a></td>
                                        <td> 
                                            <a href="{{url('/dashboard/settings/missions-delete/'.$item->id)}}" id="delete-btn" ><i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        </fieldset>



                    </div>
                </div>
            </div>


        </div>            

@endsection
@section('script')

<script>
    $(document).on('change', 'select[name=type]', function(){
        var v = $(this).val();
            $('.classroom').removeClass('hidden');
            $('.region').addClass('hidden');
    });
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
    	$('input[name=name_ar]').attr('value', '');
        $('input[name=name_en]').attr('value', '');
        if (
            $('.give-per').hasClass('hidden')) {
            $('.give-per').removeClass('hidden');
        } else {
            $('.give-per').addClass('hidden');
        }
        return false;
    });
    $('.closewindowopened').on('click', function () {
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
    	$('input[name=name_ar]').attr('value', $(this).attr('data-name_ar'));
        $('input[name=name_en]').attr('value', $(this).attr('data-name_en'));
        $('#img').attr('src', $(this).attr('data-value'));
        $('option:selected', 'select[name="car_maker_id"]').removeAttr('selected');
//Using the value
$('select[name="car_maker_id"]').find('option[value="'+$(this).attr('data-education-level')+'"]').attr("selected",true);
    	
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