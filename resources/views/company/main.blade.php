@extends('company.layout.app')
@section('style')

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
<div class="row">

    <div class="col-md-6">
        <div class="card-box">

            <h4 class="header-title m-t-0"> الطلبات من حيث الحالات </h4>

            <div class="widget-chart text-center">
                <div id="morris-donut-example" style="height: 245px;"></div>
            </div>
        </div>
    </div><!-- end col -->
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
