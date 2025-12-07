@extends('company.layout.app')
@section('style')
<style>
    .card ul{
        list-style: none;
    }
    .card .panel-footer ul li{
        display: inline-block;
    }
</style>
@endsection
@section('header')
        <div class="add-btn">
            <a href="{{ url('/company/request-drivers/create') }}" type="button" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> اضافة </a>
        </div>

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
                                            <input type="text" name="s"
                                                value="{{(array_key_exists('s', $search))? $search['s'] : ''}}"
                                                class="form-control" placeholder="كلمات البحث ">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {{-- <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="created_at"
                                                value="{{(array_key_exists('created_at', $search))? $search['created_at'] : ''}}"
                                                class="form-control" placeholder="تاريخ الانشاء ">
                                        </div>
                                    </div>

                                </div>
                            </div> --}}


                            <div class="col-md-2">
                                <div class="form-horizontal m-b-15">
                                    <button type="button" onclick="$(this).closest('form').find('#excel').remove(); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-search"></i> بحث</button>
                                </div>
                            </div>
                            {{-- <div class="col-md-2">
                                <div class="form-horizontal">
                                    <button type="button" target="_blank" onclick="$(this).closest('form').prepend(`<input name='excel' id='excel' type='hidden' value='1' />`); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10">تصدير لExcel</button>

                                </div>
                            </div> --}}

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <a href="{{url('/company/request-drivers')}}"
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


                                        <div class="col-lg-12 ">
                                            <div class="box-tebal">
                                                <div role="tabpanel" class="tab-pane " style="overflow-x: auto;">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>

                                                                <th>#</th>
                                                                <th>   	اسم المتجر  </th>
                                                                <th>   	جوال المتجر  </th>
                                                                <th>عدد الشحنات  </th>
                                                                <th> تاريخ طلب المندوب  </th>
                                                                <th> وقت الطلب  </th>
                                                                <th>  الحالة </th>
                                                                <th> تاريخ الانشاء  </th>
                                                                <th>العمليات</th>


                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($request_drivers as $item)

                                                            <tr>
                                                                  <td>{{$item->id}} </td>
                                                                <td> {{$item->name}} </td>
                                                                <td>{{$item->phone}} </td>
                                                                <td>{{$item->shipments}}</td>
                                                                <td>{{$item->pickup_date}}</td>
                                                                <td>{{TimeSlots($item->time_slot)}}</td>
                                                                <td> {{__('translation.'.$item->status)}} </td>
                                                                <td> {{$item->created_at}} </td>
                                                                <td class="btns">

                                                                    <a href="/company/request-drivers/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> تعديل </a>
                                                                    <a href="{{route('request-drivers.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>

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
                    {!! $request_drivers->appends($search)->links() !!}
                    </div>


@endsection
@section('script')

@endsection
