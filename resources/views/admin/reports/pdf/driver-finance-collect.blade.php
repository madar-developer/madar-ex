@extends('admin.reports.pdf.master')
@section('content')
<br>
<br>
<br>
<div class="col-md-6">
    <div class="col-md-12 text-center" style="">
        <h3>  التفاصيل </h3>
      </div>
    <table class="table table-striped" style="  border: 1px solid gray;">
        <thead>

        <tbody>
            <tr style="  border: 1px solid gray;">
                <th scope="row" style="  border: 1px solid gray; color:#000;">اسم السائق</th>
                <td style="  border: 1px solid gray;">{{$driver->first_name}}</td>

            </tr>

            <tr>
                <th scope="row" style="  border: 1px solid gray;  color:#000;"> اسم العائله  </th>
                <td style="  border: 1px solid gray;">{{$driver->last_name}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> رقم الجوال</th>
                <td style="  border: 1px solid gray;">{{$driver->phone}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> رقم الهويه</th>
                <td style="  border: 1px solid gray;">{{$driver->identical_number}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">  البريد الالكتروني</th>
                <td style="  border: 1px solid gray;">{{$driver->email}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">   الجنسيه</th>
                <td style="  border: 1px solid gray;">{{$driver->nationality}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">   </th>
                <td style="  border: 1px solid gray;"></td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">   الحساب الكلي</th>
                <td style="  border: 1px solid gray;">{{$row->OrdersNetProfit()}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">حساب السائق</th>
                <td style="  border: 1px solid gray;">{{$row->driver_amount}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">صافي الربح</th>
                <td style="  border: 1px solid gray;">{{$row->OrdersNetProfit()}}</td>

            </tr>
        </tbody>
    </table>
</div>

<div class="col-md-12">
    <div class="col-md-12 text-center" style="">
        <h3> الطلبات التي تم تحصيلها  </h3>
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
                {{--  <th>IDs </th>  --}}
                <th>  اسم المتجر </th>
                <th>   رقم تليفون المتجر </th>
                <th>  اسم المستلم </th>
                <th>    رقم الجوال</th>
                <th>   المدينه	   </th>
                <th>   العنوان بالتفصيل	   </th>
                <th>    عدد المنتجات	   </th>
                <th>  السعر</th>
                <th>  طريقه الدفع </th>
                {{--  <th>   ملحوظات </th>  --}}
                <th>    الحالة </th>
                <th>    رقم المرجع </th>
                <th>  رقم التسلسل </th>
                <th>    تاريخ الانشاء</th>


            </tr>
        </thead>

        <tbody>
            @foreach($orders as $item)

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
                <td>
                    {{__('words.'.$item->status)}}
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
