@extends('company.layout.app')
@section('style')
@endsection
@section('header')
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
                                            <input type="text" name="date_from"
                                                value="{{(array_key_exists('date_from', $search))? $search['date_from'] : ''}}"
                                                class="form-control datepicker" autocomplete="off" placeholder="  التاريخ من">
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
                                                class="form-control datepicker" autocomplete="off" placeholder="  التاريخ الي">
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
                                    <a href="{{url('/company/company-transfers')}}"
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
                                                                <th>     المتجر 	   </th>
                                                                <th>      الاجمالي	   </th>
                                                                <th>      المستحق للشركة 	   </th>
                                                                <th> 	تكلفة التوصيل    </th>
                                                                <th>التاريخ من</th>
                                                                <th>التاريخ الي</th>
                                                                <th>عرض الفواتير</th>
                                                                <th>التحصيل</th>
                                                                <th>العمليات</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($transfers as $item)

                                                            <tr>
                                                                  <td>{{$item->id}} </td>
                                                                  <td>{{$item->Company->name ?? ''}} </td>
                                                                <td> {{$item->total_price}} </td>
                                                                <td> {{$item->company_price}} </td>
                                                                <td> {{$item->madar_price}} </td>
                                                                <td>{{$item->date_from}} </td>
                                                                <td>{{$item->date_to}} </td>
                                                                <td >
                                                                    <!-- Button trigger modal -->
                                                                    <button type="button" data-url="{{url('/company/tranfer-invoices/'.$item->id)}}" class="btn btn-primary btn-lg invoice transfer-info" data-toggle="modal" data-target="#myModal">
                                                                      عرض الفواتير
                                                                    </button>
                                                                    <a href="{{route('company-transfers.report', $item->id)}}" class="btn btn-primary btn-xs">
                                                                        استخراج تقرير
                                                                    </a>

                                                                </td>
                                                                <td>
                                                                    @if ($item->active)
                                                                        تم التحصيل
                                                                    @else
                                                                    لم يتم التحصيل
                                                                    @endif
                                                                </td>
                                                                <td class="btns">


                                                                    <a href="/company/company-transfers/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> عرض  </a>
                                                                    <a href="{{route('company-invoices.pdf',$item->id)}}" title="Export Pdf" type="button" class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-file-pdf-o"></i> </a>
                                                                </td>

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
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                    {{--    --}}
                })
                .fail(function(error){});
            });
        </script>
@endsection
