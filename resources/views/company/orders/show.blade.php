@extends('company.layout.app')
@php
    $order = $company_order;
@endphp
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
        width: 30px;
        height: 30px;
        background: #2ea334;
        border: 5px solid #92e49c;
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
    <li>
        <div class="add-btn">
            <a href="/company/order-bill/{{$order->id}}" target="_blank"
                class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-print"></i>
            </a>
        </div>
    </li>
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
                        <td style="  border: 1px solid gray;">{{$order->status}}</td>

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
            @include('company.orders.partials.tracking')
        </div>
    </div>
</div>
@endsection
@section('script')
    @include('company.orders.partials.tracking-scripts')
@endsection
