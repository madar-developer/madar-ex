@extends('admin.layout.app')
@section('style')

<style>
    .text-muted {
        color: #000 !important;
    }

</style>
<script src="{{ asset('/adminto/assets/js/modernizr.min.js')}}"></script>
{{-- .h4{
    color:#000;
} --}}
@endsection
@section('content')

<div class="row">

@php
    if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
@endphp



    <div class="col-lg-3 col-md-6">
        <div class="card-box" style="background-color: #c2daf4;">


            <h4 class="header-title m-t-0 m-b-30" style="color: #000;">عدد المتاجر والشركات</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1">
                    <i class="fa fa-building-o" aria-hidden="true"></i>
                </div>
                <div class="widget-detail-1">
                    <h2 class="p-t-10 m-b-0" style="color: #000;"> {{\App\Models\Company::whereHas('BranchData', function($q) use( $branch_id ){
                        $q->where('admin_id', $branch_id);
                    })->count()}} </h2>
                    <p class="text-muted" style="color: #000; !important">إجمالي عدد الشركات</p>
                </div>
            </div>
        </div>
    </div>



    <div class="col-lg-3 col-md-6">

        <div class="card-box" style="background-color: #c2daf4;">


            <h4 class="header-title m-t-0 m-b-30" style="color: #000;">عدد الطلبات</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1">
                    <i class="fa fa-building-o" aria-hidden="true"></i>
                </div>
                <div class="widget-detail-1">
                    <h2 class="p-t-10 m-b-0" style="color: #000;"> {{\App\Models\Order::whereHas('BranchData', function($q) use( $branch_id ){
                        $q->where('admin_id', $branch_id);
                    })->count()}}</h2>
                    <p class="text-muted"> عدد الطلبات إجمالي </p>
                </div>
            </div>
        </div>
    </div>



    <div class="col-lg-3 col-md-6">
        <div class="card-box" style="background-color: #c2daf4;">


            <h4 class="header-title m-t-0 m-b-30" style="color: #000;">عدد الحولات</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1">
                    <i class="fa fa-building-o" aria-hidden="true"></i>
                </div>
                <div class="widget-detail-1">
                    <h2 class="p-t-10 m-b-0" style="color: #000;"> {{\App\Models\Transfer::whereHas('BranchData', function($q) use( $branch_id ){
                        $q->where('admin_id', $branch_id);
                    })->count()}} </h2>
                    <p class="text-muted"> عدد الحولات إجمالي </p>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>






</div>
<!-- end row -->
<div class="row">
    @foreach (\App\Models\OrderStatus::get() as $item)

    <div class="col-lg-3 col-md-6">
        <div class="card-box" style="background-color: {{$item->color}};">


            <h4 class="header-title m-t-0 m-b-30" style="color: #000;"> الطلبات {{$item->name}}</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1">
                    <i class="fa fa-building-o" aria-hidden="true"></i>
                </div>
                <div class="widget-detail-1">
                    <h2 class="p-t-10 m-b-0" style="color: #000;">
                        {{\App\Models\Order::whereHas('BranchData', function($q) use( $branch_id ){
                            $q->where('admin_id', $branch_id);
                        })->where('status','=',$item->key)->count()}} </h2>
                    <a href="{{url('/dashboard/orders?status='.$item->key)}}">
                        <p class="text-muted"> {{$item->name}} </p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach


</div>


{{-- table of orders --}}
<div class="row">

    <div class="col-sm-12">
        <div class="card-box text-left">
            <div class="row">

                <div class="col-lg-12">
                    <div class="box-tebal">

                        <div role="tabpanel" class="tab-pane " style="overflow-x: scroll;">
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
                                        <th> اسم المتجر </th>
                                        <th> رقم تليفون المتجر </th>
                                        <th> اسم المستلم </th>
                                        <th> رقم الجوال</th>
                                        <th> المدينه </th>
                                        <th> العنوان بالتفصيل </th>
                                        <th> عدد المنتجات </th>
                                        <th> السعر</th>
                                        <th> طريقه الدفع </th>
                                        {{--  <th>   ملحوظات </th>  --}}
                                        <th> الحالة </th>
                                        <th> رقم المرجع </th>
                                        <th> رقم التسلسل </th>
                                        <th> السائق </th>
                                        <th> السياره </th>
                                        <th> تاريخ الانشاء</th>
                                        <th> العمليات </th>


                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($orders as $item)

                                    <tr>
                                        <td>
                                            {{$i++}}
                                        </td>
                                        {{--  {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
                                                    => 'PATCH','files'=>true]) !!}
                                                    {!! Form::select("status",OrderStatus($item->status),null,['class'=>"form-control select2", "autocomplete"=> 'off', "onchange" => "$(this).closest('form').submit()"])!!}
                                                    {!!Form::close() !!}  --}}
                                        <td>{{$item->Company->name ?? ''}} </td>
                                        <td>{{$item->Company->phone ?? ''}} </td>
                                        <td>{{$item->recipent_name}} </td>
                                        <td>{{$item->phone}} </td>
                                        <td> {{ $item->City->name ?? '' }}</td>
                                        <td>{{$item->adress_details}} </td>
                                        <td>{{$item->packages_number}} </td>
                                        <td>{{$item->price}}</td>
                                        <td>{{$item->PaymentMethod->name ?? '' }}</td>
                                        {{--  <td>{{$item->notes}}</td> --}}
                                        <td>
                                            {{OrderStatus()[$item->status]}}
                                        </td>
                                        <td>{{$item->refrence_no}}</td>
                                        <td>{{$item->serial}}</td>
                                        <td>{{$item->Driver->first_name ?? ''}} {{$item->Driver->last_name ?? ''}}</td>
                                        <td>{{$item->Driver->Car->name ?? ''}}</td>
                                        <td>{{$item->created_at}}</td>


                                        <td class="btns">

                                            <a href="/dashboard/orders/{{$item->id}}/edit" type="button"
                                                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-pencil"></i> تعديل </a>
                                            <a href="/dashboard/orders/{{$item->id}}" type="button"
                                                class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-eye"></i> عرض </a>
                                            <a href="/dashboard/order-bill/{{$item->id}}" type="button"
                                                class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i
                                                    class="fa fa-times"></i> طباعه بوليصه الشحن </a>

                                        </td>


                                    </tr>
                                    @endforeach




                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- end col -->
</div>


{{-- end of table --}}

<div class="row">

    <div class="col-sm-12">
        <div class="card-box text-left">
            <div class="row">

                <div class="col-lg-12">
                    <div class="box-tebal">
                        <div id="map" style="width: 100%; height: 600px;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection

@section('script')
<script>
    var resizefunc = [];
</script>

<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="/adminto/assets/js/jquery.min.js"></script>
<script src="/adminto/assets/js/bootstrap-rtl.min.js"></script>
<script src="/adminto/assets/js/detect.js"></script>
<script src="/adminto/assets/js/fastclick.js"></script>
<script src="/adminto/assets/js/jquery.blockUI.js"></script>
<script src="/adminto/assets/js/waves.js"></script>
<script src="/adminto/assets/js/jquery.nicescroll.js"></script>
<script src="/adminto/assets/js/jquery.slimscroll.js"></script>
<script src="/adminto/assets/js/jquery.scrollTo.min.js"></script>

<!-- KNOB JS -->
<!--[if IE]>
    <script type="text/javascript" src="/adminto/assets/plugins/jquery-knob/excanvas.js"></script>
    <![endif]-->
<script src="/adminto/assets/plugins/jquery-knob/jquery.knob.js"></script>

<!--Morris Chart-->
<script src="/adminto/assets/plugins/morris/morris.min.js"></script>
<script src="/adminto/assets/plugins/raphael/raphael-min.js"></script>
<!-- Toastr js -->
<script src="{{ asset('/adminto/assets/plugins/toastr/toastr.min.js')}}"></script>

<!-- adminto/init -->
<script src="/adminto/assets/pages/jquery.adminto/js"></script>

<!-- App js -->
<script src="/adminto/assets/js/jquery.core.js"></script>
<script src="/adminto/assets/js/jquery.app.js"></script>

@if(session()->has('warning'))
<script type="text/javascript">
    toastr["error"]("{{session()->get('warning')}}")

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "500",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>
@endif
<script type="text/javascript"
    src="https://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyASV6ryM8d7tfsgxEULmT9j3GIqEM0O7rY&language=ar">
</script>
<script type="text/javascript">
    var locations = [
        ['Driver 1', -33.890542, 151.274856, 4],
        ['Driver 2 , orders: 25', -33.923036, 151.259052, 5],
        ['Driver 3 , orders: 5', -34.028249, 151.157507, 3],
        ['Driver 4 , orders: 20 <br> done:2', -33.80010128657071, 151.28747820854187, 2],
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: new google.maps.LatLng(-33.92, 151.25),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
        });

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }

</script>

    <script>

/**
* Theme: Adminto Admin Template
* Author: Coderthemes
* Dashboard
*/

!function($) {
    "use strict";

    var Dashboard1 = function() {
    	this.$realData = []
    };

    //creates Bar chart
    Dashboard1.prototype.createBarChart  = function(element, data, xkey, ykeys, labels, lineColors) {
        Morris.Bar({
            element: element,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            hideHover: 'auto',
            resize: true, //defaulted to true
            gridLineColor: '#eeeeee',
            barSizeRatio: 0.2,
            barColors: lineColors
        });
    },

    //creates line chart
    Dashboard1.prototype.createLineChart = function(element, data, xkey, ykeys, labels, opacity, Pfillcolor, Pstockcolor, lineColors) {
        Morris.Line({
          element: element,
          data: data,
          xkey: xkey,
          ykeys: ykeys,
          labels: labels,
          fillOpacity: opacity,
          pointFillColors: Pfillcolor,
          pointStrokeColors: Pstockcolor,
          behaveLikeLine: true,
          gridLineColor: '#eef0f2',
          hideHover: 'auto',
          resize: true, //defaulted to true
          pointSize: 0,
          lineColors: lineColors
        });
    },

    //creates Donut chart
    Dashboard1.prototype.createDonutChart = function(element, data, colors) {
        Morris.Donut({
            element: element,
            data: data,
            resize: true, //defaulted to true
            colors: colors
        });
    },


    Dashboard1.prototype.init = function() {

        //creating bar chart
        var $barData  = JSON.parse('{!! $companies_chart !!}');
        this.createBarChart('morris-bar-example', $barData, 'y', ['a'], ['العدد'], ['#188ae2']);
        //creating bar chart
        var $barData2  = JSON.parse('{!! $orders_chart !!}');
        this.createBarChart('morris-bar-example2', $barData2, 'y', ['a'], ['عدد الطلبات'], ['#188ae2']);

        //create line chart
        var $data  = JSON.parse('{!! $payments_chart !!}');
        this.createLineChart('morris-line-example', $data, 'y', ['a','b'], ['العام الحالي','العام الماضي'],['0.9'],['#ffffff'],['#999999'], ['#10c469','#188ae2']);

        //create line chart
        //creating donut chart
        var $donutData = [
                {label: "Download Sales", value: 12},
                {label: "In-Store Sales", value: 30},
                {label: "Mail-Order Sales", value: 20}
            ];

        var $donutData  = JSON.parse('{!! $order_statuses_chart !!}');
        var $colorData  = JSON.parse('{!! $order_statuses_colors !!}');
        this.createDonutChart('morris-donut-example', $donutData, $colorData);
    },
    //init
    $.Dashboard1 = new Dashboard1, $.Dashboard1.Constructor = Dashboard1
}(window.jQuery),

//initializing
function($) {
    "use strict";
    $.Dashboard1.init();
}(window.jQuery);
</script>
@endsection
