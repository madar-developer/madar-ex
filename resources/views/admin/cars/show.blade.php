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

</style>
@endsection
@section('header')
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12 big-nav">

        <div class="panel panel-color card-box-v2 panel-tabs panel-success m-t-10">
            <div class="panel-heading panel-heading-custom">
                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#navpills-1" data-toggle="tab" aria-expanded="true">تفاصيل السيارة
                        </a>
                    </li>
                    <li class="">
                        <a href="#navpills-2" data-toggle="tab" aria-expanded="false">الصيانة</a>
                    </li>





                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div id="navpills-1" class="tab-pane  fade in active">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="item-name">{{$car->name}}</div>
                                <div class="img-cont">
                                    @if ($car->form_image)

                                    <div class="img-wr">
                                        <img src="{{getImage($car->form_image)}}" alt="">
                                    </div>
                                    @endif
                                    {{-- <div class="img-wr">
                                        <img src="c2.jpg" alt="">
                                    </div> --}}
                                </div>
                                <div class="detals-cont">
                                    <div class="item">
                                        <span class="lbl"> رقم الهيكل :</span>
                                        <span class="val"> {{$car->structure_no}}</span>
                                    </div>
                                    <div class="item">
                                        <span class="lbl"> المدينة التى تعمل بها :</span>
                                        <span class="val"> {{$car->work_city}}</span>
                                    </div>
                                    <div class="item">
                                        <span class="lbl"> اللون :</span>
                                        <span class="val"> {{$car->color}}</span>
                                    </div>
                                    <div class="item">
                                        <span class="lbl"> سنة الصنع :</span>
                                        <span class="val"> {{$car->manufacturing_year}}</span>
                                    </div>
                                    <div class="item">
                                        <span class="lbl"> رقم اللوحه :</span>
                                        <span class="val"> {{$car->plate_num ?? ''}}</span>
                                    </div>
                                    <div class="item">
                                        <span class="lbl"> تاريخ إنتهاء الاستمارة :</span>
                                        <span class="val"> {{@$car->license_expiration_date}}</span>
                                    </div>
                                    <div class="item">
                                        <span class="lbl"> تاريخ انتهاء الاستماره * (هجري) :</span>
                                        <span class="val"> {{$car->license_expiration_date_hijri}}</span>
                                    </div>
                                    <div class="item">
                                        <span class="lbl"> تاريخ إنتهاء التأمين :</span>
                                        <span class="val"> {{$car->insurance_expiration_date}}</span>
                                    </div>
                                    <div class="item">
                                        <span class="lbl"> تاريخ انتهاء التأمين * (هجري) :</span>
                                        <span class="val"> {{$car->insurance_expiration_date_hijri}}</span>
                                    </div>
                                    <div class="item">
                                        <span class="lbl"> نوع السيارة :</span>
                                        <span class="val"> {{$car->type}}</span>
                                    </div>

                                    <div class="item">
                                        <span class="lbl"> عدد الكيلو مترات :</span>
                                        <span class="val"> {{$car->kms}}</span>
                                    </div>

                                </div>



                            </div>




                        </div>
                    </div>

                    <div id="navpills-2" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="box-tebal">
                                    <div role="tabpanel" class="tab-pane "
                                        style="overflow-x: auto;">
                                        <table class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th> # </th>
                                                    <th> التكلفه </th>
                                                    <th>الشهر</th>
                                                    <th> نوع الصيانة</th>
                                                    <th> ملحوظات </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i=1;
                                                @endphp
                                                @foreach ($car->CarMaintenance()->get() as $item)
                                                <tr >
                                                    <td >{{$i++}}</td>
                                                    <td class="cost" >{{$item->cost}}</td>
                                                    <td >{{$item->month}}</td>
                                                    <td >{{CarMaintenanceTypes($item->type)}}</td>
                                                    <td >{{$item->notes}}</td>

                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if (0)
<div class="card-box">

    <div class="row">

        <div class="col-md-6">
            <div class="col-md-12 text-center" style="">
                <h3> تفاصيل السيارة </h3>
              </div>
            <table class="table table-striped" style="  border: 1px solid gray;">
                <thead>

                <tbody>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;">اسم السيارة</th>
                        <td style="  border: 1px solid gray;">{{$car->name}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> رقم الهيكل</th>
                        <td style="  border: 1px solid gray;">{{$car->structure_no}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> المدينة التى تعمل بها السيارة </th>
                        <td style="  border: 1px solid gray;">{{$car->work_city ?? ''}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> اللون </th>
                        <td style="  border: 1px solid gray;">{{$car->color}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray;  color:#000;"> سنة الصنع </th>
                        <td style="  border: 1px solid gray;">{{$car->manufacturing_year}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;">  صورة الاستمارة </th>
                        <td style="  border: 1px solid gray;">
                            <img src="{{getImage($car->form_image)}}" width="150" height="150" alt="" srcset="">
                        </td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> رقم اللوحه</th>
                        <td style="  border: 1px solid gray;">{{$car->plate_num ?? ''}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> تاريخ إنتهاء الاستمارة  </th>

                        <td style="  border: 1px solid gray;">{{@$car->license_expiration_date}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> تاريخ انتهاء الاستماره  * (هجري) </th>
                        <td style="  border: 1px solid gray;">{{$car->license_expiration_date_hijri}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> تاريخ إنتهاء التأمين  </th>
                        <td style="  border: 1px solid gray;">{{$car->insurance_expiration_date}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;">تاريخ انتهاء التأمين  * (هجري)< </th>
                        <td style="  border: 1px solid gray;">{{$car->insurance_expiration_date_hijri}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> نوع السيارة </th>
                        <td style="  border: 1px solid gray;">{{$car->type}}</td>

                    </tr>
                    <tr>
                        <th scope="row" style="  border: 1px solid gray; color:#000;"> عدد الكيلو مترات </th>
                        <td style="  border: 1px solid gray;">{{$car->kms}}</td>

                    </tr>
                </tbody>
            </table>
        </div>




        <div class="col-md-6">
            <div class="col-md-12 text-center" style="">
                <h3> الصيانة</h3>
              </div>

            <table class="table table-bordered" style=" border: 1px solid gray;">
                <thead>
                    <tr style=" border: 1px solid black;">
                        <th style="text-align:right; border: 1px solid black;">التكلفه</th>
                        <th style="text-align:right; border: 1px solid black;">نوع الصيانة</th>
                        <th style="text-align:right; border: 1px solid black;">الشهر</th>
                        <th style="text-align:right; border: 1px solid black;">ملحوظات</th>
                        {{-- <th style="text-align:right; border: 1px solid black;" >التاريخ</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($car->CarMaintenance()->get() as $item)
                    <tr style=" border: 1px solid black;">
                        <td style=" border: 1px solid black;">{{$item->cost}}</td>
                        <td style=" border: 1px solid black;">{{CarMaintenanceTypes($item->type)}}</td>
                        <td style=" border: 1px solid black;">{{$item->month}}</td>
                        <td style=" border: 1px solid black;">{{$item->notes}}</td>

                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>

@endif
@endsection
