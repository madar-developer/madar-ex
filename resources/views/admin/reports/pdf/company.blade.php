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
        <h3> طلبات المتجر  </h3>
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
                 <th>   ملحوظات </th>
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
</div>
@endsection
