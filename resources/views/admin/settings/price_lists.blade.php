@extends('admin.layout.app')
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
                                        إضافة جديد                 
                                    </div>
                                    <div class="panel-body">

                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                من مدينة :                     
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control" name="from_city_id">
                                                    @foreach(Cities() as $key => $value )
                                                    <option value="{{$key}}"> {{$value}} </option>
                                                    @endforeach
                                                </select>
                                            </div>          
                                        </div>

                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                الي مدينة :                     
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control" name="to_city_id">
                                                    @foreach(Cities() as $key => $value )
                                                    <option value="{{$key}}"> {{$value}} </option>
                                                    @endforeach
                                                </select>
                                            </div>          
                                        </div>

                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                نوع السيارة :                     
                                            </label>
                                            <div class="col-md-7">
                                                <select class="form-control" name="car_type_id">
                                                    @foreach(CarTypeList() as $key => $value )
                                                    <option value="{{$key}}"> {{$value}} </option>
                                                    @endforeach
                                                </select>
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                التكلفة                     
                                            </label>
                                            <div class="col-md-7">
                                                <input type="number" name="price" class="form-control" placeholder="التكلفة">
                                            </div>          
                                        </div>
                                        <div class="form-group margin-top-15">
                                            <label class="control-label col-md-3">
                                                تكلفة السائق الخارجي في حالة اختيارة                      
                                            </label>
                                            <div class="col-md-7">
                                                <input type="number" name="not_employee_price" class="form-control" placeholder="تكلفة السائق الخارجي في حالة اختيارة">
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
                            <legand>قائمة الاسعار</legand>

                        <div role="tabpanel" class="tab-pane" style="overflow: hidden;">
                            <table class="datatable-buttons table table-striped table-bordered" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>من مدينة</th>
                                        <th>الي مدينة</th>
                                        <th>نوع السيارة</th>
                                        <th>التكلفة</th>
                                        <th>التكلفة في حالة السائق الخارجي</th>
                                        <th>تعديل</th>
                                        <th>حذف </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach(AllPriceLists() as $item )
                                    
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td> {{$item->FromCity->name_ar ?? ''}} </td>
                                        <td> {{$item->ToCity->name_ar ?? ''}} </td>
                                        <td> {{$item->CarType->name_ar ?? ''}} </td>
                                        <td> {{$item->price ?? ''}} </td>
                                        <td> {{$item->not_employee_price ?? ''}} </td>
                                        <td> <a href="#" data-route="{{url('/dashboard/settings/price-lists-update/'.$item->id)}}" data-price="{{ $item->price }}" data-not_employee_price="{{ $item->not_employee_price }}" data-from_city_id="{{ $item->from_city_id }}" data-to_city_id="{{ $item->to_city_id }}" data-cat_type_id="{{ $item->cat_type_id }}" data-id="{{$item->id}}" data-education-level="{{$item->car_maker_id}}" class="edit"><i class="fa fa-pencil m-r-10" style="color: #5b69bc;"></i> تعديل</a></td>
                                        <td> 
                                            <a href="{{url('/dashboard/settings/price-lists-delete/'.$item->id)}}" id="delete-btn" ><i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a>
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
    	$('input[name=price]').attr('value', '');
        $('input[name=not_employee_price]').attr('value', '');
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
    	$('input[name=price]').attr('value', $(this).attr('data-price'));
        $('input[name=not_employee_price]').attr('value', $(this).attr('data-not_employee_price'));
        $('option:selected', 'select[name="from_city_id"]').removeAttr('selected');
        //Using the value
        $('select[name="from_city_id"]').find('option[value="'+$(this).attr('data-from_city_id')+'"]').attr("selected",true);
                
        $('option:selected', 'select[name="to_city_id"]').removeAttr('selected');
        //Using the value
        $('select[name="to_city_id"]').find('option[value="'+$(this).attr('data-to_city_id')+'"]').attr("selected",true);
                
        $('option:selected', 'select[name="car_type_id"]').removeAttr('selected');
        //Using the value
        $('select[name="car_type_id"]').find('option[value="'+$(this).attr('data-car_type_id')+'"]').attr("selected",true);
                
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