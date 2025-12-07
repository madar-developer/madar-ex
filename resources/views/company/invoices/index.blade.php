
@extends('company.layout.app')
@section('style')
@endsection
@section('header')
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
                                            <input type="text" name="phone"
                                                value="{{(array_key_exists('phone', $search))? $search['phone'] : ''}}"
                                                class="form-control" placeholder="رقم التليفون">
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
                                                class="form-control" placeholder="اسم العميل">
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
                                    <button type="button" target="_blank" onclick="$(this).closest('form').prepend(`<input name='excel' id='excel' type='hidden' value='1' />`); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10">تصدير لExcel</button>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <a href="{{url('/company/company-invoices')}}"
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
                                    @php
                                    $i = 1;
                                    @endphp
                                    <tr>
                                        <th>
                                        </th>
                                        <th>  التاريخ </th>
                                        <th>  المبلغ الاجمالي </th>
                                        <th>  المستحق للشركة </th>
                                        <th> قيمه التوصيل </th>
                                        <th> الطلب</th>
                                        <th> العميل</th>
                                        <th> الحاله </th>
                                        <th> رقم الحواله</th>
                                        <th>العمليات</th>


                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($invoices as $item)

                                    <tr>
                                        <td>

                                            {{$i++}} </td>
                                        <td>{{$item->created_at->todatestring()}} </td>
                                        <td>{{$item->total_price}} ريال </td>
                                        <td>{{$item->company_price}} ريال </td>
                                        <td>{{$item->madar_price}} ريال </td>
                                        <td>{{$item->Order->serial }}</td>
                                        {{--  <td>#-{{$item->Order->id}}</td> --}}
                                        <td>{{$item->Order->recipent_name ?? '' }}</td>
                                        <td>{{($item->active == '0')? 'لم يتم' : 'تم التحصيل '}}</td>
                                        <td> {{$item->Transfer->transfer_number ?? ''}}  </td>
                                        <td class="btns">


                                            <a href="/company/company-invoices/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> عرض  </a>
                                            <a href="{{route('company-invoices.pdf',$item->id)}}" title="Export Pdf" type="button" class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-file-pdf-o"></i> </a>
                                        </td>


                                    </tr>
                                    @endforeach




                                </tbody>
                            </table>
                            {!! $invoices->links() !!}
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



@endsection
@section('script')

@endsection
