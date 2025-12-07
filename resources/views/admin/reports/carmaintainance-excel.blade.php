
<table class="table table-striped table-bordered">
    <thead>
        @php
        $i = 1;
        @endphp
            <tr>
                <th>#</th>
                <th>   	 السيارة  </th>
                <th>   	 التكلفه  </th>
                <th>   النوع 	   </th>
                <th>   الشهر 	  </th>
                <th>   ملحوظات </th>  
                <th>   تاريخ الاضافة </th>
        </thead>
        <tbody>
            @foreach($items as $item)
            
            <tr>
                <td>{{$i++}} </td>
                <td>{{$item->Car->name ?? ''}} </td>
              <td> {{$item->cost}} </td>
              <td>{{$item->type}} </td>
              <td>{{$item->month}}</td>
              <td>{{$item->notes}}</td>
              <td>{{$item->created_at}}</td>
            </tr>
            @endforeach
        </tbody>
</table>