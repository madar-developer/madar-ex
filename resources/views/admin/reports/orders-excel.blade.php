<table>
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
        @foreach($items as $item)

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
                @if ($item->status == '9')
                    , {{$item->reason}}
                @endif
                @if($item->status == 'deliver_failed' )
                    , 
                    
                                            {{($item->OrderLog()->where('status', 'deliver_failed')->latest()->first())? @$item->OrderLog()->where('status', 'deliver_failed')->latest()->first()->ReasonD->description : ''}}
                @endif
            </td>
            <td>{{$item->refrence_no}}</td>
            <td>{{$item->serial}}</td>
            <td>{{$item->created_at}}</td>



        </tr>
        @endforeach




    </tbody>
</table>
