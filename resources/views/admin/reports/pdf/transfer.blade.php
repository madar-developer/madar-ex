@extends('admin.reports.pdf.master')
@section('content')
<br>
<br>
<br>
<div class="col-md-6">
    <div class="col-md-12 text-center" style="">
        <h3>  التحصيل </h3>
      </div>
    <table class="table table-striped" style="  border: 1px solid gray;">
        <thead>

        <tbody>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> رقم الحوالة</th>
                <td style="  border: 1px solid gray;">
                    @php
                    $len = strlen($transfer->id);
                    $full = 15;
                    $rem = 0;
                    if ($full > $len) {
                        $rem = $full - $len;
                    }
                @endphp
                {{$transfer->id}}
                @for ($i = 0; $i < $rem; $i++)
                    0
                @endfor
                </td>

            </tr>
            <tr style="  border: 1px solid gray;">
                <th scope="row" style="  border: 1px solid gray; color:#000;">اسم المتجر</th>
                <td style="  border: 1px solid gray;">{{$transfer->Company->name ?? ''}}</td>

            </tr>

            <tr>
                <th scope="row" style="  border: 1px solid gray;  color:#000;"> رقم تليفون المتجر</th>
                <td style="  border: 1px solid gray;">{{$transfer->Company->phone ?? ''}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> عدد الطلبات</th>
                <td style="  border: 1px solid gray;">{{$invoices->count()}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> الاجمالي</th>
                <td style="  border: 1px solid gray;">{{$transfer->total_price}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> مستحق الشركة</th>
                <td style="  border: 1px solid gray;">{{$transfer->company_price ??''}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">  مستحق مدار اكسبريس</th>
                <td style="  border: 1px solid gray;">{{$transfer->madar_price}}</td>

            </tr>
        </tbody>
    </table>
</div>



<div class="col-md-6">
    <div class="col-md-12 text-center" style="">
        <h3>  الفواتير  </h3>
      </div>
      <table class="table table-striped table-bordered">
            <thead>
                @php
                $i = 1;
                @endphp
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        رقم الطلب
                    </th>
                    <th>  اسم العميل </th>
                    <th>  رقم تليفون العميل </th>
                    <th> المدينة </th>
                    <th> حالة الطلب</th>
                    <th>  المبلغ الاجمالي </th>
                    <th>  المستحق للشركة </th>
                    <th> قيمه التوصيل </th>


                </tr>
            </thead>

            <tbody>
                @foreach($invoices as $item)

                <tr>
                    <td>

                        {{$i++}} </td>
                        <td>{{$item->Order->id }} </td>
                        <td>{{$item->Order->recipent_name ?? ''}} </td>
                        <td>{{$item->Order->phone ?? ''}} </td>
                        <td>{{$item->Order->City->name ?? ''}} </td>
                        <td>{{__('words.'.$item->Order->status) }}</td>
                    <td>{{$item->total_price}}  </td>
                    <td>{{$item->company_price}}  </td>
                    <td>{{$item->madar_price}}  </td>

                </tr>
                @endforeach
                    <tr>
                        <td colspan="6">
                            الاجمالي
                        </td>
                        <td>{{$invoices->sum('total_price')}}  </td>
                        <td>{{$invoices->sum('company_price')}}  </td>
                        <td>{{$invoices->sum('madar_price')}}  </td>

                    </tr>





            </tbody>
        </table>
</div>
@endsection
