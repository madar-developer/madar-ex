@extends('admin.layout.app')
@section('style')
@endsection
@section('header')
@endsection
@section('content')



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
                                                                <th> المبلغ المراد تحصيلة</th>
                                                                <th>العمليات</th>


                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($branches as $item)

                                                            <tr>
                                                                <td>{{$item->id ?? ''}}</td>
                                                                  <td>{{$item->name ?? ''}} </td>
                                                                  <td>{{$item->DriverFianance()->where('status', 'collected_by_branch')->where('verified', 0)->sum('net_profit')}} {{getCurrency()}} </td>
                                                                <td class="btns text-center">
                                                                   @if ($item->DriverFianance()->where('status', 'collected_by_branch')->where('verified', 0)->sum('net_profit'))
                                                                   {!!Form::open( ['route' => 'branch-finance-r-t-m' ,'method' => 'Post','files' => true]) !!}
                                                                    {!! Form::hidden('branch_id', $item->id, []) !!}
                                                                   <button type="submit" class="btn btn-success   waves-effect waves-light">  تحصيل </button>
                                                                   {!!Form::close() !!}

                                                                   @endif

                                                                    </td>
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
