@extends('admin.layout.app')

@section('style')
<style>
    .pac-container.pac-logo {
            z-index: 99999;
        }
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
    <div class="col-lg-12">
        <div class="panel panel-color panel-tabs panel-success">
            <div class="panel-heading panel-heading-custom">
                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#navpills-1" data-toggle="tab" aria-expanded="true">البيانات الأساسية</a>
                    </li>
                    <li class="">
                        <a href="#navpills-2" data-toggle="tab" aria-expanded="false">الطلبات</a>
                    </li>
                    <li class="">
                        <a href="#navpills-3" data-toggle="tab" aria-expanded="false">العناوين</a>
                    </li>
                    <li class="">
                        <a href="#navpills-4" data-toggle="tab" aria-expanded="false">وسائل الدفع</a>
                    </li>
                    <li class="">
                        <a href="#navpills-5" data-toggle="tab" aria-expanded="false">المستندات </a>
                    </li>
                    <li class="">
                        <a href="#navpills-6" data-toggle="tab" aria-expanded="false">اسعار التوصيل </a>
                    </li>
                    <li class="">
                        <a href="#navpills-7" data-toggle="tab" aria-expanded="false">الحسابات</a>
                    </li>
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div id="navpills-1" class="tab-pane fade in active">

                        <div class="row">




                            <div class="col-md-6">
                                <div class="col-md-12 text-center" style="">
                                    <h3> تفاصيل المتجر </h3>
                                </div>
                                <table class="table table-striped" style="  border: 1px solid gray; text-align:right;">
                                    <thead>

                                    <tbody>
                                        <tr style="  border: 1px solid gray;">
                                            <th scope="row" style="text-align: right; border: 1px solid gray; color:#000;">اسم المتجر</th>
                                            <td style=" text-align:right; border: 1px solid gray;">{{$company->name}}</td>

                                        </tr>

                                        <tr>
                                            <th scope="row" style="text-align: right; border: 1px solid gray;  color:#000;"> رقم تليفون المتجر</th>
                                            <td style=" text-align:right; border: 1px solid gray;">{{$company->phone}}</td>

                                        </tr>
                                        <tr>
                                            <th scope="row" style="text-align: right; border: 1px solid gray; color:#000;">البريد الالكترونى</th>
                                            <td style=" text-align:right; border: 1px solid gray;">{{$company->email}}</td>

                                        </tr>
                                        <tr>
                                            <th scope="row" style="text-align: right; border: 1px solid gray; color:#000;"> المدينه</th>
                                            <td style=" text-align:right; border: 1px solid gray;">{{$company->City->name}}</td>

                                        </tr>
                                        <tr>
                                            <th scope="row" style="text-align: right; border: 1px solid gray; color:#000;"> العنوان بالتفصيل</th>
                                            <td style=" text-align:right; border: 1px solid gray;">{{$company->adress_details}}</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <div class="col-md-12 text-center" style="">
                                    <h3> تفاصيل الطلبات </h3>
                                </div>
                                <table class="table table-striped" style="  border: 1px solid gray;">
                                    <thead>

                                    <tbody>

                                        @foreach (OrderStatus() as $key => $value)
                                        <tr>
                                            <th scope="row" style="text-align:right;  border: 1px solid gray;  color:#000;">الطلبات {{$value}}</th>
                                            <td style="  border: 1px solid gray;color:#000;">{{$company->Order()->where('status','=',$key)->count()}}</td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div id="navpills-2" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            @php
                                                $i = 1;
                                            @endphp
                                            <tr>

                                                <th>
                                                    #
                                                </th>
                                                {{--  <th>IDs </th>  --}}
                                                <th>  اسم المتجر </th>
                                                <th>   رقم تليفون المتجر </th>
                                                <th>  اسم المستلم </th>
                                                <th>    رقم الجوال</th>
                                                <th>   المدينه	   </th>
                                                <th>   العنوان بالتفصيل	   </th>
                                                <th>    عدد المنتجات	   </th>
                                                <th>  السعر</th>
                                                <th>  طريقه الدفع </th>
                                                {{--  <th>   ملحوظات </th>  --}}
                                                <th>    الحالة </th>
                                                <th>    رقم المرجع </th>
                                                <th>  رقم التسلسل </th>
                                                <th>    تاريخ الانشاء</th>
                                                <th>    العمليات </th>


                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($orders as $item)

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
                                                   <td>
                                                        {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
                                                        => 'PATCH','files'=>true , 'class'=>'form']) !!}
                                                        {!! Form::select("status",OrderStatus($item->status),null,['class'=>"form-control select2", "autocomplete"=> 'off', "onchange" => "$(this).closest('form').submit()"])!!}
                                                        {!!Form::close() !!}
                                                    </td>
                                                <td>{{$item->refrence_no}}</td>
                                                <td>{{$item->serial}}</td>
                                                <td>{{$item->created_at}}</td>


                                                <td class="btns">

                                                        <a href="/dashboard/orders/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> تعديل </a>
                                                        <a href="{{route('orders.destroy',$item)}}" type="button" class="btn btn-danger delete-btn  waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>
                                                        <a href="/dashboard/orders/{{$item->id}}" type="button"
                                                            class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                                class="fa fa-eye"></i> عرض </a>
                                                        <a href="/dashboard/order-bill/{{$item->id}}" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> طباعه بوليصه الشحن </a>

                                                    </td>


                                            </tr>
                                            @endforeach




                                        </tbody>
                                    </table>
                                    {!! $orders->links() !!}

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="navpills-3" class="tab-pane fade">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-4">
                                <button type="button" data-route="{{route('company-addresses.create')}}?company_id={{$company->id}}" class="load-ajax btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">اضافة عنوان</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        @php
                                            $i = 1;
                                        @endphp
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>العنوان</th>
                                            <th>الاسم المستعار</th>
                                            <th>اسم الشارع</th>
                                            <th>رقم المبنى </th>
                                            <th>رقم الشقه </th>
                                            <th>رقم الطابق </th>
                                            <th>اسم الشركه  </th>
                                            <th> العنوان الاساسي </th>
                                            <th>اسم المدينه  </th>
                                            <th>    العمليات </th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($addresses as $item)

                                        <tr>
                                            <td>
                                                {{$i++}}
                                            </td>
                                            <td>{{$item->address}} </td>
                                            <td>{{$item->nick_name}} </td>
                                            <td>{{$item->street_name}} </td>
                                            <td>{{$item->building}} </td>
                                            <td> {{ $item->floor}}</td>
                                            <td>{{$item->flat}} </td>
                                            <td>{{$item->Company->name ?? ''}} </td>
                                            <td>{{($item->main == '1') ? 'نعم': 'لا'}} </td>
                                            <td>{{$item->City->name ?? ''}}</td>
                                            <td class="btns">
                                                <button type="button" data-route="{{route('company-addresses.edit', $item->id)}}?company_id={{$company->id}}" class="load-ajax btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">تعديل</button>

                                                <a href="{{route('company-addresses.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>

                                            </td>


                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {!! $addresses->links() !!}
                            </div>
                        </div>
                    </div>
                    <div id="navpills-4" class="tab-pane fade">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-4">
                                <button type="button" data-route="{{route('company-cache-types.create')}}?company_id={{$company->id}}" class="load-ajax btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">اضافة وسيله دفع</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered text-right">
                                    <thead>
                                        @php
                                            $i = 1;
                                        @endphp
                                        <tr>

                                            <th>
                                                #
                                            </th>
                                            <th>النوع</th>
                                            <th>العنوان</th>
                                            <th>المحتوي</th>
                                            <th>    العمليات </th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($company->CompanyCacheType()->latest()->get() as $item)

                                            <tr>
                                                <td>
                                                    {{$i++}}
                                                </td>
                                                <td>{{$item->AvailableMethod->title ?? ''}} </td>
                                                <td>{{$item->title ?? ''}} </td>
                                                <td>{{$item->description}} </td>
                                                <td class="btns">
                                                    <button type="button" data-route="{{route('company-cache-types.edit', $item->id)}}?company_id={{$company->id}}" class="load-ajax btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">تعديل</button>

                                                    <a href="{{route('company-cache-types.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>

                                                </td>


                                            </tr>
                                        @endforeach




                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="navpills-5" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                {!!Form::open( ['url' => route('companies-files', $company->id) ,'method' => 'Post','files' => true]) !!}
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">  الملف :</label>
                                        <div class=" col-md-6">
                                            {!! Form::file("file",['class'=>"form-control", "autocomplete"=> 'off'])!!}
                                        </div>
                                        <div class=" col-md-3">
                                            <button type="submit" class="btn blue">
                                                <i class="fa fa-check"></i>
                                                حفظ
                                            </button>
                                        </div>
                                    </div>
                                {!!Form::close() !!}
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered text-right">
                                    <thead>
                                        @php
                                            $i = 1;
                                        @endphp
                                        <tr>

                                            <th>
                                                #
                                            </th>
                                            <th>المحتوي</th>
                                            <th>    العمليات </th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($company->Files()->latest()->get() as $item)

                                            <tr>
                                                <td>
                                                    {{$i++}}
                                                </td>
                                                <td>{!! FileHtmlContent($item->name) !!} </td>
                                                <td class="btns">
                                                    <a href="{{route('files.destroy',$item->id)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>

                                                </td>


                                            </tr>
                                        @endforeach




                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="navpills-6" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered text-right">
                                    <thead>
                                        @php
                                            $i = 1;
                                        @endphp
                                        <tr>

                                            <th>
                                                #
                                            </th>
                                            <th>العنوان</th>
                                            <th>السعر</th>
                                            <th> العمليات </th>


                                        </tr>
                                    </thead>

                                    <tbody>

                                {!!Form::open( ['url' => route('companies-pricelist', $company->id) ,'method' => 'Post','files' => true]) !!}
                                        @foreach(CityGroups() as $item)

                                            <tr>
                                                <td>
                                                    {{$i++}}
                                                </td>
                                                <td>{!! $item->name !!} </td>
                                                <td>
                                                <input type="number" class="form-control" name="prices[{{$item->id}}]" value="{{($company->CompanyCityGroup()->where('city_group_id', $item->id)->first() )? $company->CompanyCityGroup()->where('city_group_id', $item->id)->first()->delivery_cost : ''}}" style="width:160px;">
                                                </td>
                                                <td class="btns">
                                                </td>


                                            </tr>
                                        @endforeach
                                        <button class="btn btn-success" type="submit"> حفظ </button>
                                {!!Form::close() !!}




                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="navpills-7" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-box widget-user" style=" background-color: #ccc; ">
                                    <div class="text-center">
                                        <h2 class="text-info" data-plugin="counterup">{{$invoices_company_price}} ريال <sub style="color:red;">{{$invoices_madar_price}} ريال </sub> </h2>
                                        <h5>المستحق للشركه من الفواتر التي لم يتم تحويلها الي حوالة</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-box widget-user" style=" background-color: #ccc; ">
                                    <div class="text-center">
                                        <h2 class="text-info" data-plugin="counterup"> {{$transfers_get->where('active', '0')->sum('company_price')}} ريال <sub style="color:red;">{{$transfers_get->where('active', '0')->sum('madar_price')}} ريال </sub> </h2>
                                        <h5>المستحق للشركه من التحصيلات لم يتم تحويلها للشركه</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card-box widget-user" style=" background-color: #ccc; ">
                                    <div class="text-center">
                                        <h2 class="text-info" data-plugin="counterup"> {{$transfers_get->where('active', '1')->sum('company_price')}} ريال <sub style="color:red;">{{$transfers_get->where('active', '1')->sum('madar_price')}} ريال </sub> </h2>
                                        <h5>المبالغ التي تم ارسالها للشركة</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered text-right">
                                    <thead>
                                        @php
                                            $i = 1;
                                        @endphp
                                        <tr>
                                            <th>
                                            </th>
                                            <th>  التاريخ </th>
                                            <th>  المبلغ الاجمالي </th>
                                            <th>  المستحق للشركة / المتجر </th>
                                            <th> قيمه التوصيل </th>
                                            <th> المدينة</th>
                                            <th> اسم العميل</th>
                                            <th> الطلب</th>
                                            <th> الحاله </th>
                                            <th> رقم الحواله</th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($invoices as $item)

                                        <tr>
                                            <td>

                                                {{$i++}} </td>
                                            <td>{{$item->created_at->todatestring()}} </td>
                                            <td>{{$item->total_price}} ريال </td>
                                            <td>{{$item->company_price}} ريال </td>
                                            <td>{{$item->madar_price}} ريال </td>
                                            <td>{{$item->Order->City->name ?? '' }}</td>
                                            <td>{{$item->Order->recipent_name ?? '' }}</td>
                                            <td>{{$item->Order->serial }}</td>
                                            <td>{{($item->active == '0')? 'لم يتم' : 'تم التحصيل '}}</td>
                                            <td> {{$item->Transfer->id ?? ''}}
                                            </td>
                                        </tr>
                                        @endforeach




                                    </tbody>
                                </table>
                                {!! $invoices->links() !!}

                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>

                                            <th>#</th>
                                            <th> الاجمالي</th>
                                            <th>المستحق للشركة</th>
                                            <th>تكلفة التوصيل </th>
                                            <th>التاريخ من</th>
                                            <th>التاريخ الي</th>
                                            <th>عرض الفواتير</th>

                                            <th>التحصيل</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($transfers as $item)

                                        <tr>
                                              <td>{{$item->id}} </td>
                                            <td> {{$item->total_price}} </td>
                                            <td> {{$item->company_price}} </td>
                                            <td> {{$item->madar_price}} </td>
                                            <td>{{$item->date_from}} </td>
                                            <td>{{$item->date_to}} </td>
                                            <td >
                                                <!-- Button trigger modal -->
                                                <button type="button" data-url="{{url('/dashboard/tranfer-invoices/'.$item->id)}}" class="btn btn-primary btn-lg invoice transfer-info" data-toggle="modal" data-target="#myModal">
                                                  عرض الفواتير
                                                </button>
                                                <a href="{{route('transfers.report', $item->id)}}" class="btn btn-primary btn-xs">
                                                    استخراج تقرير
                                                </a>

                                            </td>
                                            <td>
                                                @if ($item->active)
                                                    تم التحصيل
                                                @else
                                                لم يتم التحصيل
                                                @endif
                                            </td>
                                        </tr>


                                        @endforeach




                                    </tbody>
                                </table>

                                {{ $transfers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end col -->


</div>
<!-- end row -->

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="modal-content">
        <i class="fa fa-snipper"></i>
      </div>
    </div>
  </div>
@endsection
@section('script')
    <script>
        $('body').on('click', '.load-ajax', function(){
            $.get($(this).attr('data-route'))
            .done(function(data){
                $('#modal-content').html(data);
                initialize();
            })
            .fail(function(){});
        });
    </script>

<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyASV6ryM8d7tfsgxEULmT9j3GIqEM0O7rY&language=ar"></script>
<script type="text/javascript">
    var map;
    var marker;
    var autocomplete;
    var geocoder = new google.maps.Geocoder();

    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                updateMarkerAddress(responses[0].formatted_address);
            } else {
                updateMarkerAddress('Cannot determine address at this location.');
            }
        });
    }

    function updateMarkerStatus(str) {
        document.getElementById('markerStatus').innerHTML = str;
    }

    function updateMarkerPosition(latLng) {
        document.getElementById('info').innerHTML = [
            latLng.lat(),
            latLng.lng()
        ].join(', ');
        document.getElementById('lat').value = latLng.lat();
        document.getElementById('lng').value = latLng.lng();
    }

    function updateMarkerAddress(str) {
        document.getElementById('autocomplete').value = str;
    }

    function initialize(lat = 24.7255553, lng = 47.1027146) {
        var latLng = new google.maps.LatLng(24.7255553, 47.1027146);
        map = new google.maps.Map(document.getElementById('mapCanvas'), {
            zoom: 8,
            center: latLng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        marker = new google.maps.Marker({
            position: latLng,
            title: 'Point A',
            map: map,
            draggable: true
        });

        // Update current position info.
        updateMarkerPosition(latLng);
        geocodePosition(latLng);

        // Add dragging event listeners.
        google.maps.event.addListener(marker, 'dragstart', function() {
            updateMarkerAddress('Dragging...');
        });

        google.maps.event.addListener(marker, 'drag', function() {
            updateMarkerStatus('Dragging...');
            updateMarkerPosition(marker.getPosition());
        });

        google.maps.event.addListener(marker, 'dragend', function() {
            updateMarkerStatus('Drag ended');
            geocodePosition(marker.getPosition());
        });

        // Initialize autocomplete.
        var inputField = document.getElementById('autocomplete');
        autocomplete = new google.maps.places.Autocomplete(inputField);
        google.maps.event.addListener(
            autocomplete, 'place_changed',
            function() {
                var place = autocomplete.getPlace();
                if (place.geometry) {
                    var location = place.geometry.location;
                    map.panTo(location);
                    map.setZoom(12);
                    marker.setMap(map);
                    marker.setPosition(location);
                    updateMarkerPosition(marker.getPosition());
                }
            });

        google.maps.event.addListener(map, 'idle', function() {
            autocomplete.setBounds(map.getBounds());
        });
    }
    // Updates autocomplete object.
    function updateOptions() {
        // Set types, if any.
        var desired_types = [];
        var types = document.controls.type;
        for (var i = 1; i < types.length; i++) {
            if (types[i].checked) {
                desired_types = [types[i].value];
                break;
            }
        }
        autocomplete.setTypes(desired_types);

        // Set country, if any.
        var country = document.controls.country.value;
        if (country) {
            autocomplete.setComponentRestrictions({
                'country': country
            });
        } else {
            autocomplete.setComponentRestrictions({});
        }
    }

    // Onload handler to fire off the app.
    // google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endsection
