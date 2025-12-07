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
                <th scope="row" style="  border: 1px solid gray; color:#000;"> التاريخ</th>
                <td style="  border: 1px solid gray;">{{$invoice->created_at->todatestring()}}</td>

            </tr>

            <tr>
                <th scope="row" style="  border: 1px solid gray;  color:#000;">   المبلغ الاجمالى</th>
                <td style="  border: 1px solid gray;">{{$invoice->total_price}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">  المستحق للشركه / المتجر</th>
                <td style="  border: 1px solid gray;">{{$invoice->company_price}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">  قيمه التوصيل</th>
                <td style="  border: 1px solid gray;">{{$invoice->madar_price}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> الطلب </th>
                <td style="  border: 1px solid gray;">{{$invoice->Order->serial}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">  الحاله </th>
                <td style="  border: 1px solid gray;">{{$invoice->active == '0'? 'لم يتم' : 'تم التحصيل '}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">   رقم الحواله </th>
                <td style="  border: 1px solid gray;">{{$invoice->Transfer->id ?? ''}}</td>

            </tr>
        </tbody>
    </table>
</div>

@endsection