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
            <table class="table table-bordered" style=" border: 1px solid gray;">
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
                        <td style=" border: 1px solid black;">{{$item->details}}</td>
                    </tr>
                    @endforeach

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
    </div>
</div>
@endsection
