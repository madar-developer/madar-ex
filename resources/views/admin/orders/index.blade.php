@extends('admin.layout.app')
@section('style')
@section('header')
<button class="btn btn-danger search-tog btn waves-effect waves-light" type="button" data-toggle="collapse"
    data-target="#searchFilter" aria-expanded="false" aria-controls="searchFilter">
    <i class="fa fa-search" aria-hidden="true"></i>
    خيارات البحث
</button>
<a href="{{ url('/dashboard/orders/create') }}" type="button"
    class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-plus"></i> اضافة
</a>
<a href="{{ url('/madar-express-template.xlsx') }}" type="button" class="btn btn-primary"> <i
        class="fa fa-download"></i> تحميل تمبلت Excel </a>
<div class="add-btn">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
        ادراج طلبات من ملف اكسيل
    </button>
</div>
@endsection
@endsection
@section('content')


<!-- Page-Title -->

<div class="row collapse " id="searchFilter">

    <div class="col-sm-12">
        <div class="card-box">
            <form action="" method="get">
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
                                            <input type="text" name="serial_from"
                                                value="{{(array_key_exists('serial_from', $search))? $search['serial_from'] : ''}}"
                                                class="form-control" placeholder="  رقم الطلب من">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="serial_to"
                                                value="{{(array_key_exists('serial_to', $search))? $search['serial_to'] : ''}}"
                                                class="form-control" placeholder="  رقم الطلب الي">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="date_from"
                                                value="{{(array_key_exists('date_from', $search))? $search['date_from'] : ''}}"
                                                class="form-control start-datepicker" autocomplete="off"
                                                placeholder="  التاريخ من">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="date_to"
                                                value="{{(array_key_exists('date_to', $search))? $search['date_to'] : ''}}"
                                                class="form-control end-datepicker" autocomplete="off"
                                                placeholder="  التاريخ الي">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="refrence_no"
                                                value="{{(array_key_exists('refrence_no', $search))? $search['refrence_no'] : ''}}"
                                                class="form-control" placeholder="رقم الطلب على متجر التاجر">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("company_id",StoreOrCompany(),(array_key_exists('company_id',
                                            $search))?
                                            $search['company_id'] : null,['class'=>"form-control "])!!}

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("driver_id",Driver(),(array_key_exists('driver_id', $search))?
                                            $search['driver_id'] : null,['class'=>"form-control "])!!}

                                        </div>
                                    </div>

                                </div>
                            </div>



                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="company_phone"
                                                value="{{(array_key_exists('company_phone', $search))? $search['company_phone'] : ''}}"
                                                class="form-control" placeholder="  رقم تليفون المتجر">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="recipent_name"
                                                value="{{(array_key_exists('recipent_name', $search))? $search['recipent_name'] : ''}}"
                                                class="form-control" placeholder="اسم المستلم">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="phone"
                                                value="{{(array_key_exists('phone', $search))? $search['phone'] : ''}}"
                                                class="form-control" placeholder="رقم تليفون المستلم">

                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("city_id",TheCityP('المدينه من '),(array_key_exists('city_id',
                                            $search))?
                                            $search['city_id'] : null,['class'=>"form-control "])!!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("city_id",TheCityP('المدينه الى'),(array_key_exists('city_id',
                                            $search))?
                                            $search['city_id'] : null,['class'=>"form-control "])!!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("status",array_merge(['' => 'اختر
                                            الحالة'],OrderStatus()),(array_key_exists('status', $search))? $search['status'] :
                                            null,['class'=>"form-control "])!!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("deliver_failed",deliverFailedOptions(),(array_key_exists('deliver_failed', $search))? $search['deliver_failed'] :
                                            null,['class'=>"form-control "])!!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("payment_method_id",PaymentMethod(),(array_key_exists('deliver_failed', $search))? $search['deliver_failed'] :
                                            null,['class'=>"form-control "])!!}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row btns-row">

                                        <button type="button"
                                            class="search-bbt btn btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                                                class="fa fa-search"></i> بحث</button>

                                                <button type="button" target="_blank"
                                                class="excel-bbt btn btn-sm btn-success waves-effect waves-light b-t-10 b-b-10">تصدير
                                                لExcel</button>


                                                <a href="{{url('/dashboard/orders')}}"
                                                class="btn btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                                                    class="fa fa-trash"></i> مسح خيارات البحث</a>

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

                        <div role="tabpanel" class="tab-pane " style="overflow-x: scroll;">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    @php
                                    $i = 1;
                                    @endphp
                                    <tr>

                                        <th>
                                            <input type="checkbox" class="ids" id="checkAll">
                                        </th>
                                        {{--  <th>IDs </th>  --}}
                                        <th> اسم المتجر </th>
                                        <th> رقم تليفون المتجر </th>
                                        <th> اسم المستلم </th>
                                        <th> رقم التليفون</th>
                                        @if (auth('admin')->user()->role == 'admin')
                                        <th> تم الاضافه بواسطه</th>
                                        @endif

                                        <th> المدينه </th>
                                        <th> العنوان بالتفصيل </th>
                                        <th> عدد المنتجات </th>
                                        <th> السعر</th>
                                        <th> طريقه الدفع </th>
                                        <th> اسم السائق </th>
                                        <th> السياره </th>
                                        {{--  <th>   ملحوظات </th>  --}}
                                        <th> الحالة </th>
                                        <th> رقم المرجع </th>
                                        <th> رقم التسلسل </th>
                                        <!--<th> تاريخ الانشاء</th>-->
                                        <th> تاريخ التسليم </th>
                                        <th> العمليات </th>


                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($orders as $item)

                                    <tr style="background-color: {{$item->OrderStatus->color ?? ''}} !important;"
                                        id="id-{{$i}}">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{$item->id}}" class="ids" />
                                            {{$i++}}
                                        </td>
                                        {{--  {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
                                                                => 'PATCH','files'=>true]) !!}
                                                                {!! Form::select("status",OrderStatus($item->status),null,['class'=>"form-control select2", "autocomplete"=> 'off', "onchange" => "$(this).closest('form').submit()"])!!}
                                                                {!!Form::close() !!}  --}}
                                        <td>{{$item->Company->name ?? ''}} </td>
                                        <td>{{$item->Company->phone ?? ''}} </td>
                                        <td>{{$item->recipent_name}} </td>
                                        <td>{{$item->phone}} </td>
                                        @if (auth('admin')->user()->role == 'admin')
                                        <td>{{$item->BranchData->Admin->name ?? ''  }}</td>
                                        @endif
                                        <td> {{ $item->City->name ?? '' }}</td>
                                        <td>{{$item->adress_details}} </td>
                                        <td>{{$item->packages_number}} </td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->PaymentMethod->name ?? '' }}</td>
                                        <td>{{$item->Driver->first_name ?? ''  }}</td>
                                        <td>{{$item->Car->name ?? ''  }}</td>

                                        {{--  <td>{{$item->notes}}</td> --}}
                                        <td>
                                            {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
                                            => 'PATCH','files'=>true , 'class'=>'form']) !!}
                                            {!! Form::hidden('update_row', '1', []) !!}
                                            {!!
                                            Form::select("status",OrderStatus($item->status),null,['class'=>"form-control
                                            select2", "autocomplete"=> 'off', "onchange" =>
                                            "$(this).closest('form').submit()"])!!}
                                            {!!Form::close() !!}
                                            @if ($item->status == 'reschedule' && $item->OrderLog()->count() > 0 &&
                                            $item->OrderLog()->latest()->first()->status == 'reschedule' &&
                                            !$item->delivery_date)
                                            <button class="btn btn-primary rechedule-btn" data-id="{{$item->id}}"
                                                data-route="{{url('/dashboard/orders/'.$item->id)}}">
                                                Set Delivery Date
                                            </button>

                                            @elseif($item->status == 'deliver_failed' &&
                                            $item->OrderLog()->where('status', 'deliver_failed')->latest()->first() &&
                                            $item->OrderLog()->where('status',
                                            'deliver_failed')->latest()->first()->reason == null)
                                            <br>
                                            {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
                                            => 'PATCH','files'=>true , 'class'=>'form']) !!}
                                            {!! Form::hidden('update_row', '1', []) !!}
                                            {!!
                                            Form::select("deliver_failed_id",deliverFailedOptions(),null,['class'=>"form-control
                                            select2", "autocomplete"=> 'off', "onchange" =>
                                            "$(this).closest('form').submit()"])!!}
                                            {!!Form::close() !!}
                                            @elseif($item->status == 'deliver_failed' )
                                            <br>
                                            {{($item->OrderLog()->where('status', 'deliver_failed')->latest()->first())? @$item->OrderLog()->where('status', 'deliver_failed')->latest()->first()->ReasonD->description : ''}}
                                            @elseif($item->OrderLog()->where('status', 'reschedule')->where('active', '1')->first() && $item->delivery_date)
                                            ({{$item->delivery_date}})
                                            @endif
                                        </td>
                                        <td>{{$item->refrence_no}}</td>
                                        <td>{{$item->serial}}</td>
                                        <!--<td>{{$item->created_at}}</td>-->
                                        <td>{{$item->receive_date}}</td>


                                        <td class="btns">
                                            @if ($item->status == 'deliver_failed' && $item->Invoice()->count() == 0 )
                                            <button type="button" title="اصدار بوليصة" class="btn btn-primary   waves-effect waves-light m-b-5 btn-xs create-invoice" data-toggle="modal" data-target="#createInvoice" data-route="{{route('orders-invoice', $item->id)}}">
                                                <i class="fa fa-download"></i>
                                                </button>
                                                @endif
                                            
                                                @if($item->status != "delivered" || auth('admin')->user()->email == 'hussein@madarex.sa')
                                                <a href="/dashboard/orders/{{$item->id}}/edit" type="button"  title="تعديل"
                                                    class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                        class="fa fa-pencil"></i>  </a>
                                                @endif
                                                @if( auth('admin')->user()->email == 'hussein@madarex.sa')
                                                <a href="{{route('orders.destroy',$item)}}" type="button" title="حذف"
                                                    class="btn btn-danger delete-btn  waves-effect waves-light m-b-5 btn-xs">
                                                    <i class="fa fa-times"></i>  </a>
                                                @endif
                                            <a href="/dashboard/orders/{{$item->id}}" type="button" title="عرض"
                                                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-eye"></i> </a>
                                            <a href="/dashboard/order-bill/{{$item->id}}" target="_blank" type="button"
                                                title="طباعة"
                                                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-print"></i> </a>
                                            <a href="{{route('order.pdf',$item->id)}}" title="Export Pdf" type="button"
                                                class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-file-pdf-o"></i> </a>

                                        </td>
                                    </tr>
                                    @endforeach




                                </tbody>
                            </table>
                            {!!Form::open( ['url' => ['/dashboard/orders/update-all'] , 'method'
                            => 'post','id'=>'update-all']) !!}
                            <label>تعديل المحدد</label>
                            {!! Form::select("status",OrderStatus(),null,['class'=>"form-control select2",
                            "autocomplete"=> 'off'])!!}
                            <div class="text-center">
                                <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"
                                    id="merge_button"> تعديل </button>
                            </div>
                            {!!Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- end col -->
</div>
<!-- end row -->
<div class="col-sm-12">
    {{ $orders->appends($search)->links() }}
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ادراج طلبات من ملف اكسيل</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {!!Form::open( ['url' => '/dashboard/orders-excel/' ,'method' => 'Post','files' =>
                true,'class'=>'class1']) !!}


                <div class="card-box">
                    <div class="row">


                        <p class="custom-label-centerd text-left"> معلومات الشاحن </p>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class=""> اختار المتجر *</label>
                                <div class=" append">
                                    {!! Form::select("company_id",StoreOrCompany(),null,['class'=>"form-control select2
                                    ",
                                    "required"=> ''])!!}
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class=""> اختر الملف *</label>
                                <div class=" append">
                                    {!! Form::file("excel",['class'=>"form-control select2 ",
                                    "autocomplete"=> 'off'])!!}
                                </div>
                            </div>
                        </div>


                        <div class="text-center">
                            <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> اضافة
                            </button>
                        </div>
                    </div>


                </div>
                {!!Form::close() !!}
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModal2Label"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2Label">ادخال تاريخ اعادة الجدولة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {!!Form::open( ['url' => '/dashboard/orders/update' ,'method' => 'PATCH','files' =>
                true,'id'=>'resch-form']) !!}
                {{-- {!! Form::hidden('update_row', '1', []) !!} --}}


                <div class="card-box">
                    <div class="row">


                        <p class="custom-label-centerd text-left" id="order-id"> #</p>

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class=""> التاريخ*</label>
                                <div class=" append">
                                    <input type="hidden" name="order_id" id="resch-order-id">
                                    {!! Form::text("delivery_date",null,['class'=>"form-control datepicker",
                                    "required"=> ''])!!}
                                </div>
                            </div>
                        </div>



                        <div class="text-center">
                            <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> حفظ
                            </button>
                        </div>
                    </div>


                </div>
                {!!Form::close() !!}
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="createInvoice" tabindex="-1" role="dialog" aria-labelledby="createInvoiceLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createInvoiceLabel">اصدار فاتورة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="create-invoice">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
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
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        var link = $(this);
        swal({
            title: "هل أنت متأكد؟",
            text: "أنك تريد حذف هذه المنطقه ؟",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function (isConfirm) {
            if (isConfirm) {
                var cr = $('meta[name="csrf-token"]').attr('content');
                var o = link;
                // alert(cr);
                $(o).append('<i class="fa fa-spin fa-spinner"></i>');
                $.post($(o).attr('href'), {
                    _token: cr,
                    _method: 'DELETE'
                }, function (data) {
                    $(o).find('i').remove();
                    $(o).append('<i class="fa fa-check"></i>');
                    setTimeout(function () {
                        $(o).parent().parent().remove();
                        if (typeof cb == 'function') {
                            cb();
                        }
                    }, 1000);
                });
            } else {
                swal("تم الالغاء", "الحذف  الغي بنجاح");
            }
        });
    });
    //////////////////////////////////////// edit number of orders
    $("#merge_button").click(function (event) {
        event.preventDefault();
        confirm('هل تريد تحديث المحدد؟');
        var status = $('#update-all').find('select[name=status]').val();
        var arr = [];
        var searchIDs = $(".ids:checkbox:checked").each(function (i) {
            arr.push($(this).val());
            return $(this).val();
        });
        console.log(arr);
        //post _token, arr
        // var jqxhr = $.post( "example.php", function() {
        // alert( "success" );
        // })
        $.post("{{route('orders-ajax')}}", {
                _token: "{{csrf_token()}}",
                ids: arr,
                status: status
            })
            .done(function () {
                window.location.href = "{{route('orders.index')}}";
                alert("تم التحديث بنجاح.");
            })
            .fail(function () {
                alert("error");
            })
            .always(function () {
                //   alert( "finished" );
            });
    });
    ///////////////////////////////////////////////////////////////

    $('body').on('submit', ".form", function (e) {
        // stop submit .. stop reloading page
        e.preventDefault();
        var data = $(this).serializeArray();
        var o = $(this);
        var id = o.closest('tr').attr('id');
        console.log(data);

        $('html').waitMe({
            effect: 'ios',
            text: (this.lang == 'ar') ? 'إنتظر من فضلك ...' : 'Please wait...',
            color: '#000',
            waitTime: -1,
            textPos: 'vertical'
        });
        $.post($(this).attr('action'), data)
            .done(function (data) {
                console.log(data);
                console.log(id);
                $('#' + id).html(data.html);
                $('#' + id).attr('style', 'background-color:' + data.color + ' !important;');
                $('#' + id).css('background-color', data.color + ' !important');
                $('.waitMe').remove();
            })
            .fail(function () {
                alert("error");
                $('.waitMe').remove();
            })
            .always(function () {});


    });
    //////////////////////////////////////////////////

    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $(document).on('click', '.search-bbt', function () {
        console.log("::::::::::::::::::::::::");
        $(this).closest('form').find('#excel').remove();
        $(this).closest('form').submit();
    });
    $(document).on('click', '.excel-bbt', function () {
        $(this).closest('form').prepend(`<input name='excel' id='excel' type='hidden' value='1' />`);
        $(this).closest('form').submit();
    });
    $('body').on('click', '.rechedule-btn', function () {
        var id = $(this).attr('data-id');
        $('#exampleModal2').modal('toggle');
        $('#order-id').html('#' + id);
        $('#resch-order-id').val(id);
        $('#resch-form').attr('action', $(this).attr('data-route'));
    });

    $('body').on('click', ".create-invoice", function (e) {

        $('html').waitMe({
                    effect: 'ios',
                    text: (this.lang == 'ar') ? 'إنتظر من فضلك ...' : 'Please wait...',
                    color: '#000',
                    waitTime: -1,
                    textPos: 'vertical'
                });
        $.get($(this).attr('data-route'))
            .done(function (data) {
                $('#create-invoice').html(data);
                $('.waitMe').remove();
            })
            .fail(function () {
                alert("error");
                    $('.waitMe').remove();
            })
            .always(function () {});


    });
</script>


@endsection
