@extends('admin.reports.pdf.master')
@section('content')
<br>
<br>
<br>
<div class="col-md-6">
    <div class="col-md-12 text-center" style="">
        <h3> تفاصيل المتجر </h3>
    </div>
    <table class="table table-striped" style="  border: 1px solid gray;">
        <thead>

        <tbody>
            <tr style="  border: 1px solid gray;">
                <th scope="row" style="  border: 1px solid gray; color:#000;">اسم المتجر</th>
                <td style="  border: 1px solid gray;">{{$company->name}}</td>

            </tr>

            <tr>
                <th scope="row" style="  border: 1px solid gray;  color:#000;"> رقم تليفون المتجر</th>
                <td style="  border: 1px solid gray;">{{$company->phone}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">البريد الالكترونى</th>
                <td style="  border: 1px solid gray;">{{$company->email}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> المدينه</th>
                <td style="  border: 1px solid gray;">{{$company->City->name ??''}}

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> العنوان بالتفصيل</th>
                <td style="  border: 1px solid gray;">{{$company->adress_details}}</td>

            </tr>
        </tbody>
    </table>
</div>
<div class="col-md-6">
    <div class="col-md-12 text-center" style="">
        <h3> احصائيات </h3>
    </div>
    <table class="table table-striped" style="  border: 1px solid gray;">
        <thead>

        <tbody>
            <tr style="  border: 1px solid gray;">
                <th scope="row" style="  border: 1px solid gray; color:#000;"> قيمة بضاعة لم تسلم </th>
                <td style="  border: 1px solid gray;">{{$company->Order()->where('status', '<>', 'completed')->sum('price')}}</td>

            </tr>

            <tr>
                <th scope="row" style="  border: 1px solid gray;  color:#000;"> مبالغ في انتظار التحويل  </th>
                <td style="  border: 1px solid gray;">{{$company->Transfer()->where('active', 0)->sum('company_price')}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray;  color:#000;"> مبالغ تم تحويلها  </th>
                <td style="  border: 1px solid gray;">{{$company->Transfer()->where('active', 1)->sum('company_price')}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">قيمة توصيل مستحقة </th>
                <td style="  border: 1px solid gray;">{{$company->Transfer()->where('active', 0)->sum('madar_price')}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">قيمة توصيل تم تحصيلها سابقا </th>
                <td style="  border: 1px solid gray;">{{$company->Transfer()->where('active', 1)->sum('madar_price')}}</td>

            </tr>
        </tbody>
    </table>
</div>

{{-- <div class="col-md-6">
    <div class="col-md-12 text-center" style="">
        <h3> تفاصيل الطلبات </h3>
    </div>
    <table class="table table-striped" style="  border: 1px solid gray;">
        <thead>

        <tbody>

            @foreach (OrderStatus() as $key => $value)
            <tr>
                <th scope="row" style="  border: 1px solid gray;  color:#000;">الطلبات {{$value}}</th>
                <td style="  border: 1px solid gray;">{{$company->Order()->where('status','=',$key)->count()}}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<div class="col-md-6">
    <div class="col-md-12 text-center" style="">
        <h3> طلبات المتجر </h3>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            @php
            $i = 1;
            @endphp
            <tr>

                <th>
                    #
                </th>
                <th> اسم المتجر </th>
                <th> رقم تليفون المتجر </th>
                <th> اسم المستلم </th>
                <th> رقم الجوال</th>
                <th> المدينه </th>
                <th> العنوان بالتفصيل </th>
                <th> عدد المنتجات </th>
                <th> السعر</th>
                <th> طريقه الدفع </th>
                <th> اسم السائق </th>
                <th> السياره </th>
                <th> ملحوظات </th>
                <th> الحالة </th>
                <th> رقم المرجع </th>
                <th> رقم التسلسل </th>
                <th> تاريخ الانشاء</th>


            </tr>
        </thead>

        <tbody>
            @foreach($company->Order()->get() as $item)

            <tr>
                <td>
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
                <td>{{$item->Driver->first_name ?? ''  }}</td>
                <td>{{$item->Car->name ?? ''  }}</td>
                <td>{{$item->notes}}</td>
                <td>
                    {{trans('words.'.$item->status)}}
                </td>
                <td>{{$item->refrence_no}}</td>
                <td>{{$item->serial}}</td>
                <td>{{$item->created_at}}</td>



            </tr>
            @endforeach




        </tbody>
    </table>
</div> --}}
<div class="col-md-12">
    <div class="col-md-12 text-center" style="">
        <h3> التحصيل </h3>
    </div>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>

                <th>#</th>
                <th> المتجر </th>
                <th> الاجمالي </th>
                <th> المستحق للشركة </th>
                <th> تكلفة التوصيل </th>
                <th>التاريخ من</th>
                <th>التاريخ الي</th>

                <th>التحصيل</th>
            </tr>
        </thead>

        <tbody>
            @foreach($company->Transfer()->latest()->get() as $item)

            <tr>
                <td>{{$item->id}} </td>
                <td>{{$item->Company->name ?? ''}} </td>
                <td> {{$item->total_price}} </td>
                <td> {{$item->company_price}} </td>
                <td> {{$item->madar_price}} </td>
                <td>{{$item->date_from}} </td>
                <td>{{$item->date_to}} </td>
                <td>
                    @if ($item->active)
                    تم التحصيل
                    @else
                    لم يتم التحصيل
                    @endif
                </td>

                @endforeach




        </tbody>
    </table>
</div>
<div class="col-md-12">
    <div class="col-md-12 text-center" style="">
        <h3> الفواتير </h3>
    </div>
    <table id="datatable-buttons" class="table table-striped table-bordered">
        <thead>
            @php
            $i = 1;
            @endphp
            <tr>
                <th>
                    #
                </th>
                <th> التاريخ </th>
                <th> المبلغ الاجمالي </th>
                <th> المستحق للشركة </th>
                <th> قيمه التوصيل </th>
                <th> الطلب</th>
                <th> العميل</th>
                <th> الحاله </th>
                <th> رقم الحواله</th>


            </tr>
        </thead>

        <tbody>
            @foreach(\App\Models\Invoice::whereIn('transfer_id', $company->Transfer()->pluck('id')->toArray() )->latest()->get() as $item)

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
                <td>{{($item->active == '0')? 'لم يتم' : 'تم الاضافة الي فاتورة '}}</td>
                <td> {{$item->Transfer->id ?? ''}}
                </td>

            </tr>
            @endforeach




        </tbody>
    </table>
</div>
@endsection
