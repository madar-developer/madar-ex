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
        background: #ff9800;
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
        background: #e0e0e0;
        min-width: 8px;
        margin-top: 10px;
    }
    .ot-step-line.is-done {
        background: #e53935;
    }
    .ot-dot {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #e0e0e0;
        border: 3px solid #e0e0e0;
        flex-shrink: 0;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #fff;
    }
    .ot-dot.is-done {
        background: #e53935;
        border-color: #e53935;
    }
    .ot-dot.is-current {
        background: #fff;
        border-color: #e53935;
        box-shadow: 0 0 0 6px rgba(229, 57, 53, 0.25);
    }
    .ot-dot.is-delivered {
        background: #43a047;
        border-color: #43a047;
    }
    .ot-dot.is-returned {
        background: #fb8c00;
        border-color: #fb8c00;
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
    .order-log-table .ot-loc-en {
        direction: ltr;
        text-align: left;
        font-size: 13px;
        color: #444;
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
        <div class="col-md-12 qrcode" >

            @php
            echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->id.'', 'C39+') . '" alt="barcode"   />';
        @endphp
        </div>
        <div class="col-md-12">
            <!-- new order stepper step + logs -->
            @php
                $st = $order->status;
                $stepLabels = ['انشاء الشحنة', 'استلام الشحنة', 'مغادرة المنشأ', 'في الطريق', 'الوصول إلى الوجهة', 'الخروج للتوصيل', 'تم التوصيل'];
                if ($st === 'returned') {
                    $stepLabels[6] = 'تم الإرجاع';
                }
                $currentStepIndex = match ($st) {
                    'new' => 0,
                    'not_received' => 1,
                    'init' => 2,
                    'at_madar' => 3,
                    'at_office', 'reschedule', 'deliver_failed' => 5,
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

            <div class="order-track-card order-log-panel">
                <span class="ot-badge">
                    @if(($order->order_type ?? '') === 'outside' || ($order->order_type ?? '') === 'external')
                        شحنة دولية
                    @else
                        شحنة محلية
                    @endif
                </span>

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

                <div class="order-track-od">
                    <div class="ot-orig">
                        <div class="ot-od-label">المنشأ</div>
                        <div class="ot-od-val">المملكة العربية السعودية @if($originCity) / {{ $originCity }} @endif</div>
                    </div>
                    <div class="ot-dest">
                        <div class="ot-od-label">الوجهة</div>
                        <div class="ot-od-val">المملكة العربية السعودية @if($destCity)<br><span class="ot-loc-en" style="display:inline-block;margin-top:4px;">{{ $destCity }}</span>@endif</div>
                    </div>
                </div>

                @if($st === 'cancelled')
                    <div class="alert alert-warning m-b-20" style="text-align:right;">تم إلغاء هذا الطلب.</div>
                @endif

                <div class="ot-stepper-wrap">
                    <div class="ot-stepper">
                        @foreach($stepLabels as $i => $label)
                            <div class="ot-step">
                                <div class="ot-step-node">
                                    <div class="ot-dot
                                        @if($allStepsComplete && $st === 'delivered' && $i === 6) is-delivered
                                        @elseif($allStepsComplete && $st === 'returned' && $i === 6) is-returned
                                        @elseif($allStepsComplete && $i < 6) is-done
                                        @elseif(!$allStepsComplete && $i < $currentStepIndex) is-done
                                        @elseif(!$allStepsComplete && $i === $currentStepIndex) is-current
                                        @endif
                                    ">
                                        @if($allStepsComplete && $st === 'delivered' && $i === 6)
                                            <i class="fa fa-check"></i>
                                        @elseif($allStepsComplete && $st === 'returned' && $i === 6)
                                            <i class="fa fa-undo"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="ot-step-label">{{ $label }}</div>
                            </div>
                            @if($i < 6)
                                @php
                                    if ($allStepsComplete) {
                                        $segDone = true;
                                    } else {
                                        $segDone = ($i < $currentStepIndex);
                                    }
                                @endphp
                                <div class="ot-step-line {{ $segDone ? 'is-done' : '' }}"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

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
                                @endphp
                                <tr>
                                    <td class="ot-tl-cell">
                                        <span class="ot-tl-rail"></span>
                                        <span class="ot-tl-dot"><i class="fa fa-check"></i></span>
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
