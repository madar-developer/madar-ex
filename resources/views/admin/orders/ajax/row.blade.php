    <td>
        <input type="checkbox" name="ids[]" value="{{$item->id}}" class="ids"/>
        just updated
    </td>
    {{--  {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
                            => 'PATCH','files'=>true]) !!}
                            {!! Form::select("status",OrderStatus($item->status),null,['class'=>"form-control select2", "autocomplete"=> 'off', "onchange" => "$(this).closest('form').submit()"])!!}
                            {!!Form::close() !!}  --}}
    <td>{{$item->Company->name ?? ''}} </td>
    <td>{{$item->Company->phone ?? ''}} </td>
    <td>{{$item->recipent_name}} </td>
    <td>{{$item->phone}} </td>
    @if (auth('admin')->user()->role == 'admin')
    <td>{{$item->BranchData->Admin->name ?? ''  }}</td>
    @endif
    <td> {{ $item->City->name ?? '' }}</td>
    <td>{{$item->adress_details}} </td>
    <td>{{$item->packages_number}} </td>
    <td>{{$item->price}}</td>
    <td>{{$item->PaymentMethod->name ?? '' }}</td>
    <td>{{$item->Driver->first_name ?? ''  }}</td>
    <td>{{$item->Car->name ?? ''  }}</td>

    {{--  <td>{{$item->notes}}</td> --}}
    <td>
        {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
        => 'PATCH','files'=>true , 'class'=>'form']) !!}
        {!! Form::hidden('update_row', '1', []) !!}
        {!!
        Form::select("status",OrderStatus($item->status),null,['class'=>"form-control
        select2", "autocomplete"=> 'off', "onchange" =>
        "$(this).closest('form').submit()"])!!}
        {!!Form::close() !!}
        @if ($item->status == 'reschedule' && $item->OrderLog()->count() > 0 && $item->OrderLog()->latest()->first()->status == 'reschedule' && !$item->delivery_date)
            <button class="btn btn-primary rechedule-btn" data-id="{{$item->id}}" data-route="{{url('/dashboard/orders/'.$item->id)}}">
                Set Delivery Date
            </button>

        @elseif($item->status == 'deliver_failed' && $item->OrderLog()->where('status', 'deliver_failed')->latest()->first()->reason == null)
        <br>
        {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
        => 'PATCH','files'=>true , 'class'=>'form']) !!}
        {!! Form::hidden('update_row', '1', []) !!}
        {!!
        Form::select("deliver_failed_id",deliverFailedOptions(),null,['class'=>"form-control
        select2", "autocomplete"=> 'off', "onchange" =>
        "$(this).closest('form').submit()"])!!}
        {!!Form::close() !!}
        @elseif($item->status == 'deliver_failed' )
        <br>
        {{@$item->OrderLog()->where('status', 'deliver_failed')->latest()->first()->ReasonD->description}}
        @elseif($item->OrderLog()->where('status', 'reschedule')->where('active', '1')->first() && $item->delivery_date)
        ({{$item->delivery_date}})
        @endif
    </td>
    {{-- <td>
        {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
        => 'PATCH','files'=>true , 'class'=>'form']) !!}
                                            {!! Form::hidden('update_row', '1', []) !!}
        {!!
        Form::select("status",OrderStatus($item->status),null,['class'=>"form-control
        select2", "autocomplete"=> 'off', "onchange" =>
        "$(this).closest('form').submit()"])!!}
        {!!Form::close() !!}
    </td> --}}
    <td>{{$item->refrence_no}}</td>
    <td>{{$item->serial}}</td>
    <td>{{$item->created_at}}</td>


    <td class="btns">
        @if ($item->status == 'deliver_failed' && $item->Invoice()->count() == 0 )
        <button type="button" title="اصدار بوليصة" class="btn btn-primary   waves-effect waves-light m-b-5 btn-xs create-invoice" data-toggle="modal" data-target="#createInvoice" data-route="{{route('orders-invoice', $item->id)}}">
            <i class="fa fa-download"></i>
            </button>
            @endif
            @if($item->status != "delivered" || auth('admin')->user()->email == 'hussein@madarex.sa')
            <a href="/dashboard/orders/{{$item->id}}/edit" type="button"  title="تعديل"
                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                    class="fa fa-pencil"></i>  </a>
            @endif
            @if( auth('admin')->user()->email == 'hussein@madarex.sa')
            <a href="{{route('orders.destroy',$item)}}" type="button" title="حذف"
                class="btn btn-danger delete-btn  waves-effect waves-light m-b-5 btn-xs">
                <i class="fa fa-times"></i>  </a>
            @endif
        <a href="/dashboard/orders/{{$item->id}}" type="button" title="عرض"
            class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                class="fa fa-eye"></i>  </a>
        <a href="/dashboard/order-bill/{{$item->id}}" type="button" title="طباعة"
            class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                class="fa fa-print"></i>    </a>
                <a href="{{route('order.pdf',$item->id)}}" title="Export Pdf" type="button" class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-file-pdf-o"></i> </a>

    </td>
