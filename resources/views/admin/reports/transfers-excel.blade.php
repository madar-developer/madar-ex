
<table >
    <thead>
            <tr>
                <th>#</th>
                <th>     المتجر 	   </th>
                <th>      الاجمالي	   </th>
                <th>      المستحق للشركة 	   </th>
                <th> 	تكلفة التوصيل    </th>
                <th>التاريخ من</th>
                <th>التاريخ الي</th>
                <th>التحصيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            
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
            </tr>
            @endforeach
        </tbody>
</table>