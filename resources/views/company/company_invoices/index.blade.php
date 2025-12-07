@extends('company.layout.app')
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
                                        <a href="{{ url('/dashboard/orders/create') }}" type="button" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> اضافة </a>
                                    </div>
                                </li>
                            </ul>
@endsection
@section('content')


                <!-- Page-Title -->


<div class="row">

                    <div class="col-sm-12">
                            <div class="card-box text-left">
                                    <div class="row">

                                        <div class="col-lg-12">
                                            <div class="box-tebal">

                                                <div role="tabpanel" class="tab-pane " style="overflow-x: auto;">
                                                    <table id="datatable-buttons" class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>

                                                                <th> </th>
                                                                <th>   	  تاريخ اليوم  </th>
                                                                <th>    قيمه التوصيل	   </th>
                                                                <th>  مبلغ الشحن </th>
                                                                <th> الرصيد    </th>
                                                                <th>  الطلب</th>
                                                                <th>  العميل</th>
                                                                <th>  الحاله </th>
                                                                <th>   رقم الحواله</th>
                                                                <th>العمليات</th>


                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($company_invoices as $item)

                                                            <tr>
                                                                  <td>{{$item->id}} </td>
                                                                  <td>{{$item->created_at->todatestring()}} </td>
                                                                <td>
                                                                    @if($item->city_id == '1')
                                                                    {{$item->Company->inside_price ?? ''}} ريال
                                                                    @else
                                                                    {{$item->Company->inside_price ?? ''}} ريال
                                                                    @endif
                                                                </td>
                                                                <td>{{$item->price}} ريال </td>
                                                                <td>{{$item->balance}}</td>
                                                                <td>#-{{$item->id}}</td>
                                                                <td>{{$item->recipent_name ?? '' }}</td>
                                                                <td>{{($item->active == '1')? 'تم التحصيل' : ' لم يتم التحصيل '}}</td>
                                                                <td> {{$item->number_of_remittance_voucher}} </td>
                                                                <td class="btns">


                                                                        <a href="/company/invoices/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> عرض  </a>
                                                                        <a href="{{route('company-invoices.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>
                                                                        <a href="{{route('company-invoices.pdf',$item->id)}}" title="Export Pdf" type="button" class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-file-pdf-o"></i> </a>
                                                                    </td>


                                                                {{--  <td>
                                                                    <a href="/dashboard/invoices/{{$item->id}}/edit">
                                                                        <i class="fa fa-pencil  m-r-10" style="color: #188ae2;">
                                                                        </i> تعديل</a>
                                                                </td>  --}}

                                                                {{--  <td>
                                                                     <a href="{{route('invoices.destroy',$item)}}" id="delete-btn"  >
                                                                         <i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a>
                                                                </td>  --}}
                                                                {{-- <td>
                                                                    <a href="{{route('users.show',$item->id)}}" class="btn waves-effect btn-default pull-right client-info" > عرض </a>
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
