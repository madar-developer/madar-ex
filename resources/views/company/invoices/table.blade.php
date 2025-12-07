@extends('admin.layout.app')
@section('style')
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
        <h4 class="page-title">
            عرض الفواتير </h4>
    </li>
</ul>
<ul class="nav navbar-nav navbar-right">
    <li>
        <div class="add-btn">
            <a href="{{ url('/dashboard/invoices/table') }}" type="button"
                class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i>
                اضافة </a>
        </div>
    </li>

</ul>
<ul class="nav navbar-nav navbar-right">
    <li>

    </li>
</ul>
@endsection
@section('content')


        <div class="col-md-12">
            <div class="card-box">
            <div class="row">


                <div class="col-lg-2">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" name="serial" value="" class="form-control" placeholder="  اسم الشركه">
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-2">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" name="refrence_no" value="" class="form-control" placeholder=" من">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" name="refrence_no" value="" class="form-control" placeholder=" الي">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" name="refrence_no" value="" class="form-control" placeholder=" اسم المتجر">
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-horizontal m-b-15">
                        <button type="button" onclick="$(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-search"></i> بحث</button>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-horizontal">
                        <a href="{{url('/dashboard/orders')}}" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-trash"></i> مسح خيارات البحث</a>
                    </div>
                </div>
                </div>
                </div>
            </div>
            </br>
        </br>


<div class="card-box">
                <div class="row">

                    <div class="col-sm-12">
                            <div class="card-box text-left">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div class="box-tebal">

                                                <div role="tabpanel" class="tab-pane " style="overflow-x: scroll;">
                                                    <table id="datatable-buttons" class="table table-striped table-bordered">
                                                        <thead>

                                                            <tr>
                                                                <th>#</th>
                                                                <th>   رقم الشحنه </th>
                                                                <th>   قيمه التوصيل   </th>
                                                                <th>  قيمه البضاعه  </th>
                                                                <th>    الوقت </th>
                                                                <th>   الحاله	   </th>



                                                            </tr>
                                                        </thead>

                                                        <tbody>


                                                            <tr>
                                                                <td> </td>
                                                                <td></td>
                                                                <td> </td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>

                                                                  </tr>
                                                                  </tbody>
                                                    </div>


                                                    @endsection
                                                    @section('script')


                                                    <script type="text/javascript">
                                                        // $('#datatable-0, #datatable-1, #datatable-2, #datatable-3, #datatable-4').DataTable( {
                                                        //     "bLengthChange" : false, //thought this line could hide the LengthMenu
                                                        //     "bInfo":false,
                                                        // } );

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

                                                        $(document).on('click', '.client-info', function () {
                                                            $.get("{{url('/dashboard/user-info')}}" + "/" + $(this).attr('data-id'), function (data) {
                                                                $('#client-info-box').html(data);
                                                            });
                                                        });

                                                    </script>
                                                    @endsection
