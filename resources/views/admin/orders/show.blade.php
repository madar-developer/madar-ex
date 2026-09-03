@extends('admin.layout.app')
@section('style')
<style>
    table {
        border-collapse: collapse;
        border: 1px solid black;
    }


    td,
    th {

        text-align: right;
        padding: 7px;
        border-bottom: 1px solid black;
    }
    .qrcode div{
        margin: auto;
    }

    @media print {
        .xc {
            text-align: left !important;
            border: 1px solid #000 !important;
            background-color: #000 !important;
            color: #fff !important;
            width: 2rem !important;
        }
    }

    /* Order tracking card + stepper (RTL) */
    .order-track-card {
        background: #f5f5f7;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px 24px 28px;
        margin-bottom: 24px;
        direction: rtl;
        text-align: right;
    }
    .order-track-card .ot-badge {
        display: inline-block;
        background: #b71c1c;
        color: #fff;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 4px;
        margin-bottom: 12px;
    }
    .order-track-head {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 16px;
    }
    .order-track-head .ot-tracking {
        flex: 1 1 200px;
    }
    .order-track-head .ot-tracking .ot-label {
        color: #666;
        font-size: 13px;
        margin-bottom: 4px;
    }
    .order-track-head .ot-tracking .ot-value {
        font-size: 18px;
        font-weight: 600;
    }
    .order-track-head .ot-last {
        flex: 1 1 280px;
        text-align: center;
    }
    .order-track-head .ot-last .ot-label {
        color: #666;
        font-size: 13px;
        margin-bottom: 6px;
    }
    .order-track-head .ot-last .ot-msg {
        font-size: 14px;
        line-height: 1.5;
        color: #333;
    }
    .order-track-head .ot-last .ot-time {
        font-size: 12px;
        color: #888;
        margin-top: 6px;
    }
    .order-track-od {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 24px;
    }
    .order-track-od .ot-orig,
    .order-track-od .ot-dest {
        flex: 1 1 220px;
    }
    .order-track-od .ot-od-label {
        color: #666;
        font-size: 12px;
        margin-bottom: 4px;
    }
    .order-track-od .ot-od-val {
        font-size: 15px;
        font-weight: 500;
    }
    .ot-stepper-wrap {
        margin-top: 8px;
        padding-top: 8px;
    }
    .ot-stepper {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        position: relative;
        padding: 0 4px;
    }
    .ot-step {
        flex: 1;
        text-align: center;
        position: relative;
        min-width: 0;
    }
    .ot-step-node {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }
    .ot-step-line {
        flex: 1;
        height: 4px;
        background: #d9d9d9;
        min-width: 8px;
        margin-top: 10px;
    }
    .ot-step-line.is-done {
        background: #b71c1c;
        margin-bottom: 3rem;
    }
    .ot-dot {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #d9d9d9;
        border: 3px solid #d9d9d9;
        flex-shrink: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #999;
    }
    .ot-dot.is-done {
        background: #b71c1c;
        border-color: #b71c1c;
        color: #fff;
    }
    .ot-dot.is-current {
        background: #fff;
        border-color: #b71c1c;
        box-shadow: 0 0 0 6px rgba(183, 28, 28, 0.25);
    }
    .ot-dot.is-delivered {
        background: #2e7d32;
        border-color: #2e7d32;
        color: #fff;
    }
    .ot-dot.is-returned {
        background: #fb8c00;
        border-color: #fb8c00;
        color: #fff;
    }
    .ot-dot.is-failed {
        background: #c62828;
        border-color: #c62828;
        color: #fff;
    }
    .ot-step.ot-step--failed .ot-step-label {
        color: #c62828;
        font-weight: 700;
    }
    .order-track-card.order-track-card--failed {
        border-color: #ffcdd2;
        box-shadow: 0 0 0 1px rgba(229, 57, 53, 0.12);
    }
    .order-status-alert--failed {
        background: #ffebee;
        border: 1px solid #e53935;
        color: #b71c1c;
        border-radius: 8px;
        padding: 12px 14px;
        margin-bottom: 18px;
        text-align: right;
        line-height: 1.6;
    }
    .order-status-alert--failed strong {
        display: block;
        font-size: 15px;
        margin-bottom: 4px;
    }
    .order-status-alert--failed .reason {
        font-size: 13px;
        color: #c62828;
        margin-top: 6px;
    }
    .ot-step-label {
        font-size: 11px;
        line-height: 1.35;
        color: #555;
        padding: 0 2px;
        word-break: break-word;
    }
    @media (max-width: 991px) {
        .ot-step-label { font-size: 10px; }
    }

    /* Activity log timeline table */
    .order-log-panel {
        margin-top: 8px;
    }
    .order-log-table-wrap {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        overflow: hidden;
    }
    .order-log-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
        direction: rtl;
    }
    .order-log-table thead th {
        background: #f3f3f3;
        color: #222;
        font-weight: 700;
        padding: 12px 14px;
        text-align: right;
        border: none;
        border-bottom: 1px solid #e5e5e5;
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
        background: #b71c1c;
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
        background: #b71c1c;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 11px;
        z-index: 2;
        border: 2px solid #fff;
        box-shadow: 0 0 0 1px #b71c1c;
    }
    .order-log-table tr.order-log-row--failed .ot-tl-dot {
        background: #c62828;
        box-shadow: 0 0 0 1px #c62828;
    }
    .order-log-table tr.order-log-row--failed td:last-child {
        color: #b71c1c;
        font-weight: 600;
    }
    .order-log-table tr.order-log-row--failed {
        background: #ffebee !important;
    }
    .order-log-table tr.order-log-row--failed .ot-tl-rail {
        background: #c62828;
    }
    .order-log-table .ot-loc-en {
        direction: ltr;
        text-align: left;
        font-size: 13px;
        color: #444;
    }
    .ot-dot.is-delivered {
        width: 30px;
        height: 30px;
        background: #2ea334;
        border: 5px solid #92e49c;
    }
    .order-image-groups {
        direction: rtl;
        text-align: right;
        margin: 20px 0 28px;
    }
    .order-image-groups h3 {
        text-align: center;
        margin-bottom: 18px;
    }
    .order-image-group {
        background: #f5f5f7;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 16px 18px 12px;
        margin-bottom: 16px;
    }
    .order-image-group-head {
        margin-bottom: 12px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e0e0e0;
    }
    .order-image-group-badge {
        display: inline-block;
        color: #fff;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 4px;
        background: #b71c1c;
        margin-left: 8px;
    }
    .order-image-group-unlinked {
        background: #757575;
    }
    .order-image-group-meta {
        color: #888;
        font-size: 12px;
        margin-top: 6px;
    }
    .order-image-group-details {
        color: #444;
        font-size: 13px;
        margin-top: 6px;
        line-height: 1.5;
    }
    .order-image-group-thumbs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .order-image-group-thumbs a img {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 6px;
        background: #fff;
    }

    /* Live delivery tracking map (at_office only) */
    .order-live-track {
        background: #f5f5f7;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 18px 20px 20px;
        margin-bottom: 24px;
        direction: rtl;
        text-align: right;
    }
    .order-live-track h4 {
        margin: 0 0 12px;
        font-size: 16px;
        font-weight: 700;
    }
    .order-live-track-map {
        width: 100%;
        height: 420px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background: #e9ecef;
    }
    .order-live-track-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 12px;
        font-size: 13px;
        color: #444;
    }
    .order-live-track-legend span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .order-live-track-legend i {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .order-live-track-legend .lg-start { background: #2e7d32; }
    .order-live-track-legend .lg-driver { background: #1565c0; }
    .order-live-track-legend .lg-dest { background: #c62828; }
    .order-live-track-meta {
        margin-top: 10px;
        font-size: 12px;
        color: #666;
    }
    .order-live-track-alert {
        margin-top: 12px;
        margin-bottom: 0;
        text-align: right;
    }
</style>
@endsection
@section('header')
        <div class="add-btn">
            <a href="/dashboard/order-bill/{{$order->id}}" id='btn'
                class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-print"></i>
            </a>
        </div>
@endsection
@section('content')
<div class="card-box">

    <div class="row">

        <div class="col-md-6">
            <div class="col-md-12 text-center" style="">
                <h3> تفاصيل الطلب </h3>
              </div>
            <table class="table table-striped" style="  border: 1px solid gray;">
                <thead>

                <tbody>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;">اسم المستلم</th>
                        <td style="  border: 1px solid gray;">{{$order->recipent_name}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> رقم الجوال</th>
                        <td style="  border: 1px solid gray;">{{$order->phone}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> المدينه </th>
                        <td style="  border: 1px solid gray;">{{$order->City->name ?? ''}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> العنوان بالتفصيل </th>
                        <td style="  border: 1px solid gray;">{{$order->adress_details}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> عدد المنتجات </th>
                        <td style="  border: 1px solid gray;">{{$order->packages_number}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> السعر </th>
                        <td style="  border: 1px solid gray;">{{$order->price}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> طريقه الدفع </th>
                        <td style="  border: 1px solid gray;">{{$order->PaymentMethod->name ?? ''}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> الحاله </th>

                        <td style="  border: 1px solid gray;">{{@$order->OrderLog()->where('status', $order->status)->latest()->first()->details}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> رقم المرجع </th>
                        <td style="  border: 1px solid gray;">{{$order->refrence_no}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> الرقم المتسلسل </th>
                        <td style="  border: 1px solid gray;">{{$order->serial}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> تاريخ الانشاء </th>
                        <td style="  border: 1px solid gray;">{{$order->created_at}}</td>

                    </tr>
                </tbody>
            </table>
        </div>




        <div class="col-md-6">
            <div class="col-md-12 text-center" style="">
                <h3> تفاصيل المتجر </h3>
              </div>
            <table class="table table-striped" style="  border: 1px solid gray;">
                <thead>

                <tbody>
                    <tr style="  border: 1px solid gray;">
                        <th scope="row" style="  border: 1px solid gray; color:#000;">اسم المتجر</th>
                        <td style="  border: 1px solid gray;">{{$order->Company->name ?? ''}}</td>

                    </tr>

                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> رقم تليفون المتجر</th>
                        <td style="  border: 1px solid gray;">{{$order->Company->phone ?? ''}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;">البريد الالكترونى</th>
                        <td style="  border: 1px solid gray;">{{$order->Company->email ?? ''}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> المدينه</th>
                        <td style="  border: 1px solid gray;">{{$order->Company->City->name ?? ''}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> العنوان بالتفصيل</th>
                        <td style="  border: 1px solid gray;">{{$order->Company->adress_details ?? ''}}</td>

                    </tr>
                    {{--  <tr>
            <th scope="row" style="  border: 1px solid gray; color:#000;">  حاله المتجر</th>
            <td style="  border: 1px solid gray;">{{$order->Company->active ?? ''}}</td>

                    </tr> --}}
                </tbody>
            </table>
        </div>
        @php $imageGroups = $order->imageGroups(); @endphp
        @if($imageGroups->isNotEmpty())
        <div class="col-md-12 order-image-groups">
            <h3>صور الطلب</h3>
            @foreach($imageGroups as $group)
            <div class="order-image-group">
                <div class="order-image-group-head">
                    @if($group['status'])
                        <span class="order-image-group-badge" @if($group['status_color']) style="background: {{ $group['status_color'] }};" @endif>
                            مرتبطة بالحالة: {{ $group['status_txt'] ?: $group['status'] }}
                        </span>
                    @else
                        <span class="order-image-group-badge order-image-group-unlinked">صور غير مرتبطة بحالة</span>
                    @endif
                    @if($group['created_at'])
                        <div class="order-image-group-meta">تاريخ الرفع: {{ $group['created_at']->format('d/m/Y H:i') }}</div>
                    @endif
                    @if($group['status'] && $group['status_details'])
                        <div class="order-image-group-details">{{ $group['status_details'] }}</div>
                    @endif
                </div>
                <div class="order-image-group-thumbs">
                    @foreach($group['files'] as $file)
                    <a href="{{ getImage($file->name) }}" target="_blank">
                        <img src="{{ getImage($file->name) }}" alt="">
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @endif
        <div class="col-md-12 qrcode" >

            @php
            echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->id.'', 'C39+') . '" alt="barcode"   />';
        @endphp
        </div>
        <div class="col-md-12">
            <!-- new order stepper step + logs -->
            @php
                $st = $order->status;
                $lastStepIndex = max(0, count($stepLabels) - 1);
                if ($st === 'returned') {
                    $stepLabels[$lastStepIndex] = $returnedStepLabel ?? 'تم الإرجاع';
                }
                $orderStatusIsFailed = ($st === 'deliver_failed');
                /* Indices follow $stepLabels: 0 new, 1 init, 2 at_madar, 3 at_office, 4 delivered */
                $currentStepIndex = match ($st) {
                    'new' => 0,
                    'not_received' => 1,
                    'init' => 1,
                    'at_madar' => 2,
                    'at_office', 'reschedule' => 3,
                    'deliver_failed' => $lastStepIndex,
                    'delivered', 'returned' => 7,
                    'cancelled' => 0,
                    default => 0,
                };
                $allStepsComplete = ($st === 'delivered') || ($st === 'returned');
                $originCity = $order->Company?->City ? (string) $order->Company->City->name : '';
                $destCity = $order->City ? (string) $order->City->name : '';
                $trackNo = $order->serial ?: ($order->refrence_no ?: '—');
                $lastLog = $lastLog ?? $orderLogs->sortByDesc('id')->first();
            @endphp

            <div class="order-track-card order-log-panel {{ $orderStatusIsFailed ? 'order-track-card--failed' : '' }}">
                <!-- <span class="ot-badge">
                    @if(($order->order_type ?? '') === 'outside' || ($order->order_type ?? '') === 'external')
                        شحنة دولية
                    @else
                        شحنة محلية
                    @endif
                </span> -->

                <div class="order-track-head">
                    <div class="ot-tracking">
                        <div class="ot-label"><i class="fa fa-truck m-l-5"></i> رقم التتبع للشحنة</div>
                        <div class="ot-value">{{ $trackNo }}</div>
                    </div>
                    <div class="ot-last">
                        <div class="ot-label">التحديث الأخير</div>
                        @if($lastLog)
                            <div class="ot-msg">{{ $lastLog->details }}</div>
                            <div class="ot-time">{{ $lastLog->created_at->format('d/m/Y H:i:s') }}</div>
                        @else
                            <div class="ot-msg text-muted">لا توجد سجلات بعد</div>
                        @endif
                    </div>
                </div>

                <!-- <div class="order-track-od">
                    <div class="ot-orig">
                        <div class="ot-od-label">المنشأ</div>
                        <div class="ot-od-val">المملكة العربية السعودية @if($originCity) / {{ $originCity }} @endif</div>
                    </div>
                    <div class="ot-dest">
                        <div class="ot-od-label">الوجهة</div>
                        <div class="ot-od-val">المملكة العربية السعودية @if($destCity)<br><span class="ot-loc-en" style="display:inline-block;margin-top:4px;">{{ $destCity }}</span>@endif</div>
                    </div>
                </div> -->

                @if($st === 'cancelled')
                    <div class="alert alert-warning m-b-20" style="text-align:right;">تم إلغاء هذا الطلب.</div>
                @endif

                @if($orderStatusIsFailed)
                    <div class="order-status-alert--failed" role="alert">
                        <strong>{{ $order->status_txt ?: 'فشل التسليم' }}</strong>
                        @if(trim((string) ($order->reason ?? '')) !== '')
                            <div class="reason">{{ $order->reason }}</div>
                        @endif
                    </div>
                @endif

                <div class="ot-stepper-wrap">
                    <div class="ot-stepper">
                        @foreach($stepLabels as $i => $label)
                            <div class="ot-step {{ $orderStatusIsFailed && $i === $lastStepIndex ? 'ot-step--failed' : '' }}">
                                <div class="ot-step-node">
                                    <div class="ot-dot
                                        @if($orderStatusIsFailed && $i === $lastStepIndex) is-failed
                                        @elseif($allStepsComplete && $st === 'delivered' && $i === $lastStepIndex) is-delivered
                                        @elseif($allStepsComplete && $st === 'returned' && $i === $lastStepIndex) is-returned
                                        @elseif($allStepsComplete && $i < $lastStepIndex) is-done
                                        @elseif(!$allStepsComplete && $i < $currentStepIndex) is-done
                                        @elseif(!$allStepsComplete && $i === $currentStepIndex && ! $orderStatusIsFailed) is-current
                                        @endif
                                    ">
                                    
                                @php
                                    if ($allStepsComplete) {
                                        $segDone1 = true;
                                    } else {
                                        $segDone1 = ($i < $currentStepIndex);
                                    }
                                @endphp
                                    @if($orderStatusIsFailed && $i === $lastStepIndex)
                                        <i class="fa fa-times"></i>
                                    @elseif($segDone1)
                                        <i class="fa fa-check"></i>
                                    @endif
                                        <!-- @if($allStepsComplete && $st === 'delivered' && $i === $lastStepIndex)
                                            <i class="fa fa-check"></i>
                                        @elseif($allStepsComplete && $st === 'returned' && $i === $lastStepIndex)
                                            <i class="fa fa-undo"></i>
                                        @endif -->
                                    </div>
                                </div>
                                <div class="ot-step-label">{{ $label }}</div>
                            </div>
                            @if($i < $lastStepIndex)
                                @php
                                    if ($allStepsComplete) {
                                        $segDone = true;
                                    } else {
                                        $segDone = ($i < $currentStepIndex);
                                    }
                                @endphp
                                <div class="ot-step-line {{ $segDone ? 'is-done' : '' }}">
                                    
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            @if(!empty($trackingData))
            <div class="order-live-track">
                <h4><i class="fa fa-map-marker m-l-5"></i> تتبع التوصيل المباشر</h4>
                <div id="order-live-track-map" class="order-live-track-map"></div>
                <div class="order-live-track-legend">
                    <span><i class="lg-start"></i> نقطة البداية (أول حضور)</span>
                    <span><i class="lg-driver"></i> موقع السائق الحالي</span>
                    <span><i class="lg-dest"></i> عنوان التسليم</span>
                </div>
                <div id="order-live-track-meta" class="order-live-track-meta"></div>
                <div id="order-live-track-alert" class="alert alert-warning order-live-track-alert" style="display:none;"></div>
            </div>
            @endif

            <div class="order-log-panel">
                <h4 class="m-b-15" style="text-align:right;">سجل النشاط</h4>
                <div class="order-log-table-wrap">
                    <table class="order-log-table table">
                        <thead>
                            <tr>
                                <th class="ot-tl-cell"></th>
                                <th>التاريخ</th>
                                <th>الموقع</th>
                                <th>النشاط</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orderLogs->sortByDesc('id') as $log)
                                @php
                                    $locAr = $order->City ? (string) $order->City->name : '';
                                    $locLine = $locAr ? ($locAr.', Saudi Arabia') : 'Madar Express';
                                    $logRowFailed = ($log->status ?? '') === 'deliver_failed';
                                @endphp
                                <tr class="{{ $logRowFailed ? 'order-log-row--failed' : '' }}">
                                    <td class="ot-tl-cell">
                                        <span class="ot-tl-rail"></span>
                                        <span class="ot-tl-dot"><i class="fa {{ $logRowFailed ? 'fa-times' : 'fa-check' }}"></i></span>
                                    </td>
                                    <td>
                                        <div>{{ $log->created_at->format('d/m/Y') }}</div>
                                        <div style="color:#888;font-size:12px;">{{ $log->created_at->format('H:i') }}</div>
                                    </td>
                                    <td>
                                        <div class="ot-loc-en">{{ $locLine }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $log->details }}</div>
                                        <div style="font-size:12px;color:#888;margin-top:6px;">
                                            @if($log->added_by_type === 'driver')
                                                @php $d2 = $driversById->get($log->added_by_id); @endphp
                                                {{ $d2 ? ('سائق: '.trim($d2->first_name.' '.$d2->last_name)) : 'سائق' }}
                                            @else
                                                لوحة التحكم
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center" style="padding:24px;">لا توجد سجلات.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if(!empty($trackingData))
@section('script')
<script type="text/javascript"
    src="https://maps.google.com/maps/api/js?sensor=false&libraries=places&key={{ getMapsKey() }}&language=ar"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.2/firebase-analytics.js"></script>
<script>
    var orderTrackingData = @json($trackingData);

    (function () {
        var mapEl = document.getElementById('order-live-track-map');
        var metaEl = document.getElementById('order-live-track-meta');
        var alertEl = document.getElementById('order-live-track-alert');
        if (!mapEl || typeof google === 'undefined' || !google.maps) {
            return;
        }

        var defaultCenter = { lat: 24.7255553, lng: 47.1027146 };
        var map = new google.maps.Map(mapEl, {
            zoom: 12,
            center: defaultCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var infowindow = new google.maps.InfoWindow();
        var bounds = new google.maps.LatLngBounds();
        var hasPoint = false;
        var mapFitted = false;
        var routePath = [];
        var markers = {};
        var driverMarker = null;
        var routeLine = null;
        var driverId = parseInt(orderTrackingData.driver_id, 10);

        function extendBounds(lat, lng) {
            if (lat == null || lng == null || isNaN(lat) || isNaN(lng)) {
                return null;
            }
            var pos = { lat: parseFloat(lat), lng: parseFloat(lng) };
            bounds.extend(pos);
            hasPoint = true;
            return pos;
        }

        function addMarker(key, pos, options) {
            if (!pos) {
                return null;
            }
            if (markers[key]) {
                markers[key].setMap(null);
            }
            var marker = new google.maps.Marker(Object.assign({
                position: pos,
                map: map
            }, options || {}));
            markers[key] = marker;
            return marker;
        }

        function updateRouteLine() {
            if (routeLine) {
                routeLine.setMap(null);
            }
            var path = [];
            if (markers.start) {
                path.push(markers.start.getPosition().toJSON());
            }
            if (driverMarker) {
                path.push(driverMarker.getPosition().toJSON());
            }
            if (markers.destination) {
                path.push(markers.destination.getPosition().toJSON());
            }
            if (path.length < 2) {
                return;
            }
            routeLine = new google.maps.Polyline({
                path: path,
                geodesic: true,
                strokeColor: '#1565c0',
                strokeOpacity: 0.85,
                strokeWeight: 4,
                map: map
            });
        }

        function fitMapOnce() {
            if (mapFitted || !hasPoint) {
                return;
            }
            map.fitBounds(bounds, 48);
            mapFitted = true;
        }

        function showAlert(message) {
            if (!alertEl) {
                return;
            }
            alertEl.style.display = 'block';
            alertEl.textContent = message;
        }

        function hideAlert() {
            if (!alertEl) {
                return;
            }
            alertEl.style.display = 'none';
            alertEl.textContent = '';
        }

        if (orderTrackingData.start) {
            var startPos = extendBounds(orderTrackingData.start.lat, orderTrackingData.start.lng);
            var startMarker = addMarker('start', startPos, {
                title: orderTrackingData.start.label,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: '#2e7d32',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2
                }
            });
            if (startMarker) {
                startMarker.addListener('click', function () {
                    var html = '<strong>' + orderTrackingData.start.label + '</strong>';
                    if (orderTrackingData.start.time) {
                        html += '<br>' + orderTrackingData.start.time;
                    }
                    infowindow.setContent(html);
                    infowindow.open(map, startMarker);
                });
            }
        } else {
            showAlert('لا يوجد سجل حضور للسائق — لن تظهر نقطة البداية.');
        }

        function setDestinationMarker(pos, title) {
            var destMarker = addMarker('destination', pos, {
                title: title || orderTrackingData.destination.label,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: '#c62828',
                    fillOpacity: 1,
                    strokeColor: '#ffffff',
                    strokeWeight: 2
                }
            });
            if (destMarker) {
                destMarker.addListener('click', function () {
                    infowindow.setContent('<strong>' + (title || orderTrackingData.destination.label) + '</strong>');
                    infowindow.open(map, destMarker);
                });
            }
            updateRouteLine();
            fitMapOnce();
        }

        function resolveDestination() {
            var dest = orderTrackingData.destination || {};
            if (dest.lat != null && dest.lng != null && !isNaN(dest.lat) && !isNaN(dest.lng)) {
                var destPos = extendBounds(dest.lat, dest.lng);
                setDestinationMarker(destPos, dest.label);
                return;
            }
            if (!dest.address) {
                showAlert('لا يوجد عنوان أو إحداثيات لموقع التسليم.');
                fitMapOnce();
                return;
            }
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ address: dest.address }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    var loc = results[0].geometry.location;
                    var destPos = extendBounds(loc.lat(), loc.lng());
                    setDestinationMarker(destPos, dest.label);
                } else {
                    showAlert('تعذر تحديد موقع عنوان التسليم على الخريطة.');
                    fitMapOnce();
                }
            });
        }

        function updateDriverMarker(data, location) {
            var lat = location.lat;
            var lng = location.long;
            var pos = extendBounds(lat, lng);
            if (!pos) {
                return;
            }

            var updatedAt = '';
            if (location.timestamp) {
                updatedAt = new Date(location.timestamp).toLocaleString('ar-SA');
            }

            var htmlCard = '<div class="map-menu">' +
                '<div class="d-name">' + (data.name || orderTrackingData.driver_name || 'السائق') + '</div>' +
                '<div class="flex-wrap">' +
                    '<div class="item"><span class="lbl">عدد الطلبات : </span><span class="val">' + (data.order_count || 0) + '</span></div>' +
                    '<div class="item"><span class="lbl">جاري التوصيل : </span><span class="val">' + (data.delivering_orders_count || 0) + '</span></div>' +
                    '<div class="item"><span class="lbl">تم التسليم : </span><span class="val">' + (data.order_delivered_count || 0) + '</span></div>' +
                    '<div class="item"><span class="lbl">لم تسلم : </span><span class="val">' + (data.order_failed_count || 0) + '</span></div>' +
                '</div>' +
                (updatedAt ? '<div style="margin-top:8px;font-size:12px;">آخر تحديث: ' + updatedAt + '</div>' : '') +
            '</div>';

            if (!driverMarker) {
                driverMarker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    title: data.name || orderTrackingData.driver_name || 'السائق',
                    icon: 'https://madarex.sa/Location.png'
                });
                driverMarker.addListener('click', function () {
                    infowindow.setContent(htmlCard);
                    infowindow.open(map, driverMarker);
                });
            } else {
                driverMarker.setPosition(pos);
            }

            if (metaEl && updatedAt) {
                metaEl.textContent = 'آخر تحديث لموقع السائق: ' + updatedAt;
            }

            hideAlert();
            updateRouteLine();
            fitMapOnce();
        }

        resolveDestination();

        if (!orderTrackingData.driver_id) {
            showAlert('لم يتم تعيين سائق لهذا الطلب — لن يظهر موقع السائق.');
            return;
        }

        if (typeof firebase === 'undefined') {
            showAlert('تعذر تحميل خدمة تتبع السائق.');
            return;
        }

        var firebaseConfig = {
            apiKey: "AIzaSyASV6ryM8d7tfsgxEULmT9j3GIqEM0O7rY",
            authDomain: "madarexpress.firebaseapp.com",
            databaseURL: "https://madarexpress.firebaseio.com",
            projectId: "madarexpress",
            storageBucket: "madarexpress.appspot.com",
            messagingSenderId: "306328789253",
            appId: "1:306328789253:web:c874611b2b87e13bedd76c",
            measurementId: "G-S8PYJ0VE2T"
        };

        if (!firebase.apps.length) {
            firebase.initializeApp(firebaseConfig);
            firebase.analytics();
        }

        var db = firebase.firestore();
        var driverFound = false;

        db.collection('drivers').onSnapshot(function (querySnapshot) {
            var matched = false;

            querySnapshot.forEach(function (doc) {
                var data = doc.data() || {};
                if (parseInt(data.id, 10) !== driverId) {
                    return;
                }

                matched = true;

                if (data.locations !== undefined && data.locations[0] !== undefined) {
                    driverFound = true;
                    updateDriverMarker(data, data.locations[0]);
                } else {
                    showAlert('لا توجد إحداثيات حالية للسائق.');
                }
            });

            if (!matched && !driverFound) {
                showAlert('لم يتم العثور على موقع السائق في Firestore.');
            }
        }, function () {
            showAlert('حدث خطأ أثناء الاتصال بخدمة تتبع السائق.');
        });
    })();
</script>
@endsection
@endif
