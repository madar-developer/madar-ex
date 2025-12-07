    <table>
    <thead>
        @php
        $i = 1;
        @endphp
            <tr>
                <th>#</th>
                <th>   	اسم السيارة  </th>
                <th>     رقم الهيكل	   </th>
                <th>    اللون 	  </th>
                @if (auth('admin')->user()->role == 'admin') 
                <th>    تم الاضافه بواسطه </th>  
                @endif
                <th>  سنه الصنع   </th>
                <th>  نوع السيارة</th>
                <th>   المدينه التى تعمل بها السياره</th>
                <th>   تاريخ إنتهاء الاستماره</th>
                <th>    تاريخ إنتهاء الاستمارة هجري</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            
            <tr>
                <td>{{$i++}} </td>
                <td> {{$item->name}} </td>
                <td>{{$item->structure_no}} </td>
                <td>{{$item->color}}</td>
                @if (auth('admin')->user()->role == 'admin') 
                <td>{{$item->BranchData->Admin->name ?? ''  }}</td>
                @endif
                <td>{{$item->manufacturing_year}}</td>
                <td>{{$item->type}}</td>
                <td> {{$item->work_city}} </td>
                <td> {{$item->license_expiration_date}} </td>
                <td> {{$item->license_expiration_date_hijri}} </td>
            </tr>
            @endforeach
        </tbody>
</table>