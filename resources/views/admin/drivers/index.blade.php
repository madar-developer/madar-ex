@extends('admin.layout.app')
@section('style')
@endsection
@section('header')
<!-- Page title -->

    <div class="add-btn">
        <a href="{{ url('/dashboard/drivers/create') }}" type="button" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> اضافة </a>
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



                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="driver"
                                                value="{{(array_key_exists('driver', $search))? $search['driver'] : ''}}"
                                                class="form-control" placeholder="كلمات البحث ">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="created_at"
                                                value="{{(array_key_exists('created_at', $search))? $search['created_at'] : ''}}"
                                                class="form-control datepicker" placeholder="تاريخ الانشاء ">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("active",(['' => 'اختر الحالة','1' => 'نشط هذا الأسبوع', '0' => 'غير نشط']),
                                            (array_key_exists('active', $search))? $search['active'] : null,['class'=>"form-control select2 "])!!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal m-b-15">
                                    <button type="button" onclick="$(this).closest('form').find('#excel').remove(); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-search"></i> بحث</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <button type="button" target="_blank" onclick="$(this).closest('form').prepend(`<input name='excel' id='excel' type='hidden' value='1' />`); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10">تصدير لExcel</button>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <a href="{{url('/dashboard/drivers')}}"
                                        class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                                            class="fa fa-trash"></i> مسح خيارات البحث</a>
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
                            <div class="card-box text-left">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div class="box-tebal">
                                                <div role="tabpanel" class="tab-pane " style="overflow-x: auto;">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>

                                                                <th>#</th>
                                                                <th>   	اسم السائق  </th>
                                                                <th>    اسم العائلة	   </th>
                                                                <th>   رقم الجوال 	  </th>
                                                                <th>   رقم الهوية </th>
                                                                <th> البريد الإلكترونى   </th>
                                                                <th>  الجنسيه</th>
                                                                <th>  رقم الرخصة</th>
                                                                <!-- <th>   تاريخ إنتهاء الرخصة</th> -->
                                                                <!-- <th>    تاريخ إنتهاء الرخصة هجري</th> -->
                                                                <th>     اسم السيارة</th>
                                                                <!-- <th>      انتهاء الهويه</th> -->
                                                                <!-- <th>      انتهاء الهويه هجري</th> -->
                                                                 <th>      --------</th>
                                                                {{-- <th>      صورة الهويه</th>
                                                                <th>      صورة الرخصه</th>
                                                                <th>      صوره الاستماره</th> --}}
                                                                <th>عمليات</th>


                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($drivers as $item)

                                                            <tr>
                                                                  <td>{{$item->id}} </td>
                                                                <td> {{$item->first_name}} </td>
                                                                <td>{{$item->last_name}} </td>
                                                                <td>{{$item->phone}}</td>
                                                                <td>{{$item->identical_number}}</td>
                                                                <td>{{$item->email}}</td>
                                                                <td> {{$item->nationality}} </td>
                                                                <td> {{$item->license_number}} </td>
                                                                <!-- <td> {{$item->license_date_expiration}} </td> -->
                                                                <!-- <td> {{$item->license_expiration_date_hijri}} </td> -->
                                                                <td> {{$item->Car->name ?? '' }}</td>
                                                                <!-- <td> {{$item->identity_expiration_date }}</td> -->
                                                                <!-- <td> {{$item->identity_expiration_date_hijri }}</td> -->
                                                                 
                                                                @php
                                                                    $weekStart = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::SATURDAY)->startOfDay();
                                                                    $weekEnd = (clone $weekStart)->addDays(6)->endOfDay();
                                                                    $isActiveThisWeek = !empty($item->last_activity)
                                                                        && \Carbon\Carbon::parse($item->last_activity)->between($weekStart, $weekEnd);
                                                                @endphp
                                                                @if($isActiveThisWeek)
                                                                    <td bgcolor="#d4edda"></td>
                                                                @else
                                                                    <td bgcolor="#f8d7da"></td>
                                                                @endif
                                                                {{-- <td>
                                                                    <img src="{{getImage($item->identity_image) }}" width="150" height="150" />
                                                                </td>
                                                                <td>
                                                                    <img src="{{getImage($item->license_image) }}" width="150" height="150" />
                                                                </td>
                                                                <td>
                                                                    <img src="{{getImage($item->form_image) }}" width="150" height="150" />
                                                                </td> --}}

                                                                <td class="btns">
                                                                    <a href="/dashboard/drivers/{{$item->id}}" type="button"
                                                                        class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                                            class="fa fa-eye"></i> عرض </a>
                                                                    <a href="/dashboard/drivers/{{$item->id}}/edit" type="button"
                                                                        class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                                            class="fa fa-pencil"></i> تعديل </a>
                                                                            <a href="{{route('drivers.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>
                                                                    <a href="javascript:void(0);" type="button"
                                                                        class="btn btn-warning waves-effect waves-light m-b-5 btn-xs cashed-orders-btn"
                                                                        data-route="{{ route('drivers.cashed-orders-form', $item->id) }}"
                                                                        data-name="{{ $item->first_name }} {{ $item->last_name }}"
                                                                        title="تصفية الطلبات من السائق">
                                                                        <i class="fa fa-money"></i> تصفية الطلبات
                                                                    </a>
                                                                    <a href="javascript:void(0);" type="button"
                                                                        class="btn btn-primary waves-effect waves-light m-b-5 btn-xs driver-finances-btn"
                                                                        data-route="{{ route('drivers.finances-form', $item->id) }}"
                                                                        data-name="{{ $item->first_name }} {{ $item->last_name }}"
                                                                        title="تحميل التصفيات">
                                                                        <i class="fa fa-list"></i> تحميل التصفيات
                                                                    </a>
                                                                    <a href="{{route('driver.pdf',$item->id)}}" type="button" class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pdf"></i> ExportPDF </a>
                                                                    <a href="{{route('driver-finance.pdf',$item->id)}}" type="button" class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pdf"></i> تقرير الطلبات التي تم توصيلها </a>


                                                                </td>
                                                                {{-- <td class="btns">
                                                                    <a href="/dashboard/drivers/{{$item->id}}/edit">
                                                                        <i class="fa fa-pencil  m-r-10" style="color: #188ae2;">
                                                                        </i> تعديل</a>
                                                                </td> --}}

                                                                {{-- <td>
                                                                     <a href="{{route('drivers.destroy',$item)}}" id="delete-btn"  >
                                                                         <i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a>
                                                                </td> --}}
                                                                {{-- <td>
                                                                    <a href="{{route('users.show',$item->id)}}" class="btn waves-effect btn-default pull-right client-info" > عرض </a>
                                                                </td> --}}
                                                            </tr>
                                                            @endforeach




                                                        </tbody>
                                                    </table>
                                                    {!! $drivers->appends($search)->links() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                    </div><!-- end col -->
                </div>
                <!-- end row -->
                <div class="col-sm-12">

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

                <div id="cashed-orders-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" style="width:90%; max-width:1200px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">تصفية الطلبات من السائق — <span id="cashed-orders-driver-name"></span></h4>
                            </div>
                            <div class="modal-body">
                                <div id="cashed-orders-box" class="text-center">
                                    <i class="fa fa-spinner fa-spin"></i> جاري التحميل...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="driver-finances-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" style="width:90%; max-width:1200px;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">تحميل التصفيات — <span id="driver-finances-driver-name"></span></h4>
                            </div>
                            <div class="modal-body">
                                <div id="driver-finances-box" class="text-center">
                                    <i class="fa fa-spinner fa-spin"></i> جاري التحميل...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="finance-orders-modal" tabindex="-1" role="dialog" aria-labelledby="financeOrdersModalLabel">
                    <div class="modal-dialog modal-lg" role="document" style="width:90%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="financeOrdersModalLabel">عرض الطلبات</h4>
                            </div>
                            <div class="modal-body" id="finance-orders-ajax-content"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">اغلاق</button>
                            </div>
                        </div>
                    </div>
                </div>


@endsection
@section('script')


        <script type="text/javascript">
            $(document).on('click', '.client-info', function(){
                $.get( "{{url('/dashboard/user-info')}}" + "/" + $(this).attr('data-id'), function( data ) {
                    $('#client-info-box').html(data);
                });
            });

            $(document).on('click', '.cashed-orders-btn', function () {
                var route = $(this).data('route');
                var name = $(this).data('name');
                $('#cashed-orders-driver-name').text(name);
                $('#cashed-orders-box').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>');
                $('#cashed-orders-modal').modal('show');
                $.get(route)
                    .done(function (data) {
                        $('#cashed-orders-box').html(data);
                    })
                    .fail(function () {
                        $('#cashed-orders-box').html('<div class="alert alert-danger">تعذر تحميل الطلبات</div>');
                    });
            });

            $(document).on('click', '#checkAllCashed', function () {
                $('#cashed-orders-box input.cashed-order-id').prop('checked', this.checked);
            });

            $(document).on('click', '.driver-finances-btn', function () {
                var route = $(this).data('route');
                var name = $(this).data('name');
                $('#driver-finances-driver-name').text(name);
                $('#driver-finances-box').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>');
                $('#driver-finances-modal').modal('show');
                $.get(route)
                    .done(function (data) {
                        $('#driver-finances-box').html(data);
                        if ($.fn.select2) {
                            $('#driver-finances-box .select2').select2();
                        }
                    })
                    .fail(function () {
                        $('#driver-finances-box').html('<div class="alert alert-danger">تعذر تحميل التصفيات</div>');
                    });
            });

            $(document).on('click', '.transfer-info', function () {
                var url = $(this).attr('data-url');
                $('#finance-orders-ajax-content').html('<div class="text-center"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>');
                $.get(url)
                    .done(function (res) {
                        $('#finance-orders-ajax-content').html(res);
                    })
                    .fail(function () {
                        $('#finance-orders-ajax-content').html('<div class="alert alert-danger">تعذر تحميل الطلبات</div>');
                    });
            });
        </script>
@endsection
