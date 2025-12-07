@extends('admin.layout.app')
@section('style')
@endsection
@section('header')
<!-- Page title -->

                                    {{-- <div class="add-btn">
                                        <a href="{{ url('/dashboard/invoices') }}" type="button" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> اضافة </a>
                                    </div> --}}
@endsection
@section('content')


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
                                            {!! Form::select("company_id",StoreOrCompany(),(array_key_exists('company_id', $search))?
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
                                    <a href="{{url('/dashboard/transfers')}}"
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
                                                                <th> المتجر  </th>
                                                                <th> الاجمالي</th>
                                                                <th>المستحق للشركة</th>
                                                                <th>تكلفة التوصيل </th>
                                                                <th>التاريخ من</th>
                                                                <th>التاريخ الي</th>
                                                                <th>عرض الفواتير</th>
                                                                <th>التحصيل</th>
                                                                <th>التعليمات</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($transfers as $item)

                                                            <tr>
                                                                <td>{{$item->id}} </td>
                                                                <td>{{$item->Company->name ?? ''}} </td>
                                                                <td>{{$item->total_price}} </td>
                                                                <td>{{$item->company_price}} </td>
                                                                <td>{{$item->madar_price}} </td>
                                                                <td>{{$item->date_from}} </td>
                                                                <td>{{$item->date_to}} </td>
                                                                <td >
                                                                    <!-- Button trigger modal -->
                                                                    <button type="button" data-url="{{url('/dashboard/tranfer-invoices/'.$item->id)}}" class="btn btn-primary btn-lg invoice transfer-info" data-toggle="modal" data-target="#myModal">
                                                                      عرض الفواتير
                                                                    </button>
                                                                    <a href="{{route('transfers.report', $item->id)}}" class="btn btn-primary btn-xs">
                                                                        استخراج تقرير
                                                                    </a>

                                                                </td>
                                                                <td>
                                                                    @if ($item->active)
                                                                        تم التحصيل
                                                                    @else
                                                                    <button type="button" data-url="{{url('/dashboard/transfers/'.$item->id . '/edit')}}" class="btn btn-primary btn-lg invoice transfer-info2" data-toggle="modal" data-target="#myModal2">
                                                                        تحصيل
                                                                      </button>
                                                                    @endif
                                                                </td>
                                                                <td class="btns">
                                                                    @if (!$item->active)
                                                                    <a href="{{route('transfers.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs">
                                                                        <i class="fa fa-times"></i> حذف
                                                                    </a>
                                                                    <a href="{{route('transfers.recalculate',$item)}}" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs">
                                                                        اعاده حساب
                                                                    </a>
                                                                    @endif
                                                                </td>
                                                            </tr>


                                                            @endforeach




                                                        </tbody>
                                                    </table>

                                                    {{ $transfers->appends($search)->links() }}
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

                    <!-- Modal -->
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">عرض فواتير الحواله </h4>
                          </div>
                          <div class="modal-body" id="ajax-content">
                              {{--  here table by ajax  --}}
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">اغلاق</button>
                            {{--  <button type="button" class="btn btn-primary"> </button>  --}}
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">  التحصيل </h4>
                          </div>
                          <div class="modal-body" id="ajax-content2">
                              {{--  here table by ajax  --}}
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">اغلاق</button>
                            {{--  <button type="button" class="btn btn-primary"> </button>  --}}
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

            $(document).on('click', '.transfer-info', function(){
                var url = $(this).attr('data-url');
                $.get(url )
                .done(function(res){
                    console.log(res);
                    $('#ajax-content').html(res);

                })
                .fail(function(error){});
            });
            $(document).on('click', '.transfer-info2', function(){
                var url = $(this).attr('data-url');
                $.get(url )
                .done(function(res){
                    console.log(res);
                    $('#ajax-content2').html(res);

                })
                .fail(function(error){});
            });
        </script>
@endsection
