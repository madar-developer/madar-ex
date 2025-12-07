@extends('admin.layout.app')
@section('style')
@endsection
@section('content')


                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        {{-- <div class="btn-group pull-right m-t-15">
                            <a href="" class="btn btn-info waves-effect w-md waves-light m-b-5">
                                <i class="fa fa-pencil m-r-5"></i>
                                <span>إضافة  جديد</span>
                            </a>
                        </div> --}}
                        <div class="title-page sub-header-title-custom">
                            <h5 style="color: inherit;"><a href="#"><span> المدونه </span><i class="fa fa-angle-left" style="padding: 0 5px; color:inherit;"></i></a><span>نطره عامة        </span></h5>

                            <ul>


                                <li class="offer-2">
                                    <a href="{{url('/dashboard/blogs/create')}}"><span><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        اضافة  جديد   </a>
                                </li>

                            </ul>
                        </div>
                        <div class="card-box">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-md-12 part-top">
                                        <div class="row">

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">


                                            <div class="col-lg-4">
                                                <div class="form-horizontal">
                                                    <div class="form-group">
                                                        <div class="col-md-12">
                                                            <input type="text" name="name" value="" class="form-control" placeholder="المدونه ">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-horizontal m-b-15">
                                                    <button type="button" onclick="$(this).closest('form').find('#excel').remove(); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-search"></i> بحث</button>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-horizontal">
                                                    <a href="{{url('/dashboard/users')}}" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-trash"></i> مسح خيارات البحث</a>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-horizontal">
                                                @if(auth()->user()->role == 'admin')
                                                    <button type="button" target="_blank" onclick="$(this).closest('form').prepend(`<input name='excel' id='excel' type='hidden' value='1' />`); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10">تصدير لExcel</button>
                                                @endif
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
                                                <div class="title">
                                                    <h4><i class="fa fa-list" aria-hidden="true"></i>قائمه المدونات     </h4>
                                                </div>
                                                <div role="tabpanel" class="tab-pane " style="overflow-x: auto;">
                                                    <table class="datatable-buttons table table-striped table-bordered">
                                                        <thead>
                                                            <tr>

                                                                <th>#</th>
                                                                <th>   	 الصورة  </th>
                                                                <th>     	عنوان المدونه	   </th>
                                                                <th>   وقت الاضافه  	  </th>
                                                                <th>تعديل</th>
                                                                <th>حذف </th>


                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($blogs as $item)

                                                            <tr>
                                                                  <td>{{$item->id}} </td>
                                                                <td> {{$item->image}} </td>
                                                                <td>{{$item->name}} </td>
                                                                <td>{{$item->created_at}}</td>


                                                                <td>
                                                                    <a href="/dashboard/discounts/{{$item->id}}/edit">
                                                                        <i class="fa fa-pencil  m-r-10" style="color: #188ae2;">
                                                                        </i> تعديل</a>
                                                                </td>

                                                                <td>
                                                                     <a href="{{route('blogs.destroy',$item)}}" id="delete-btn"  >
                                                                         <i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a>
                                                                </td>
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
