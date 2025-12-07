@extends('company.layout.app')
@section('style')
@section('header')
    <div class="add-btn">
        <a href="{{ url('/company/company-orders/create') }}" type="button"
            class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> اضافة
        </a>
        <a href="{{ url('/madar-express-template.xlsx') }}" type="button"
            class="btn btn-primary"> <i class="fa fa-download"></i> تحميل تمبلت Excel </a>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            ادراج طلبات من ملف اكسيل
        </button>
    </div>
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
                                            <input type="text" name="company_phone"
                                                value="{{(array_key_exists('company_phone', $search))? $search['company_phone'] : ''}}"
                                                class="form-control" placeholder="  رقم تليفون المتجر">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="recipent_name"
                                                value="{{(array_key_exists('recipent_name', $search))? $search['recipent_name'] : ''}}"
                                                class="form-control" placeholder="اسم المستلم">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="phone"
                                                value="{{(array_key_exists('phone', $search))? $search['phone'] : ''}}"
                                                class="form-control" placeholder="رقم تليفون المستلم">

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("status",array_merge(['' => 'اختر
                                            الحالة'],OrderStatus()),(array_key_exists('status', $search))?
                                            $search['status'] :
                                            null,['class'=>"form-control select2 "])!!}
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal m-b-15">
                                    <button type="button"
                                        onclick="$(this).closest('form').find('#excel').remove(); $(this).closest('form').submit();"
                                        class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                                            class="fa fa-search"></i> بحث</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <button type="button" target="_blank"
                                        onclick="$(this).closest('form').prepend(`<input name='excel' id='excel' type='hidden' value='1' />`); $(this).closest('form').submit();"
                                        class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10">تصدير
                                        لExcel</button>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <a href="{{url('/company/company-orders')}}"
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

                        <div role="tabpanel" class="tab-pane " style="overflow-x: scroll;">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    @php
                                    $i = 1;
                                    @endphp
                                    <tr>

                                        <th>
                                            <input type="checkbox" class="ids" id="checkAll">
                                        </th>
                                        {{--  <th>IDs </th>  --}}
                                        <th> اسم المتجر </th>
                                        <th> رقم تليفون المتجر </th>
                                        <th> اسم المستلم </th>
                                        <th> رقم التليفون</th>

                                        <th> المدينه </th>
                                        <th> العنوان بالتفصيل </th>
                                        <th> عدد المنتجات </th>
                                        <th> السعر</th>
                                        <th> طريقه الدفع </th>
                                        {{-- <th> اسم السائق </th> --}}
                                        <th> السياره </th>
                                        {{--  <th>   ملحوظات </th>  --}}
                                        <th> الحالة </th>
                                        <th> رقم المرجع </th>
                                        <th> رقم التسلسل </th>
                                        <th> تاريخ الانشاء</th>
                                        <th> العمليات </th>


                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($orders as $item)

                                    <tr style="background-color: {{$item->OrderStatus->color ?? ''}} !important;">
                                        <td>
                                            <input type="checkbox" name="ids[]" value="{{$item->id}}" class="ids" />
                                            {{$i++}}
                                        </td>
                                        <td>{{$item->Company->name ?? ''}} </td>
                                        <td>{{$item->Company->phone ?? ''}} </td>
                                        <td>{{$item->recipent_name}} </td>
                                        <td>{{$item->phone}} </td>
                                        <td> {{ $item->City->name ?? '' }}</td>
                                        <td>{{$item->adress_details}} </td>
                                        <td>{{$item->packages_number}} </td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->PaymentMethod->name ?? '' }}</td>
                                        {{-- <td>{{$item->Driver->first_name ?? ''  }}</td> --}}
                                        <td>{{$item->Car->name ?? ''  }}</td>

                                        {{--  <td>{{$item->notes}}</td> --}}
                                        <td>
                                            {{__('words.'.$item->status)}}
                                            @if($item->status == 'deliver_failed' )
                                            <br>
                                            {{($item->OrderLog()->where('status', 'deliver_failed')->latest()->first())? @$item->OrderLog()->where('status', 'deliver_failed')->latest()->first()->ReasonD->description : ''}}
                                            @elseif($item->OrderLog()->where('status', 'reschedule')->where('active', '1')->first() && $item->delivery_date)
                                            ({{$item->delivery_date}})
                                            @endif
                                        </td>
                                        <td>{{$item->refrence_no}}</td>
                                        <td>{{$item->serial}}</td>
                                        <td>{{$item->created_at}}</td>

                                        <td class="btns">
                                            @if ($item->status == 'new')
                                            <a href="/company/company-orders/{{$item->id}}/edit" type="button"
                                                title="تعديل"
                                                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-pencil"></i> </a>

                                            @endif
                                            <a href="{{route('company-orders.destroy',$item)}}" type="button"
                                                title="حذف"
                                                class="btn btn-danger delete-btn  waves-effect waves-light m-b-5 btn-xs">
                                                <i class="fa fa-times"></i> </a>
                                            <a href="/company/company-orders/{{$item->id}}" type="button" title="عرض"
                                                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-eye"></i> </a>
                                                    <a href="/company/order-bill/{{$item->id}}" target="_blank" type="button" title="طباعة"
                                                        class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                            class="fa fa-print"></i>    </a>
                                            {{--  <a href="/dashboard/order-bill/{{$item->id}}" type="button"
                                            title="طباعة"
                                            class="btn btn-info waves-effect waves-light m-b-5 btn-xs"> <i
                                                class="fa fa-print"></i> </a> --}}
                                            {{--  <a href="{{route('order.pdf',$item->id)}}" title="Export Pdf"
                                            type="button" class="btn btn-success waves-effect waves-light m-b-5 btn-xs">
                                            <i class="fa fa-file-pdf-o"></i> </a> --}}

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
    {{ $orders->appends($search)->links() }}
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

                {!!Form::open( ['url' => '/company/company-orders-excel/' ,'method' => 'Post','files' =>
                true,'class'=>'class1']) !!}


                <div class="card-box">
                    <div class="row">



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

@endsection
