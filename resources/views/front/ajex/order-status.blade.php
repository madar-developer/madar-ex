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
<style>
.shipment-card{
    margin-top: 25px;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px 15px;
    background: #fff;
}

.shipment-head{
    margin-bottom: 30px;
}

.shipment-number .lbl,
.shipment-last-update .lbl{
    display:block;
    font-weight:700;
    margin-bottom:8px;
    color:#555;
}

.shipment-number .val,
.shipment-last-update .val{
    font-size:22px;
    font-weight:700;
    color:#222;
}

.shipment-number .val i{
    margin-left:8px;
    color:#444;
}

.shipment-progress-wrapper{
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin: 35px 0 25px;
    padding: 0 10px;
    overflow-x: auto;
}

.shipment-progress-line{
    position: absolute;
    top: 12px;
    right: 30px;
    left: 30px;
    height: 3px;
    background: #b71c1c;
    z-index: 1;
}

.shipment-progress-step{
    position: relative;
    z-index: 2;
    min-width: 90px;
    text-align: center;
}

.shipment-progress-step .step-circle{
    width: 24px;
    height: 24px;
    margin: 0 auto 10px;
    border-radius: 50%;
    background: #b71c1c;
    color: #fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size: 11px;
    box-shadow: 0 0 0 4px #fff;
}

.shipment-progress-step:not(.done) .step-circle{
    background:#d9d9d9;
    color:#999;
}

.shipment-progress-step.shipment-progress-step--delivered-final .step-circle{
    background: #2ea334;
}

.shipment-status-alert--failed{
    background: #ffebee;
    border: 1px solid #e53935;
    color: #b71c1c;
    border-radius: 8px;
    padding: 12px 14px;
    margin-bottom: 18px;
    text-align: right;
    line-height: 1.6;
}
.shipment-status-alert--failed strong{
    display: block;
    font-size: 15px;
    margin-bottom: 4px;
}
.shipment-status-alert--failed .reason{
    font-size: 13px;
    color: #c62828;
    margin-top: 6px;
}
.shipment-card.shipment-card--failed{
    border-color: #ffcdd2;
    box-shadow: 0 0 0 1px rgba(229, 57, 53, 0.12);
}
.shipment-progress-step.failed .step-circle{
    background: #c62828;
    color: #fff;
}
.shipment-progress-step.failed .step-label{
    color: #c62828;
    font-weight: 700;
}

.shipment-progress-step .step-label{
    font-size: 12px;
    color: #333;
    line-height: 1.6;
}

.shipment-timeline-table .table{
    margin-bottom: 0;
    border: 1px solid #eee;
}

.shipment-timeline-table thead th{
    background: #f3f3f3;
    color: #222;
    font-weight: 700;
    border-bottom: 1px solid #e5e5e5;
    text-align: right;
}

.shipment-timeline-table tbody td{
    vertical-align: middle !important;
    border-top: 1px solid #efefef;
    text-align: right;
}

.timeline-status-row{
    position: relative;
    padding-right: 34px;
}

.timeline-dot{
    position: absolute;
    right: 0;
    top: 2px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: #b71c1c;
    color: #fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size: 10px;
}

.timeline-text{
    display: inline-block;
    line-height: 1.8;
}

@media (max-width: 767px){
    .shipment-number .val,
    .shipment-last-update .val{
        font-size:18px;
    }

    .shipment-progress-step{
        min-width: 80px;
    }

    .shipment-progress-step .step-label{
        font-size: 11px;
    }
}
/*  */
.order-log-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        direction: rtl;
    }
    .order-log-table thead th {
        background: #5c5c5c;
        color: #fff;
        font-weight: 600;
        padding: 12px 14px;
        text-align: right;
        border: none;
        font-size: 13px;
    }
    .order-log-table tbody tr:nth-child(even) {
        background: #f9f9f9;
    }
    .order-log-table tbody tr:nth-child(odd) {
        background: #fff;
    }
    .order-log-table tbody td {
        padding: 14px 12px;
        vertical-align: top;
        border-bottom: 1px solid #eee;
        text-align: right;
    }
    .order-log-table .ot-tl-cell {
        width: 48px;
        position: relative;
        padding-right: 20px !important;
        border-left: 1px solid #eee;
    }
    .order-log-table .ot-tl-rail {
        position: absolute;
        right: 22px;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #e53935;
    }
    .order-log-table tr:last-child .ot-tl-rail {
        bottom: 50%;
    }
    .order-log-table tr:first-child .ot-tl-rail {
        top: 50%;
    }
    .order-log-table .ot-tl-dot {
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #e53935;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 11px;
        z-index: 2;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px #e53935;
    }
    .order-log-table tr.order-log-row--failed .ot-tl-dot{
        background: #c62828;
        box-shadow: 0 0 0 1px #c62828;
    }
    .order-log-table tr.order-log-row--failed td:last-child{
        color: #b71c1c;
        font-weight: 600;
    }
    .order-log-table tr.order-log-row--failed{
        background: #ffebee !important;
    }
    .order-log-table tr.order-log-row--failed .ot-tl-rail{
        background: #c62828;
    }
    .shipment-details-toggle{
        display: block;
        width: 100%;
        margin-top: 10px;
        padding: 12px 16px;
        border: 1px solid #eee;
        border-radius: 8px;
        background: #fafafa;
        color: #222;
        font-weight: 700;
        text-align: right;
        text-decoration: none !important;
        cursor: pointer;
    }
    .shipment-details-toggle:hover,
    .shipment-details-toggle:focus{
        background: #f3f3f3;
        color: #222;
        text-decoration: none !important;
    }
    .shipment-details-toggle .fa{
        margin-left: 8px;
        transition: transform .2s ease;
    }
    .shipment-details-toggle[aria-expanded="true"] .fa{
        transform: rotate(180deg);
    }
    .shipment-details-collapse{
        margin-top: 12px;
    }
    .order-log-table tr.order-log-row--delivered .ot-tl-rail{
        background: #43a047;
    }
    .order-log-table tr.order-log-row--delivered .ot-tl-dot{
        background: #2e7d32;
        box-shadow: 0 0 0 1px #2e7d32;
    }
    .order-log-table tr.order-log-row--delivered td:last-child{
        color: #1b5e20;
        font-weight: 600;
    }
    .order-log-table tr.order-log-row--delivered{
        background: #e8f5e9 !important;
    }
    .order-log-table .ot-loc-en {
        direction: ltr;
        text-align: left;
        font-size: 13px;
        color: #444;
    }
    .shipment-progress-step.done.shipment-progress-step--delivered-final .step-circle{
        border: 3px solid #92e49c;
    }
</style>
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
                    <h2 class="text-center">حالة الشحنة : {{$order->status_txt}}</h2>
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
                                <span class="lbl">تاريخ الشحنة </span>
                                <span class="val"> {{$order->created_at->format('d/m/Y')}}</span>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4">
                            <div class="item flex-c ">
                                <span class="lbl">المدينة</span>
                                <span class="val"> {{ $order->City->name ?? '' }}</span>
                            </div>
                        </div>

                    </div>
                    <!-- start replace -->
                     @if(0)
                    <div class="state-bar">
                        <div class="step {{($step > 1)? 'done' : (($step == 1)? 'active' : '')}}">
                            <div class="step-text">جاري تجهيز الشحنة</div>
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
                        <div class=" 11 step  {{($step >= 4)? (($step == 4)? 'done' : (($step == 5)? 'failed' : '')) : ''}}">
                            <div class="step-text">تم التسليم</div>
                            <span>
                                <i class="fas fa-people-carry"></i> 1
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
                    @else
                        <!-- start replace -->

                        @php
                            $progressSteps = [
                                1 => 'تم الاستلام',
                                2 => 'تم استلام الطلب من المتجر ',
                                //3 => 'الخروج في جولة',
                                //4 => 'في الطريق',
                                5 => 'الشحنه فى مستودع مدار ',
                                6 => 'جاري التوصيل ',
                                7 => 'تم التسليم',
                                //8 => 'فشل التسليم'
                            ];

                            $statusMap = [
                                'init' => 1,
                                'at_madar' => 2,
                                //'reschedule' => 3,
                                'at_madar' => 5,
                                'at_office' => 6,

                                'delivered' => 7,
                                'deliver_failed' => 6,
                                //'deliver_failed' => 8,
                            ];

                            $orderStatusIsFailed = $order->status === 'deliver_failed';

                            $currentProgress = $statusMap[$order->status] ?? 1;

                            $logs = $order->OrderLog()->latest()->get();

                            $last_msg = '';
                            $filteredLogs = [];

                            foreach ($logs as $item) {
                                if ($last_msg == $item->details) {
                                    continue;
                                }

                                $filteredLogs[] = $item;
                                $last_msg = $item->details;
                            }

                            $latestShownLog = null;
                            $lmLatest = '';
                            foreach ($logs as $item) {
                                if ($lmLatest === $item->details) {
                                    continue;
                                }
                                $latestShownLog = $item;
                                break;
                            }
                            $lastStatusIsDelivered = ($order->status === 'delivered')
                                || ($latestShownLog && $latestShownLog->status === 'delivered');
                        @endphp

                        <div class="shipment-card {{ $orderStatusIsFailed ? 'shipment-card--failed' : '' }}">
                            @if($orderStatusIsFailed)
                                <div class="shipment-status-alert shipment-status-alert--failed" role="alert">
                                    <strong>{{ $order->status_txt ?: 'فشل التسليم' }}</strong>
                                    @if(trim((string) $order->reason) !== '')
                                        <div class="reason">{{ $order->reason }}</div>
                                    @endif
                                </div>
                            @endif
                            <!-- <div class="shipment-head row">
                                <div class="col-xs-12 col-sm-6 text-right">
                                    <div class="shipment-number">
                                        <span class="lbl">رقم تتبع الشحنة</span>
                                        <div class="val">
                                            <i class="fa fa-truck" aria-hidden="true"></i>
                                            {{$order->serial}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 text-left">
                                    @if(count($filteredLogs))
                                        <div class="shipment-last-update">
                                            <span class="lbl">التحديث الأخير</span>
                                            <div class="val">
                                                {{$filteredLogs[0]->details}}<br>
                                                <small>{{$filteredLogs[0]->created_at->format('H:i d M y')}}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div> -->

                            <div class="shipment-progress-wrapper">
                                <div class="shipment-progress-line"></div>

                                @foreach($progressSteps as $index => $label)
                                    @php
                                        if ($orderStatusIsFailed && $loop->last) {
                                            $stepClass = 'failed';
                                        } else {
                                            $stepClass = $index <= $currentProgress ? 'done' : '';
                                        }
                                        $deliveredFinal = ! $orderStatusIsFailed && $loop->last && $lastStatusIsDelivered && $index <= $currentProgress;
                                    @endphp

                                    <div class="shipment-progress-step {{ $stepClass }} {{ $deliveredFinal ? 'shipment-progress-step--delivered-final' : '' }}">
                                        <div class="step-circle">
                                            <i class="fa {{ ($orderStatusIsFailed && $loop->last) ? 'fa-times' : 'fa-check' }}"></i>
                                        </div>
                                        <div class="step-label">{{$label}}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="shipment-details-accordion">
                                <a class="shipment-details-toggle collapsed"
                                   role="button"
                                   data-toggle="collapse"
                                   href="#shipment-details-log"
                                   aria-expanded="false"
                                   aria-controls="shipment-details-log">
                                    <i class="fa fa-chevron-down"></i>
                                    اظهار تفاصيل الشحنه
                                </a>
                                <div id="shipment-details-log" class="collapse shipment-details-collapse">
                                    <div class="table-wr shipment-timeline-table">
                                        <table class="order-log-table table">
                                            <thead>
                                                <tr>
                                                    <th class="ot-tl-cell"></th>
                                                    <th>التاريخ</th>
                                                    <th>الحالة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $last_msg = '';
                                                @endphp
                                                @forelse($order->OrderLog()->latest()->get()  as $log)
                                                @php
                                                if($last_msg == $log->details){
                                                continue;
                                                }
                                                $last_msg = $log->details;
                                                $logRowFailed = $log->status === 'deliver_failed';
                                                $logRowDelivered = ! $logRowFailed && $log->status === 'delivered';
                                                $logRowClass = $logRowFailed ? 'order-log-row--failed' : ($logRowDelivered ? 'order-log-row--delivered' : '');
                                                @endphp
                                                    <tr class="{{ $logRowClass }}">
                                                        <td class="ot-tl-cell">
                                                            <span class="ot-tl-rail"></span>
                                                            <span class="ot-tl-dot"><i class="fa {{ $logRowFailed ? 'fa-times' : 'fa-check' }}"></i></span>
                                                        </td>
                                                        <td>
                                                            <div>{{ $log->created_at->format('d/m/Y') }}</div>
                                                            <div style="color:#888;font-size:12px;">{{ $log->created_at->format('H:i') }}</div>
                                                        </td>
                                                        <td>
                                                            <div>{{ $log->details }}</div>
                                                            
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center" style="padding:24px;">لا توجد سجلات.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        <!--  -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- end replace -->
                    @endif
                    <!-- end replace -->

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
