@extends('admin.reports.pdf.master')
@section('content')
<div class="col-md-6">
    <div class="col-md-12 text-center" style="">
        <h3>  بيانات السائق </h3>
      </div>
    <table class="table table-striped" style="  border: 1px solid gray;">
        <thead>

        <tbody>
            <tr style="  border: 1px solid gray;">
                <th scope="row" style="  border: 1px solid gray; color:#000;">اسم السائق</th>
                <td style="  border: 1px solid gray;">{{$driver->first_name}}</td>

            </tr>

            <tr>
                <th scope="row" style="  border: 1px solid gray;  color:#000;"> اسم العائله  </th>
                <td style="  border: 1px solid gray;">{{$driver->last_name}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> رقم الجوال</th>
                <td style="  border: 1px solid gray;">{{$driver->phone}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;"> رقم الهويه</th>
                <td style="  border: 1px solid gray;">{{$driver->identical_number}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">  البريد الالكتروني</th>
                <td style="  border: 1px solid gray;">{{$driver->email}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">   الجنسيه</th>
                <td style="  border: 1px solid gray;">{{$driver->nationality}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">   رقم الرخصه</th>
                <td style="  border: 1px solid gray;">{{$driver->license_number}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">   تاريخ انتهاء الرخصه</th>
                <td style="  border: 1px solid gray;">{{$driver->license_date_expiration}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">     اسم السياره</th>
                <td style="  border: 1px solid gray;">{{$driver->Car->name ?? ''}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">     انتهاء الهويه </th>
                <td style="  border: 1px solid gray;">{{$driver->identity_expiration_date}}</td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">     صوره الهويه </th>
                <td style="  border: 1px solid gray;">
                <a href="{{getImage($driver->identity_image)}}" target="_blank" rel="noopener noreferrer">عرض</a>
                </td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">     صوره الرخصه </th>
                {{-- <td style="  border: 1px solid gray;">{{$driver->license_image}}</td> --}}
                <td style="  border: 1px solid gray;">
                    <a href="{{getImage($driver->license_image)}}" target="_blank" rel="noopener noreferrer">عرض</a>
                </td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">     صوره الاستماره </th>
                {{-- <td style="  border: 1px solid gray;">{{$driver->form_image}}</td> --}}
                <td style="  border: 1px solid gray;">
                    <a href="{{getImage($driver->form_image)}}" target="_blank" rel="noopener noreferrer">عرض</a>
                </td>

            </tr>
            <tr>
                <th scope="row" style="  border: 1px solid gray; color:#000;">      المدينه </th>
                <td style="  border: 1px solid gray;">
                    @foreach ($driver->DriverCity()->get() as $item)
                        {{$item->City->name ?? ''}}
                    @endforeach
                </td>

            </tr>
        </tbody>
    </table>
</div>

<div class="col-md-6">
    <div class="col-md-12 text-center" style="">
        <h3> طلبات السائق  </h3>
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
                <th> اسم المتجر </th>
                <th> رقم تليفون المتجر </th>
                <th> اسم المستلم </th>
                <th> رقم الجوال</th>
                <th> المدينه </th>
                <th> العنوان بالتفصيل </th>
                <th> عدد المنتجات </th>
                <th> السعر</th>
                <th> طريقه الدفع </th>
                <th> اسم السائق </th>
                <th> السياره </th>
                 <th>   ملحوظات </th>
                <th> الحالة </th>
                <th> رقم المرجع </th>
                <th> رقم التسلسل </th>
                <th> تاريخ الانشاء</th>


            </tr>
        </thead>

        <tbody>
            @foreach($driver->Order()->get() as $item)

            <tr>
                <td>
                    {{$i++}}
                </td>
                <td>{{$item->Company->name ?? ''}} </td>
                <td>{{$item->Company->phone ?? ''}} </td>
                <td>{{$item->recipent_name}} </td>
                <td>{{$item->phone}} </td>
                <td> {{ $item->City->name ?? '' }}</td>
                <td>{{$item->adress_details}} </td>
                <td>{{$item->packages_number}} </td>
                <td>{{$item->price}}</td>
                <td>{{$item->PaymentMethod->name ?? '' }}</td>
                <td>{{$item->Driver->first_name ?? ''  }}</td>
                <td>{{$item->Car->name ?? ''  }}</td>
                 <td>{{$item->notes}}</td>
                <td>
                    {{trans('words.'.$item->status)}}
                </td>
                <td>{{$item->refrence_no}}</td>
                <td>{{$item->serial}}</td>
                <td>{{$item->created_at}}</td>



            </tr>
            @endforeach




        </tbody>
    </table>
</div>

@endsection
