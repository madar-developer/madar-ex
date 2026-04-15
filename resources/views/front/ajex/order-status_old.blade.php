@if (!isset($order))

<div class="modal-dialog modal-sm"  id="order-response">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
        </div>
        <div class="modal-body">
            <h2 class="text-center">لايوجد نتائج </h2>
            <div class="img-wr">
                <img src="/error2.svg" alt="">
            </div>


        </div>

    </div>

    <div class="modal-dialog modal-lg"  id="order-response">
@else

@php
    if (in_array($order->status, ['init', 'at_madar'])) {
        $step = 1;
    // } else if (in_array($order->status, [ 'deliver_failed', 'reschedule'])) {
    } else if (in_array($order->status, [ 'reschedule'])) {
        $step = 2;
    } else if (in_array($order->status, ['at_office'])) {
        $step = 3;
    } else if (in_array($order->status, ['delivered'])) {
        $step = 4;
    } else if (in_array($order->status, ['deliver_failed'])) {
        $step = 5;
    } else {
        $step = 0;
    }

@endphp

<div class="modal-dialog modal-lg"  id="order-response">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <h2 class="text-center">حالة الشحنة</h2>
                    <div class="flex-bn">
                        <div class="item">
                            <span class="lbl">رقم الطلب : </span>
                            <span class="val"> {{$order->refrence_no}}</span>
                        </div>
                        <div class="item">
                            <span class="lbl">رقم التتبع : </span>
                            <span class="val"> {{$order->serial}}</span>
                        </div>
                    </div>
                    <div class="flex-bn v2 row">
                        <div class="col-xs-12 col-sm-12 col-md-4">
                            <div class="item flex-c ">
                                <span class="lbl">التاجر</span>
                                <span class="val">{{$order->Company->name ?? ''}} </span>
                            </div>
                        </div>
                        <div class="col-xs-6  col-md-4">
                            <div class="item flex-c  ">
                                <span class="lbl">عدد المنتجات</span>
                                <span class="val"> {{$order->packages_number}}</span>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4">
                            <div class="item flex-c ">
                                <span class="lbl">المدينة</span>
                                <span class="val"> {{ $order->City->name ?? '' }}</span>
                            </div>
                        </div>

                    </div>
                    <div class="state-bar">
                        <div class="step {{($step > 1)? 'done' : (($step == 1)? 'active' : '')}}">
                            <div class="step-text">تم الاستلام</div>
                            <span>
                                <i class="fa fa-archive" aria-hidden="true"></i>
                            </span>
                        </div>
                        <div class="step {{($step > 2)? 'done' : (($step == 2)? 'active' : '')}}">
                            <div class="step-text">في مرحلة انتقالية</div>
                            <span>
                                <i class="fas fa-warehouse"></i>
                            </span>
                        </div>
                        <div class="step  {{($step > 3)? 'done' : (($step == 3)? 'active' : '')}}">
                            <div class="step-text">جارى التوصيل </div>
                            <span>
                                <i class="fas fa-shipping-fast"></i>
                            </span>
                        </div>
                        <div class="step  {{($step >= 4)? (($step == 4)? 'done' : (($step == 5)? 'failed' : '')) : ''}}">
                            <div class="step-text">تم التسليم</div>
                            <span>
                                <i class="fas fa-people-carry"></i>
                            </span>
                        </div>
                    </div>
                    <div class="table-wr">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $last_msg = '';
                                @endphp
                                @foreach ($order->OrderLog()->latest()->get() as $item)
                                {{--  --}}
                                @php
                                if($last_msg == $item->details){
                                continue;
                                }
                                @endphp
                                <tr>
                                    <td>{{$item->created_at->toDateString()}}</td>
                                    <td>
                                        @if ($item->status == 'deliver_failed' || $item->status == 'reschedule')
                                        {{$last_msg = $item->details}}
                                        @else
                                        {{$last_msg = $item->details}}
                                        {{-- {{\App\Models\OrderStatus::where('key',$item->status)->first()->name}} --}}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
</div>
    @if (0)

    ****************************

<div class="row">

    <div class="col-lg-12 text-center" >
        الرقم الطلب: {{$order->refrence_no}}
        <br />
        رقم التتبع: {{$order->serial}}
    </div>
    @foreach(OrderStatus() as $key => $value)
    @php
    //////////////////////////////
    if(in_array($key, ['new','not_received', 'processed'])) continue;
    // if($order->status != $key) continue;
    @endphp
    @if($order->status == $key)
    <div class="col-lg-4" style="text-center">
        <div class="one-state {{$class}}">
            {{--  <img style="width: 60px;" src="https://image.flaticon.com/icons/svg/984/984233.svg">  --}}

            <img style="width: 60px;" src="{{url('/adminto/assets/images/icon02.png')}}">
            <h4> {{$value}} </h4>
        </div>
    </div>
    @php
        break;
    @endphp
    @php
    $class= "to-do";
    @endphp
    @else
    <div class="col-lg-4">
        <div class="one-state {{$class}}">
            <i class="fa fa-check"></i>
            <h4> {{$value}} </h4>
        </div>
    </div>

    @endif
    @endforeach

    <br />
    <br />
    <br />
    {{--  <div class="col-md-12 text-right" style="background-color:#fff; margin-top:  70px;">  --}}
        <div class="col-md-12 text-right" style=" margin-top:  70px;">

        <table class="table table-bordered" style=" border: 1px solid black;">
            <thead>
                <tr style=" border: 1px solid black;">
                    <th style="text-align:right; border: 1px solid black;" >التاريخ</th>
                    <th style="text-align:right; border: 1px solid black;">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->OrderLog()->get() as $item)
                <tr style=" border: 1px solid black;">
                    <td style=" border: 1px solid black;">{{$item->created_at->toDateString()}}</td>
                    <td style=" border: 1px solid black;">
                        @if ($item->status == 'deliver_failed' || $item->status == 'reschedule')
                        {{$item->details}}
                        @else
                        {{$item->details}}
                        {{-- {{\App\Models\OrderStatus::where('key',$item->status)->first()->name}} --}}
                        @endif
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
        <table class="table table-bordered" style=" border: 1px solid black;">
            <thead>
                <tr style=" border: 1px solid black;">
                    <th style="text-align:right; border: 1px solid black;">اسم التاجر</th>
                    <th style="text-align:right; border: 1px solid black;">عدد المنتجات</th>
                    <th style="text-align:right; border: 1px solid black;">المدينه </th>
                </tr>
            </thead>
            <tbody>
                <tr style=" border: 1px solid black;">
                    <td style=" border: 1px solid black;">{{$order->Company->name ?? ''}}</td>
                    <td style=" border: 1px solid black;">{{$order->packages_number}}</td>
                    <td style=" border: 1px solid black;">{{ $order->City->name ?? '' }}</td>
                </tr>

            </tbody>
        </table>
    </div>

</div>

@endif

@endif
