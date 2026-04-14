2

<table class="table table-striped table-bordered">
    <thead>
        @php
        $i = 1;
        @endphp
            <tr>
                <th>#</th>
                <th>   	اسم السائق  </th>
                <th>    اسم العائلة	   </th>
                <th>   رقم الجوال 	  </th>
                <th>   رقم الهوية </th>  
                <th> البريد الإلكترونى   </th>
                <th>  الجنسيه</th>
                <th>  رقم الرخصة</th>
                <!-- <th>   تاريخ إنتهاء الرخصة</th> -->
                <!-- <th>    تاريخ إنتهاء الرخصة هجري</th> -->
                <th>     اسم السيارة</th>
                <!-- <th>      انتهاء الهويه</th> -->
                <!-- <th>      انتهاء الهويه هجري </th> -->
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            
            <tr>
                <td>{{$i++}} </td>
                @php
                    $weekStart = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::SATURDAY)->startOfDay();
                    $weekEnd = (clone $weekStart)->addDays(6)->endOfDay();
                    $isActiveThisWeek = !empty($item->last_activity)
                        && \Carbon\Carbon::parse($item->last_activity)->between($weekStart, $weekEnd);
                @endphp
                @if($isActiveThisWeek)
                    <td bgcolor="#d4edda">{{$item->first_name}}</td>
                @else
                    <td bgcolor="#f8d7da">{{$item->first_name}}</td>
                @endif
                <td>{{$item->last_name}} </td>
                <td>{{$item->phone}}</td>
                <td>{{$item->identical_number}}</td>
                <td>{{$item->email}}</td>
                <td> {{$item->nationality}} </td>
                <td> {{$item->license_number}} </td>
                <!-- <td> {{$item->license_date_expiration}} </td> -->
                <!-- <td> {{$item->license_expiration_date_hijri}} </td> -->
                <td> {{$item->Car->name ?? '' }}</td>
                <!-- <td> {{$item->identity_expiration_date }}</td> -->
                <!-- <td> {{$item->identity_expiration_date_hijri }}</td> -->
            </tr>
            @endforeach
        </tbody>
</table>