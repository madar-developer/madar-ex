@extends('admin.layout.app')
@section('style')
@section('header')
<!-- Page title -->

@endsection
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


                            <div class="col-lg-12">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-3">اسم المتجر</label>
                                        <div class="col-md-9">
                                            {!! Form::select("company_id",StoreOrCompany(),(array_key_exists('company_id',
                                            $search))? $search['company_id'] : null,['class'=>"form-control select2 "])!!}

                                        </div>
                                    </div>

                                </div>
                            </div>



                            <div class="col-lg-12">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-3"> تاريخ البداية</label>
                                        <div class="col-md-9">
                                            <input type="text" name="start_date"
                                                value="{{(array_key_exists('start_date', $search))? $search['start_date'] : ''}}"
                                                class="form-control datepicker" placeholder="dd/mm/yyy">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-md-3"> تاريخ النهاية</label>
                                        <div class="col-md-9">
                                            <input type="text" name="end_date"
                                                value="{{(array_key_exists('end_date', $search))? $search['end_date'] : ''}}"
                                                class="form-control datepicker" placeholder="dd/mm/yyy">
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
@if ($orders)
                        <div role="tabpanel" class="tab-pane " style="overflow-x: scroll;">
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                                <thead>
                                    @php
                                    $i = 1;
                                    @endphp
                                    <tr>
                                        <th>#</th>
                                        <th> اسم المتجر </th>
                                        <th> رقم تليفون المتجر </th>
                                        <th> اسم المستلم </th>
                                        <th> رقم الجوال</th>
                                        <th> المدينه </th>
                                        <th> العنوان بالتفصيل </th>
                                        <th> عدد المنتجات </th>
                                        <th> طريقه الدفع </th>
                                        <th> الحالة </th>
                                        <th> رقم المرجع </th>
                                        <th> رقم التسلسل </th>
                                        <th> تاريخ الانشاء</th>
                                        <th> السعر</th>


                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($orders as $item)

                                    <tr>
                                        <td>{{$i++}} </td>
                                        <td>{{$item->Company->name ?? ''}} </td>
                                        <td>{{$item->Company->phone ?? ''}} </td>
                                        <td>{{$item->recipent_name}} </td>
                                        <td>{{$item->phone}} </td>
                                        <td> {{ $item->City->name ?? '' }}</td>
                                        <td>{{$item->adress_details}} </td>
                                        <td>{{$item->packages_number}} </td>
                                        <td>{{$item->PaymentMethod->name ?? '' }}</td>
                                        <td>
                                            {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
                                            => 'PATCH','files'=>true]) !!}
                                            {!!
                                            Form::select("status",OrderStatus($item->status),null,['class'=>"form-control
                                            select2", "autocomplete"=> 'off', "onchange" =>
                                            "$(this).closest('form').submit()"])!!}
                                            {!!Form::close() !!}
                                        </td>
                                        <td>{{$item->refrence_no}}</td>
                                        <td>{{$item->serial}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->price}}</td>


                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="12"></td>
                                        <td>الاجمالي</td>
                                        <td>{{$orders->sum('price')}}</td>
                                    </tr>




                                </tbody>
                            </table>
                        </div>

@endif
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
                                    "autocomplete"=> 'off'])!!}
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


@endsection
@section('script')

<script src="{{ asset('/adminto/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
<script src="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script>
jQuery('.datepicker').datepicker();

</script>

@endsection
