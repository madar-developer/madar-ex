@extends('admin.layout.app')
@section('style')
<style>
    .card ul{
        list-style: none;
    }
    .card .panel-footer ul li{
        display: inline-block;
    }
</style>
@endsection
@section('header')
<!-- Page title -->

        <div class="add-btn">
            <a href="{{ url('/dashboard/cars/create') }}" type="button" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> اضافة </a>
        </div>

@endsection
@section('content')


<div class="row">

    <div class="col-sm-12">
        <div class="card-box">
            <form action="" method="get">
                @csrf
                <div class="row">
                    <div class="col-md-12 part-top">
                        <div class="row">

                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="row">



                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="name"
                                                value="{{(array_key_exists('name', $search))? $search['name'] : ''}}"
                                                class="form-control" placeholder="كلمات البحث ">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{-- <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="created_at"
                                                value="{{(array_key_exists('created_at', $search))? $search['created_at'] : ''}}"
                                                class="form-control" placeholder="تاريخ الانشاء ">
                                        </div>
                                    </div>

                                </div>
                            </div> --}}


                            <div class="row btns-row">
                                    <button type="button" onclick="$(this).closest('form').find('#excel').remove(); $(this).closest('form').submit();" class="btn btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-search"></i> بحث</button>

                                    <button type="button" target="_blank" onclick="$(this).closest('form').prepend(`<input name='excel' id='excel' type='hidden' value='1' />`); $(this).closest('form').submit();" class="btn btn-sm btn-success waves-effect waves-light b-t-10 b-b-10">تصدير لExcel</button>

                                    <a href="{{url('/dashboard/drivers')}}"
                                        class="btn btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                                            class="fa fa-trash"></i> مسح خيارات البحث</a>
                                    <p>
                                        <ul style="list-style: none;">

                                            <li>
                                                <strong>اجمالي الصيانة : </strong> {{\App\Models\CarMaintenance::sum('cost')}} ريال
                                            </li>
                                        </ul>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="row">

                    <div class="col-sm-12">
                            <div class="card-box text-left cars-card">
                                    <div class="row">

                                        @foreach($cars as $item)
                                            <div class="col-md-4">
                                                <div class="card panel panel-default ">
                                                    <div class="head panel-heading">
                                                        <ul>

                                                            <li>
                                                                <strong>اجمالي الصيانة : </strong> {{$item->CarMaintenance()->sum('cost')}} ريال
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="body panel-body">
                                                        <a href="{{route('cars.show', $item->id)}}">
                                                            <ul>

                                                                <li>
                                                                    <strong>اسم السيارة : </strong> {{$item->name}}
                                                                </li>
                                                                <li>
                                                                    <strong>رقم الهيكل : </strong>{{$item->structure_no}}
                                                                </li>
                                                                <li>
                                                                    <strong>اللون : </strong> {{$item->color}}
                                                                </li>
                                                                <li>
                                                                    <strong>سنة الصنع : </strong>{{$item->manufacturing_year}}
                                                                </li>
                                                                <li>
                                                                    <strong>نوع السيارة : </strong>{{$item->type}}
                                                                </li>
                                                                <li>
                                                                    <strong>المدينة التى تعمل بها السيارة : </strong>{{$item->work_city}}
                                                                </li>
                                                                <li>
                                                                    <strong>تاريخ انتهاء الاستماره : </strong> {{$item->license_expiration_date}}
                                                                </li>
                                                                <li>
                                                                    <strong>تاريخ انتهاء الاستماره هجرى :</strong> {{$item->license_expiration_date_hijri}}
                                                                </li>
                                                                @if (auth('admin')->user()->role == 'admin')
                                                                    <li>
                                                                        <strong> تم الاضافه بواسطه : </strong>{{$item->BranchData->Admin->name ?? ''  }}
                                                                    </li>
                                                                @endif

                                                            </ul>
                                                        </a>
                                                    </div>
                                                    <div class="options panel-footer">
                                                        <ul>
                                                            <li>
                                                                <a href="/dashboard/carmaintaince/create?carid={{$item->id}}" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-plus"></i> اضافة صيانه </a>

                                                            </li>
                                                            <li>
                                                                <a href="/dashboard/cars/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> تعديل </a>

                                                            </li>
                                                            <li>
                                                                <a href="{{route('cars.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>

                                                            </li>
                                                        </ul>
                                                    </div>

                                                </div>

                                            </div>
                                        @endforeach
                                        @if (0)
                                        <div class="col-lg-12 hidden">
                                            <div class="box-tebal">
                                                <div class="title">
                                                    <h4><i class="fa fa-list" aria-hidden="true"></i>قائمه السيارات     </h4>
                                                </div>
                                                <div role="tabpanel" class="tab-pane " style="overflow-x: auto;">
                                                    <table id="datatable-buttons" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>

                                                                <th>#</th>
                                                                <th>   	اسم السيارة  </th>
                                                                <th>   رقم الهيكل	   </th>
                                                                <th>   اللون 	  </th>
                                                                @if (auth('admin')->user()->role == 'admin')
                                                                <th>  تم الاضافه بواسطه</th>
                                                                @endif
                                                                <th>  سنة الصنع </th>
                                                                <th> نوع السيارة  </th>
                                                                <th> المدينة التى تعمل بها السيارة</th>
                                                                <th> تاريخ انتهاء الاستماره </th>
                                                                <th> تاريخ انتهاء الاستماره هجرى</th>
                                                                <th>   صورة السيارة </th>
                                                                <th>العمليات</th>


                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($cars as $item)

                                                            <tr>
                                                                  <td>{{$item->id}} </td>
                                                                <td> {{$item->name}} </td>
                                                                <td>{{$item->structure_no}} </td>
                                                                <td>{{$item->color}}</td>
                                                                @if (auth('admin')->user()->role == 'admin')
                                                                <td>{{$item->BranchData->Admin->name ?? ''  }}</td>
                                                                @endif
                                                                <td>{{$item->manufacturing_year}}</td>
                                                                <td>{{$item->type}}</td>
                                                                <td> {{$item->work_city}} </td>
                                                                <td> {{$item->license_expiration_date}} </td>
                                                                <td> {{$item->license_expiration_date_hijri}} </td>

                                                                <td>
                                                                    <img src="{{getImage($item->form_image) }}" width="150" height="150" />
                                                                </td>

                                                                <td class="btns">

                                                                    <a href="/dashboard/carmaintaince/create?carid={{$item->id}}" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-plus"></i> اضافة صيانه </a>
                                                                    <a href="/dashboard/cars/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> تعديل </a>
                                                                    <a href="{{route('cars.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>

                                                                </td>

                                                            </tr>
                                                            @endforeach




                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        @endif
                                    </div>
                                </div>

                    </div><!-- end col -->
                </div>
                <!-- end row -->
                <div class="col-sm-12">
                    {!! $cars->appends($search)->links() !!}
                    </div>

                <div id="modal-delete" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width:55%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="custom-width-modalLabel">هل تريد الحذف </h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">إلغاء الامر</button>
                                <button type="button" class="btn btn-primary buunton-notofication waves-effect waves-light" data-type="success"  data-message="تم الحذف">حذف</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>

                <div id="info-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width:55%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="custom-width-modalLabel">بيانات العميل </h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12" id="client-info-box">
                                        {{--  --}}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
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

            $(document).on('click', '.client-info', function(){
                $.get( "{{url('/dashboard/user-info')}}" + "/" + $(this).attr('data-id'), function( data ) {
                    $('#client-info-box').html(data);
                });
            });
        </script>
@endsection
