@extends('admin.layout.app')
@section('style')
@endsection
@section('header')
        <div class="add-btn">
            <a href="{{ url('/dashboard/invoices/create') }}" type="button"
                class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i>
                اضافة </a>
        </div>
@endsection
@section('content')


<!-- Page-Title -->

<div class="row">

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




                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("company_id",$companies,(array_key_exists('company_id', $search))?
                                            $search['company_id'] : null,['class'=>"form-control select2 "])!!}

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="date_from"
                                                value="{{(array_key_exists('date_from', $search))? $search['date_from'] : ''}}"
                                                class="form-control start-datepicker" autocomplete="off" placeholder="  التاريخ من">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="date_to"
                                                value="{{(array_key_exists('date_to', $search))? $search['date_to'] : ''}}"
                                                class="form-control end-datepicker" autocomplete="off" placeholder="  التاريخ الي">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2">
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
                            <div class="col-lg-2">
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

                            <div class="col-lg-2">
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



                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("city_id",TheCityP('المدينه من '),(array_key_exists('city_id', $search))?
                                            $search['city_id'] : null,['class'=>"form-control select2 "])!!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("city_id",TheCityP('المدينه الى'),(array_key_exists('city_id', $search))?
                                            $search['city_id'] : null,['class'=>"form-control select2 "])!!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal m-b-15">
                                    <button type="button" onclick="$(this).closest('form').submit();"
                                        class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                                            class="fa fa-search"></i> بحث</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <button type="button" target="_blank" onclick="$(this).closest('form').prepend(`<input name='excel' id='excel' type='hidden' value='1' />`); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10">تصدير لExcel</button>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <a href="{{url('/dashboard/invoices')}}"
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
                                    @php
                                    $i = 1;
                                    @endphp
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="ids" id="checkAll">
                                        </th>
                                        <th>  التاريخ </th>
                                        <th>  المبلغ الاجمالي </th>
                                        <th>  المستحق للشركة / المتجر </th>
                                        <th> قيمه التوصيل </th>
                                        <th> تكلفة السائق  </th>
                                        <th> المدينة</th>
                                        <th> اسم العميل</th>
                                        <th> الطلب</th>
                                        <th> الحاله </th>
                                        <th> رقم الحواله</th>
                                        <th>العمليات</th>


                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($invoices as $item)

                                    <tr>
                                        <td>
                                            @if ($item->active == '0')
                                            <span class="badge bage-success" style="background-color: green">جاهر للتحصيل</span>
                                            <input type="checkbox" name="ids[]" value="{{$item->id}}" class="ids"/>
                                            @else
                                            <span class="badge bage-success" style="background-color: red">تم التحصيل</span>
                                            @endif
                                            {{$i++}} </td>
                                        <td>{{$item->created_at->todatestring()}} </td>
                                        <td>{{$item->total_price}} ريال </td>
                                        <td>{{$item->company_price}} ريال </td>
                                        <td>{{$item->madar_price}} ريال </td>
                                        <td>{{$item->driver_cost}} ريال </td>
                                        <td>{{$item->Order->City->name ?? '' }}</td>
                                        <td>{{$item->Order->recipent_name ?? '' }}</td>
                                        <td>{{$item->Order->serial }}</td>
                                        <td>{{($item->active == '0')? 'لم يتم' : 'تم التحصيل '}}</td>
                                        <td> {{$item->Transfer->id ?? ''}}
                                        </td>
                                        <td class="btns">

                                            @if ($item->active == '0')

                                            <a data-route="/dashboard/invoices/{{$item->id}}/edit"  data-toggle="modal" data-target=".bd-example-modal-lg"
                                                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs load-ajax"> <i
                                                    class="fa fa-pen"></i> تعديل </a>
                                            @endif
                                            <a href="{{route('invoices.destroy',$item)}}" id="delete-btn" type="button"
                                                class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-times"></i> حذف </a>
                                            <a href="/dashboard/invoices/{{$item->id}}" type="button"
                                                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-eye"></i> عرض </a>
                                                    {{-- <a href="{{route('invoice.pdf',$item->id)}}" title="Export Pdf" type="button" class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-file-pdf-o"></i> </a> --}}

                                                    @if ($item->Order()->where('collected', '1')->first())
                                                    <span style="width: 15px; height:15px; border:1px solid #fff; border-radius: 50%; display: inline-block; background-color: blueviolet;">

                                                    </span>

                                                        @endif
                                        </td>
                                    </tr>
                                    @endforeach




                                </tbody>
                            </table>
                            {!! $invoices->appends($search)->links() !!}
                        </div>

                        {!!Form::open( ['url' => ['/dashboard/orders/update-all'] , 'method'
                        => 'post','id'=>'update-all']) !!}
                        <label>تحصيل المحدد</label>
                        <div class="text-center">
                            <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"
                                id="merge_button"> تحصيل </button>
                        </div>
                        {!!Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    </div><!-- end col -->
</div>
<!-- end row -->
<div class="col-sm-12">

</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="modal-content">
        <i class="fa fa-snipper"></i>
        </div>
    </div>
</div>

<div id="modal-delete" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true">
    <div class="modal-dialog" style="width:55%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="custom-width-modalLabel">هل تريد الحذف </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">إلغاء الامر</button>
                <button type="button" class="btn btn-primary buunton-notofication waves-effect waves-light"
                    data-type="success" data-message="تم الحذف">حذف</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="info-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel"
    aria-hidden="true">
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


<script>
    $('body').on('click', '.load-ajax', function(){
        $.get($(this).attr('data-route'))
        .done(function(data){
            $('#modal-content').html(data);
            initialize();
        })
        .fail(function(){});
    });
</script>
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
    $("#merge_button").click(function (event) {
        event.preventDefault();
        confirm('هل تريد تحصيل المحدد؟');
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
        $.post("{{route('invoices-transfer')}}", {
                _token: "{{csrf_token()}}",
                ids: arr,
                company_id: $('select[name=company_id]').val(),
                date_from: $('input[name=date_from]').val(),
                date_to: $('input[name=date_to]').val(),
            })
            .done(function () {
                window.location.href = "{{route('invoices.index')}}";
                // alert("تم التحديث بنجاح.");
            })
            .fail(function () {
                alert("error");
            })
            .always(function () {
                //   alert( "finished" );
            });
    });
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
@endsection
