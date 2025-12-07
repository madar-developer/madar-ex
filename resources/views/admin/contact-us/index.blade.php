@extends('admin.layout.app')
@section('style')
@endsection
@section('header')
<!-- Page title -->


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
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>

                                        <th>#</th>
                                        <th> الاسم </th>
                                        <th> البريد الالكترونى </th>
                                        <th> اسم الجهه </th>
                                        <th> التليفون </th>
                                        <th> المدينه </th>
                                        <th> الحاله </th>
                                        <th> تاريخ الانشاء </th>
                                        <th>العمليات</th>


                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($contacts as $item)

                                    <tr>
                                        <td>{{$item->id}} </td>
                                        <td>{{$item->name}} </td>
                                        <td>{{$item->email}}</td>
                                        <td>{{$item->organization_name}}</td>
                                        <td>{{$item->phone}}</td>
                                        <td>{{$item->city}}</td>
                                        <td>
                                            {!!Form::model($item , ['url' => ['/dashboard/contact_us/'.$item->id] , 'method'
                                            => 'PATCH','files'=>true , 'class'=>'form']) !!}
                                            {!! Form::select("status",ContactUs(),null,['class'=>"form-control select2", "autocomplete"=> 'off', "onchange" => "$(this).closest('form').submit()"])!!}
                                            {!!Form::close() !!}
                                        </td>
                                        <td>{{$item->created_at}}</td>
                                        <td class="btns">
                                            {{--  <a href="/dashboard/contact_us/{{$item->id}}/edit" type="button"
                                                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-pencil"></i> تعديل </a>  --}}
                                            <a href="{{route('contact_us.destroy',$item)}}" id="delete-btn"
                                                type="button"
                                                class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-times"></i> حذف </a>
                                        </td>


                                    </tr>
                                    @endforeach




                                </tbody>
                            </table>
                            {!! $contacts->links() !!}
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

</script>
@endsection
