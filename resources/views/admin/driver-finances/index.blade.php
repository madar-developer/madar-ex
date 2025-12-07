@extends('admin.layout.app')
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
                                            {!! Form::select("driver_id",DriversList(),(array_key_exists('driver_id', $search))?
                                            $search['driver_id'] : null,['class'=>"form-control select2 "])!!}

                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="row btns-row">
                                    <button type="button" onclick="$(this).closest('form').find('#excel').remove(); $(this).closest('form').submit();" class="btn  btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-search"></i> بحث</button>

                                    <a href="{{url('/dashboard/driver-finances')}}"
                                        class="btn  btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
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

                                                <div role="tabpanel" class="tab-pane " style="overflow-x: auto;">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>

                                                                <th>#</th>
                                                                <th> الفرع	</th>
                                                                <th> السائق</th>
                                                                <th>الحساب الكلي</th>
                                                                <th> حساب السائق</th>
                                                                <th>  صافي الربح</th>
                                                                <th>   الحاله</th>
                                                                {{-- <th>العمليات</th> --}}


                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @php
                                                                $i=1;
                                                            @endphp
                                                            @foreach($driver_finances as $item)

                                                            <tr>
                                                            <td>{{$i++}}</td>
                                                                  <td>{{$item->Admin->name ?? ''}} </td>
                                                                  <td>{{$item->Driver->first_name ?? ''}} {{$item->Driver->last_name ?? ''}} </td>
                                                                  <td>{{$item->total_amount}} </td>
                                                                  <td>{{$item->driver_amount}} </td>
                                                                  <td>{{$item->net_profit}} </td>
                                                                  <td>
                                                                    {!!Form::model($item , ['url' => ['/dashboard/driver-finances/'.$item->id] , 'method' => 'PATCH', 'class'=>'form']) !!}
                                                                        {!! Form::hidden('update_row', '1', []) !!}
                                                                        {!! Form::select("status",\App\Models\DriverFianance::getLevels($item->status),null,['class'=>"form-control
                                                                        select2", "autocomplete"=> 'off', "onchange" =>
                                                                        "$(this).closest('form').submit()"]) !!}
                                                                    {!!Form::close() !!}
                                                                  </td>
                                                                {{-- <td class="btns">

                                                                        <a href="/dashboard/driver-finances/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> تعديل </a>

                                                                    </td> --}}


                                                            </tr>
                                                            @endforeach




                                                        </tbody>
                                                    </table>
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
