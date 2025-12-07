
    <table class="table table-striped" style="  border: 1px solid gray;">
        <thead>

        <tbody>
            <tr style="  border: 1px solid gray;">
                <th scope="row" style="  border: 1px solid gray; font-weight:bold;">اسم السائق</th>
                <td style="  border: 1px solid gray;">{{$items['driver']->first_name}}</td>

            </tr>

            <tr>
                <th scope="row" style="  border: 1px solid gray;  font-weight:bold;"> اسم العائله  </th>
                <td style="  border: 1px solid gray;">{{$items['driver']->last_name}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; font-weight:bold;"> رقم الجوال</th>
                <td style="  border: 1px solid gray;">{{$items['driver']->phone}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; font-weight:bold;"> رقم الهويه</th>
                <td style="  border: 1px solid gray;">{{$items['driver']->identical_number}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; font-weight:bold;">  البريد الالكتروني</th>
                <td style="  border: 1px solid gray;">{{$items['driver']->email}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; font-weight:bold;">   الجنسيه</th>
                <td style="  border: 1px solid gray;">{{$items['driver']->nationality}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; font-weight:bold;">   </th>
                <td style="  border: 1px solid gray;"></td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; font-weight:bold;">   الحساب الكلي</th>
                <td style="  border: 1px solid gray;">{{$items['row']->OrdersNetProfit()}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; font-weight:bold;">حساب السائق</th>
                <td style="  border: 1px solid gray;">{{$items['row']->driver_amount}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; font-weight:bold;">صافي الربح</th>
                <td style="  border: 1px solid gray;">{{$items['row']->OrdersNetProfit()}}</td>

            </tr>
        </tbody>
    </table>
<table class="table table-striped table-bordered">
    <thead>
        @php
            $i = 1;
        @endphp
        <tr  style="  border: 1px solid gray;">

            <th  style="  border: 1px solid gray; font-weight:bold;">
                #
            </th>
            {{--  <th  style="  border: 1px solid gray; font-weight:bold;">IDs </th>  --}}
            <th  style="  border: 1px solid gray; font-weight:bold;">  اسم المتجر </th>
            <th  style="  border: 1px solid gray; font-weight:bold;">   رقم تليفون المتجر </th>
            <th  style="  border: 1px solid gray; font-weight:bold;">  اسم المستلم </th>
            <th  style="  border: 1px solid gray; font-weight:bold;">    رقم الجوال</th>
            <th  style="  border: 1px solid gray; font-weight:bold;">   المدينه	   </th>
            <th  style="  border: 1px solid gray; font-weight:bold;">   العنوان بالتفصيل	   </th>
            <th  style="  border: 1px solid gray; font-weight:bold;">    عدد المنتجات	   </th>
            <th  style="  border: 1px solid gray; font-weight:bold;">  السعر</th>
            <th  style="  border: 1px solid gray; font-weight:bold;">  طريقه الدفع </th>
            {{--  <th  style="  border: 1px solid gray; font-weight:bold;">   ملحوظات </th>  --}}
            <th  style="  border: 1px solid gray; font-weight:bold;">    الحالة </th>
            <th  style="  border: 1px solid gray; font-weight:bold;">    رقم المرجع </th>
            <th  style="  border: 1px solid gray; font-weight:bold;">  رقم التسلسل </th>
            <th  style="  border: 1px solid gray; font-weight:bold;">    تاريخ الانشاء</th>


        </tr>
    </thead>

    <tbody>
        @foreach($items['orders'] as $item)

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
