<table class="table table-striped table-bordered">
    <thead>
        @php
        $i = 1;
        @endphp
        <thead>
            <tr>
                <th> # </th>
                <th> اسم المتجر </th>
                <th>رقم الجوال  </th>
                @if (auth('admin')->user()->role == 'admin') 
                <th>  تم الاضافه بواسطه</th>
                @endif
                <th> البريد الالكترونى  </th>
                <th> المدينه</th>
                <th> العنوان بالتفصيل</th>
                <th> السجل التجارى</th>
                <th> حاله المتجر</th>
                <th>  عدد الطلبات</th>
                <th>   تكلفه ارجاع الطلب</th>
            </tr>
        </thead>

        <tbody>
            @foreach($items as $item)
            
            <tr>
                <td>{{$i++}} </td>
                <td> {{$item->name}} </td>
                <td>{{$item->phone}} </td>
                @if (auth('admin')->user()->role == 'admin') 
                <td>{{$item->BranchData->Admin->name ?? ''  }}</td>
                @endif
                <td>{{$item->email}}</td>
                <td>{{$item->city->name ?? ''}}</td>
                <td>{{$item->adress_details}}</td>
                
                <td> {{$item->commercial_record}} </td>
                <td>{{($item->active == '1')? 'مفعل' : 'غير مفعل'}}</td>
                <td>{{$item->Order()->count()}}</td>
                <td>{{$item->return_cost}}</td>
            </tr>
            @endforeach
        

            
            
        </tbody>
</table>