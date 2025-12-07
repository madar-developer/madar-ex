@extends('admin.reports.pdf.master')
@section('content')
<br>
<br>
<br>
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
                <th scope="row" style="  border: 1px solid gray; color:#000;"> اسم المستلم</th>
                <td style="  border: 1px solid gray;">{{$order->recipent_name}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> رقم الجوال</th>
                <td style="  border: 1px solid gray;">{{$order->phone}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> المدينه </th>
                <td style="  border: 1px solid gray;">{{$order->City->name ?? ''}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> العنوان بالتفصيل </th>
                <td style="  border: 1px solid gray;">{{$order->adress_details}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">  عدد المنتجات </th>
                <td style="  border: 1px solid gray;">{{$order->packages_number}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">   السعر </th>
                <td style="  border: 1px solid gray;">{{$order->price}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">   طريقع الدفع </th>
                <td style="  border: 1px solid gray;">{{$order->PaymentMethod->name ?? ''}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">    اسم السائق </th>
                <td style="  border: 1px solid gray;">{{$order->Driver->name ?? ''}}<td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">     الحاله </th>
                <td style="  border: 1px solid gray;">{{$order->status}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">     رقم المرجع </th>
                <td style="  border: 1px solid gray;">{{$order->refrence_no}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">     رقم التسلسل </th>
                <td style="  border: 1px solid gray;">{{$order->serial_no}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">      تاريخ الانشاء </th>
                <td style="  border: 1px solid gray;">{{$order->created_at}}</td>

            </tr>
        </tbody>
    </table>
</div>

@endsection
