@extends('admin.layout.app')
@section('style')
<style>
    .morris-hover.morris-default-style {
    position: absolute;
}
</style>
@endsection
@section('header')
<!-- Page title -->

@endsection
@section('content')

<div class="row">

    <div class="col-sm-12">
        <div class="card-box">
            <form action="" method="get">
                @csrf
                <div class="row">
                    <div class="col-md-12 part-top">
                        <div class="row">

                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="row">



                            <!--<div class="col-lg-2">-->
                            <!--    <div class="form-horizontal">-->
                            <!--        <div class="form-group">-->
                            <!--            <div class="col-md-12">-->
                            <!--                <input type="text" name="driver"-->
                            <!--                    value="{{(array_key_exists('driver', $search))? $search['driver'] : ''}}"-->
                            <!--                    class="form-control" placeholder="كلمات البحث ">-->
                            <!--            </div>-->
                            <!--        </div>-->

                            <!--    </div>-->
                            <!--</div>-->
                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="orders_from"
                                                value="{{(array_key_exists('orders_from', $search))? $search['orders_from'] : ''}}"
                                                class="form-control datepicker" placeholder="تاريخ الطلبات من">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="orders_to"
                                                value="{{(array_key_exists('orders_to', $search))? $search['orders_to'] : ''}}"
                                                class="form-control datepicker" placeholder="تاريخ الطلبات الي">
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-horizontal m-b-15">
                                    <button type="button" onclick="$(this).closest('form').find('#excel').remove(); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-search"></i> بحث</button>
                                </div>
                            </div>
                            

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <a href="{{url('/dashboard/drivers-charts')}}"
                                        class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                                            class="fa fa-trash"></i> مسح خيارات البحث</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">

                    <div class="col-sm-12">
                            <div class="card-box text-left">
                                    <div class="row">
                                        

                                        <div class="col-lg-12">
                                            
        <div class="card-box">
            <!--<h4 class="header-title m-t-0">... </h4>-->
            <div id="morris-bar-example2" style="height: 280px;"></div>
        </div>
                                        </div>
                                    </div>
                                </div>

                    </div><!-- end col -->
                </div>
                <!-- end row -->
                <div class="col-sm-12">

                    </div>

                <div id="modal-delete" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width:55%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="custom-width-modalLabel">هل تريد الحذف </h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">إلغاء الامر</button>
                                <button type="button" class="btn btn-primary buunton-notofication waves-effect waves-light" data-type="success"  data-message="تم الحذف">حذف</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>

                <div id="info-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width:55%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="custom-width-modalLabel">بيانات العميل </h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12" id="client-info-box">
                                        {{--  --}}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>


@endsection
@section('script')


<!--Morris Chart-->
<script src="/adminto/assets/plugins/morris/morris.min.js"></script>
<script src="/adminto/assets/plugins/raphael/raphael-min.js"></script>

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
              Dashboard1.prototype.createBarChart = function(element, data, xkey, ykeys, labels, barColors) {
                Morris.Bar({
                  element: element,
                  data: data,
                  xkey: xkey,
                  ykeys: ykeys,
                  labels: labels,
                  hideHover: 'auto',
                  resize: true,
                  gridLineColor: '#eeeeee',
                  barSizeRatio: 0.6, // a bit wider for readability
                  barColors: barColors
                });
              },
        
            Dashboard1.prototype.init = function() {
        
                //creating bar chart
                var $barData2  = JSON.parse('{!! $rows_chart !!}');
                this.createBarChart(
                  'morris-bar-example2',
                  $barData2,
                  'y',
                  ['total', 'delivered'],
                  ['كل الطلبات', 'الطلبات المسلَّمة'],
                  ['#188ae2', '#10c469'] // blue / green
                );
        
                //create line chart
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