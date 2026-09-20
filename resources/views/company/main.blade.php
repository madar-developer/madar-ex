@extends('company.layout.app')
@section('style')

<style>
    .text-muted {
        color: #000 !important;
    }

    .dash-stats-card {
        background: #fff;
        border: 1px solid #e6ecf2;
        border-radius: 6px;
        padding: 10px 12px 6px;
        margin-bottom: 16px;
    }

    .dash-stats-card .dash-stats-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 8px;
        padding-bottom: 6px;
        border-bottom: 1px solid #edf1f5;
    }

    .dash-stats-card .dash-stats-head h4 {
        margin: 0;
        color: #1a2857;
        font-size: 14px;
        font-weight: 700;
    }

    .dash-stats-card .dash-stats-count {
        background: #e8f1fb;
        color: #1a2857;
        border-radius: 12px;
        padding: 1px 8px;
        font-size: 11px;
        font-weight: 700;
    }

    .dash-stats-table-wrap {
        max-height: 280px;
        overflow: auto;
    }

    .dash-stats-table {
        width: 100%;
        margin: 0;
        font-size: 12px;
    }

    .dash-stats-table > thead > tr > th,
    .dash-stats-table > tbody > tr > td {
        padding: 5px 6px !important;
        vertical-align: middle !important;
        white-space: nowrap;
        border-color: #edf1f5 !important;
    }

    .dash-stats-table > thead > tr > th {
        background: #f5f8fb;
        color: #4b5563;
        font-weight: 700;
        font-size: 11px;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .dash-stats-table .name-col {
        white-space: normal;
        font-weight: 700;
        color: #271f22;
        max-width: 140px;
    }

    .dash-stats-table .num {
        text-align: center;
        font-weight: 700;
    }

    .dash-stats-table .num.orders { color: #188ae2; }
    .dash-stats-table .num.processing { color: #f7b84b; }
    .dash-stats-table .num.delivering { color: #3b82f6; }
    .dash-stats-table .num.reschedule { color: #8b5cf6; }
    .dash-stats-table .num.delivered { color: #10c469; }
    .dash-stats-table .num.failed { color: #f1556c; }

    .dash-stats-empty {
        color: #6b7280;
        text-align: center;
        padding: 18px 8px;
        margin: 0;
        font-size: 12px;
    }
</style>
<script src="{{ asset('/adminto/assets/js/modernizr.min.js')}}"></script>
@endsection
@section('content')

<div class="row flex-row"></div>
<div class="row flex-row">





    <div class="col-lg-3 col-md-6">
        <div class="card-box stat" style="background-color: #c2daf4;">


            <h4 class="header-title m-t-0 m-b-30">عدد  الطلبات</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1">
                    <i class="fa fa-archive" aria-hidden="true"></i>
                </div>
                <div class="widget-detail-1">
                    <h2 class="p-t-10 m-b-0">  {{\App\Models\Order::where('company_id', auth('company')->id())->count()}} </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 ">
        <div class="card-box stat" style="background-color: #c2daf4;">


            <h4 class="header-title m-t-0 m-b-30">عدد  الفواتير</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1">
                    <i class="fa fa-files-o" aria-hidden="true"></i>
                </div>
                <div class="widget-detail-1">
                    <h2 class="p-t-10 m-b-0">  {{\App\Models\Invoice::whereHas('Order', function($q){$q->where('company_id', auth('company')->id());})->count()}} </h2>
                </div>
            </div>
        </div>
    </div>
    @foreach (\App\Models\OrderStatus::/*whereIn('key', ['at_office', 'delivered'])->*/get() as $item)

    <div class="col-lg-3 col-md-6">
        <a href="{{url('/company/company-orders?status='.$item->key)}}">
        <div class="card-box" style="background-color: {{$item->color}};">


            <h4 class="header-title m-t-0 m-b-30" style="color: #000;"> الطلبات {{$item->name}}</h4>

            <div class="widget-chart-1">
                <div class="widget-chart-box-1">
                    <img src="{{getImage($item->image)}}" alt="" srcset="">
                </div>
                <div class="widget-detail-1">
                    <h2 class="p-t-10 m-b-0" style="color: #000;">
                        {{\App\Models\Order::where('company_id', auth('company')->id())->where('status','=',$item->key)->count()}} </h2>
                        <p class="text-muted"> {{$item->name}} </p>
                    </div>
                </div>
            </div>
        </a>
        </div>
    @endforeach

    {{-- <div class="col-lg-3 col-md-6">
        <div class="card-box widget-user">
            <div>
                <div class="wid-u-info">
                    <h4 class="m-t-0 m-b-5"><a href="{{route('company.company-finance.pdf')}}">  تحميل تقرير مالي </a></h4>
                </div>
            </div>
        </div>
    </div><!-- end col --> --}}
</div>
<!-- end row -->

<div class="row flex-row">
    <div class="col-lg-6">
        <div class="dash-stats-card">
            <div class="dash-stats-head">
                <h4>السائقون النشطون اليوم</h4>
                <span class="dash-stats-count">{{ ($activeDriversStats ?? collect())->count() }}</span>
            </div>
            <div class="dash-stats-table-wrap">
                @if(($activeDriversStats ?? collect())->isNotEmpty())
                    <table class="table table-striped table-bordered dash-stats-table">
                        <thead>
                            <tr>
                                <th>السائق</th>
                                <th class="num">الطلبات</th>
                                <th class="num">بالمستودع</th>
                                <th class="num">جاري التوصيل</th>
                                <th class="num">جدولة التوصيل</th>
                                <th class="num">تم التسليم</th>
                                <th class="num">تعذر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeDriversStats as $driver)
                                <tr>
                                    <td class="name-col">{{ trim(($driver->first_name ?? '').' '.($driver->last_name ?? '')) ?: ('سائق #'.$driver->id) }}</td>
                                    <td class="num orders">{{ $driver->orders_count }}</td>
                                    <td class="num processing">{{ $driver->processing_count }}</td>
                                    <td class="num delivering">{{ $driver->delivering_count }}</td>
                                    <td class="num reschedule">{{ $driver->reschedule_count }}</td>
                                    <td class="num delivered">{{ $driver->delivered_count }}</td>
                                    <td class="num failed">{{ $driver->failed_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="dash-stats-empty">لا يوجد سائقون نشطون اليوم</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="dash-stats-card">
            <div class="dash-stats-head">
                <h4>المدن النشطة (آخر 30 يومًا)</h4>
                <span class="dash-stats-count">{{ ($citiesStats ?? collect())->count() }}</span>
            </div>
            <div class="dash-stats-table-wrap">
                @if(($citiesStats ?? collect())->isNotEmpty())
                    <table class="table table-striped table-bordered dash-stats-table">
                        <thead>
                            <tr>
                                <th>المدينة</th>
                                <th class="num">الطلبات</th>
                                <th class="num">بالمستودع</th>
                                <th class="num">جاري التوصيل</th>
                                <th class="num">جدولة التسليم</th>
                                <th class="num">تم التسليم</th>
                                <th class="num">تعذر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citiesStats as $city)
                                <tr>
                                    <td class="name-col">{{ $city->name }}</td>
                                    <td class="num orders">{{ $city->orders_count }}</td>
                                    <td class="num processing">{{ $city->processing_count }}</td>
                                    <td class="num delivering">{{ $city->delivering_count }}</td>
                                    <td class="num reschedule">{{ $city->reschedule_count }}</td>
                                    <td class="num delivered">{{ $city->delivered_count }}</td>
                                    <td class="num failed">{{ $city->failed_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="dash-stats-empty">لا توجد مدن نشطة خلال آخر 30 يومًا</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-6">
        <div class="card-box">

            <h4 class="header-title m-t-0"> الطلبات من حيث الحالات </h4>

            <div class="widget-chart text-center">
                <div id="morris-donut-example" style="height: 245px;"></div>
            </div>
        </div>
    </div><!-- end col -->
    @if(0)
    <div class="col-md-6">
        <div class="card-box">
            <h4 class="header-title m-t-0">ربط متجر سلة</h4>
            @if(!empty($sallaToken))
                <p class="text-success m-b-10">الحساب مربوط مع سلة بنجاح.</p>
                <p class="text-muted m-b-10">Merchant ID: <strong>{{ $sallaToken->merchant_id ?? 'N/A' }}</strong></p>
                <p class="text-muted m-b-20">Company ID: <strong>{{ $sallaToken->company_id }}</strong></p>
                <a href="{{ route('company.connect-salla') }}" class="btn btn-default">
                    إعادة الربط
                </a>
            @else
                <p class="text-muted m-b-20">قم بربط حساب الشركة الحالي مع سلة لتفعيل مزامنة الطلبات.</p>
                <a href="{{ route('company.connect-salla') }}" class="btn btn-primary">
                    ربط مع سلة
                </a>
            @endif
        </div>
    </div>
    @endif
</div>





@endsection
@section('script')

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
