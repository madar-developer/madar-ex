<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <!-- App Favicon -->
    <link rel="shortcut icon" href="{{asset('/assets/images/madar-logo-dark.png')}}">

    <!-- App title -->
    <title> لوحه تحكم مدار | {{ isset($title)? $title : '' }}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@300;400;500;600;700&family=Tajawal:wght@400;500;700;800&display=swap"
        rel="stylesheet">
    <!-- DataTables -->

    <link href="{{ asset('/adminto/assets/plugins/datatables/jquery.dataTables.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/adminto/assets/plugins/datatables/buttons.bootstrap.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/adminto/assets/plugins/datatables/fixedHeader.bootstrap.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/adminto/assets/plugins/datatables/responsive.bootstrap.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/adminto/assets/plugins/datatables/scroller.bootstrap.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/adminto/assets/plugins/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/adminto/assets/plugins/select2/dist/css/select2-bootstrap.css')}}" rel="stylesheet"
        type="text/css">
    <link href="/adminto/assets/plugins/toastr/toastr.min.css" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}"
        rel="stylesheet">
    <link
        href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css"
        rel="stylesheet">
    <!-- App CSS -->
    <link href="{{ asset('/adminto/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminto/assets/css/core.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminto/assets/css/components.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminto/assets/css/pages.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminto/assets/css/menu.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/adminto/assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/waitMe.min.css') }}">

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

    <script src="{{ asset('/adminto/assets/js/modernizr.min.js')}}"></script>

    <!-- cusotm style -->
    <link href="{{ asset('/adminto/assets/css/my-style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/css/cstmStyle.css')}}" rel="stylesheet" type="text/css" />
    {{-- <link href="{{ asset('/css/modalStyle.css')}}?v={{time()}}" rel="stylesheet" type="text/css" /> --}}
    <style>
        .datepicker {
            direction: rtl;
        }

        .slimScrollBar {
            width: 10px !important;
        }

        #sidebar-menu ul li .menu-arrow:before {
            content: '\f107';
            font-family: 'FontAwesome';
        }

    </style>
    @yield('style')
</head>


<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->
        <div class="topbar">

            <!-- LOGO -->
            <div class="topbar-left">
                <a href="{{url('/dashboard')}}" class="logo">
                    <img class="img-responsive" src="{{ asset('/adminto/assets/images/logo.png')}}">
                </a>
            </div>
            <!--Navigation Menu-->
            {{--  ////////////////////////////////////////////////////////////////////////////////////////////////////////  --}}
            <div class="navbar navbar-default" role="navigation">
                <div class="container">

                    <!-- Page title -->
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <button class="switch-btn btn open"></button>
                        </li>
                            <h4 class="page-title">
                                {{(isset($title))? $title : ''}} </h4>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">

                        <li>
                            <!-- Notification -->
                            <div class="notification-box">
                                <ul class="list-inline m-b-0">
                                    @php
                    $user = \App\Models\Admin::first();
                                    $c_n = \DB::table('notifications')->where('notifiable_id', $user->id)->whereNull('read_at')->where('notifiable_type', 'App\Models\Admin')->exists();//$user->unreadnotifications->count();
                                    @endphp
                                    <li>
                                        <a href="javascript:void(0);" class="right-bar-toggle"
                                            style="position: relative; z-index:5000;">
                                            <i class="fa fa-bell-o"></i>
                                        </a>
                                        <div class="noti-dot">
                                            <span class="dot"></span>
                                            <span class="pulse"></span>
                                            <span id="noti-count"
                                                style="position: relative; font-size: 15px; background: red; height: 18px; padding: 0px 5px 2px 5px; border-radius: 50%; top: -15px; width: 17px; color: #fff; right: -15px;">{{$c_n}}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div><!-- end container -->
            </div><!-- end navbar -->
            {{--  ////////////////////////////////////////////////////////////////////////////////////////////////////////  --}}
        </div>
        <!-- Top Bar End -->


        @include('admin.layout.sidebar')



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="top-btns">
                    @yield('header')
                </div>
                <div class="container">

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif


                    @yield('content')

                    <!-- end row -->




                </div> <!-- container -->

            </div> <!-- content -->

            <footer class="footer">
            </footer>

        </div>


        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->


        <!-- Right Sidebar -->
        <div class="side-bar right-bar">
            <a href="javascript:void(0);" class="right-bar-toggle">
                <i class="fa fa-close"></i>
            </a>
            <h4 class="">الاشعارات</h4>
            <div class="notification-list nicescroll">
                <ul class="list-group list-no-border user-list" id="notifications">
                    @php
                    // Fetch a small page of unread notifications without loading them all
                    $notifications = $user->notifications()
                        ->whereNull('read_at')
                        ->orderByDesc('created_at')
                        ->limit(30)
                        ->get();
                    @endphp
                    @foreach($notifications as $item)
                    <li class="list-group-item {{($item->read_at)? '' : 'active'}}">
                        @if( array_key_exists('redirect', $item->data) )
                        <a href="{{(str_contains($item->data['redirect'], '?'))? $item->data['redirect'].'&notify='.$item->id : $item->data['redirect'].'?notify='.$item->id}}"
                            class="user-list-item">
                            @else
                            <a href="#" class="user-list-item">
                                @endif
                                <div class="avatar">
                                    <img src="" alt="">
                                </div>
                                <div class="user-desc" alt="{{$item->data['text']}}" title="{{$item->data['text']}}">
                                    <span class="desc">{{$item->data['text']}}</span>
                                    <span class="time">{{$item->created_at}}</span>
                                </div>
                            </a>
                    </li>
                    @endforeach

                </ul>
            </div>
        </div>
        <!-- /Right-bar -->

    </div>
    <!-- END wrapper -->



    <script>
        var resizefunc = [];

    </script>

    @if(\Request::url() != url('/dashboard') )
    <!-- jQuery  -->
    <script src="{{ asset('/adminto/assets/js/jquery.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/js/bootstrap-rtl.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/js/detect.js')}}"></script>
    <script src="{{ asset('/adminto/assets/js/fastclick.js')}}"></script>
    <script src="{{ asset('/adminto/assets/js/jquery.slimscroll.js')}}"></script>
    <script src="{{ asset('/adminto/assets/js/jquery.blockUI.js')}}"></script>
    <script src="{{ asset('/adminto/assets/js/waves.js')}}"></script>
    <script src="{{ asset('/adminto/assets/js/jquery.nicescroll.js')}}"></script>
    <script src="{{ asset('/adminto/assets/js/jquery.scrollTo.min.js')}}"></script>

    <script src="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}">
    </script>
    <script src="{{ asset('/js/bootstrap-hijri-datetimepicker.min.js')}}"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>


    <script
        src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js">
    </script>
    <!-- Datatables-->
    <script src="{{ asset('/adminto/assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/dataTables.bootstrap.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/buttons.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/jszip.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/pdfmake.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/vfs_fonts.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/buttons.print.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/dataTables.keyTable.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/responsive.bootstrap.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/datatables/dataTables.scroller.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/select2/dist/js/select2.min.js')}}" type="text/javascript"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('/adminto/assets/pages/datatables.init.js')}}"></script>
    <!-- Toastr js -->
    <script src="/adminto/assets/plugins/toastr/toastr.min.js"></script>

    <!-- App js -->
    <script src="{{ asset('/adminto/assets/js/jquery.core.js')}}"></script>
    <script src="{{ asset('/adminto/assets/js/jquery.app.js')}}?v={{time()}}"></script>
    <script src="{{ asset('js/waitMe.min.js') }}"></script>

    <script type="text/javascript">
        // Date Picker
        jQuery('.datepicker').datepicker({
            format: "yyyy-mm-dd"
        });
        jQuery('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD hh:mm:ss'
        });
        $('.start-datepicker').datepicker({
            format: 'yyyy-mm-dd'
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('.end-datepicker').datepicker('setStartDate', minDate);
        });
        $('.end-datepicker').datepicker({
            format: 'yyyy-mm-dd'
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('.start-datepicker').datepicker('setEndDate', minDate);
        });
        $(function () {
            $('.date-picker-month-year').datepicker({
                format: "mm-yyyy",
                viewMode: "months",
                minViewMode: "months"
            });
            $('.date-picker-year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
        });
        $(document).ready(function () {
            $('#datatable').DataTable();
            $('#datatable-keytable').DataTable({
                keys: true
            });
            $('#datatable-responsive').DataTable();
            $('#datatable-scroller').DataTable({
                ajax: "{{ asset('/adminto/assets/plugins/datatables/json/scroller-demo.json')}}",
                deferRender: true,
                scrollY: 380,
                scrollCollapse: true,
                scroller: true
            });
            var table = $('#datatable-fixed-header').DataTable({
                fixedHeader: true
            });
        });
        TableManageButtons.init();

    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
    <script>
        $("#Customer_isOwner").on("click", function () {
            var checked = $(this).is(':checked');
            if (checked) {
                $(".owner-inputs").removeClass('hidden');
            } else {
                $(".owner-inputs").addClass('hidden');
            }
        });

        $("#Customer_isSc").on("click", function () {
            var checked = $(this).is(':checked');
            if (checked) {
                $(".owner-sc").removeClass('hidden');
            } else {
                $(".owner-sc").addClass('hidden');
            }
        });

        $('#bank-plus').on('click', function () {
            if ($('.bank-plus').hasClass('hidden')) {
                $('.bank-plus').removeClass('hidden');
            } else {
                $('.bank-plus').addClass('hidden');
            }
            return false;
        });

        //$(".select2").select2();

    </script>
    @if(session()->has('success'))
    <script type="text/javascript">
        toastr["success"]("تمت العملية بنجاح .")

        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
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
    @if(session()->has('warning'))
    <script type="text/javascript">
        toastr["warning"]("{{session()->get('warning')}}")

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
        var deleter = {

            linkSelector: "a#delete-btn",

            init: function () {
                $(this.linkSelector).on('click', {
                    self: this
                }, this.handleClick);
            },

            handleClick: function (event) {
                event.preventDefault();

                var self = event.data.self;
                var link = $(this);

                swal({
                    title: "هل أنت متأكد؟",
                    text: "أنك تريد حذف هذه المنطقه ؟",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        var cr = $('meta[name="csrf-token"]').attr('content');
                        var o = link;
                        // alert(cr);
                        $(o).append('<i class="fa fa-spin fa-spinner"></i>');
                        $.post($(o).attr('href'), {
                            _token: cr,
                            _method: 'DELETE'
                        }, function (data) {
                            $(o).find('i').remove();
                            $(o).append('<i class="fa fa-check"></i>');
                            setTimeout(function () {
                                $(o).parent().parent().remove();
                                if (typeof cb == 'function') {
                                    cb();
                                }
                            }, 1000);
                        });
                    } else {
                        swal("تم الالغاء", "الحذف  الغي بنجاح");
                    }
                });

            },
        };

        deleter.init();

    </script>
    <script>
        var deleterfile = {

            linkSelector: "a.delete-file",

            init: function () {
                $(this.linkSelector).on('click', {
                    self: this
                }, this.handleClick);
            },

            handleClick: function (event) {
                event.preventDefault();

                var self = event.data.self;
                var link = $(this);

                swal({
                    title: "هل أنت متأكد؟",
                    text: "أنك تريد حذف هذه المنطقه ؟",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then(function (isConfirm) {
                    if (isConfirm) {
                        var cr = $('meta[name="csrf-token"]').attr('content');
                        var o = link;
                        // alert(cr);
                        $(o).append('<i class="fa fa-spin fa-spinner"></i>');
                        $.post($(o).attr('href'), {
                            _token: cr,
                            _method: 'DELETE'
                        }, function (data) {
                            $(o).find('i').remove();
                            $(o).append('<i class="fa fa-check"></i>');
                            setTimeout(function () {
                                $(o).parent().parent().remove();
                                if (typeof cb == 'function') {
                                    cb();
                                }
                            }, 1000);
                        });
                    } else {
                        swal("تم الالغاء", "الحذف  الغي بنجاح");
                    }
                });

            },
        };

        deleterfile.init();

    </script>
    <script>
        /*$(document).on('change', 'select[name=order_type]', function(){
                    var o = $(this);
                    $.get( "{{url('/ajax/drivers-by-type')}}" + "/" + $(this).val(), function( data ) {
                        o.closest('form').find('select[name=driver_id]').html(data).select2();
                    });
                });
                $(document).on('change', 'select[name=city_id]', function(){
                    var o = $(this);
                    $.get( "{{url('/regions')}}" + "/" + $(this).val(), function( data ) {
                        o.closest('form').find('select[name=region_id]').html(data);
                    });
                });
                $(document).on('change', 'select[name=region_id]', function(){
                    var o = $(this);
                    $.get( "{{url('/regions')}}" + "/" + $(this).val(), function( data ) {
                        o.closest('form').find('select[name=district_id]').html(data);
                    });
                });*/

        $(document).on('change', 'select[name=city_id]', function () {
            var cityID = $(this).val();
            //alert(cityID);
            var o = $(this);
            if (cityID) {
                $.ajax({
                    type: "GET",
                    url: "{{url('dashboard/city-id')}}?city_id=" + cityID,
                    success: function (res) {
                        if (res) {
                            $("#cities").empty();
                            //$("#cities").append('<option>Select</option>');
                            $.each(res, function (key, value) {
                                if (o.data('district_id') != '' && key == o.data(
                                        'district_id')) {
                                    $("#cities").append('<option value="' + key +
                                        '" selected>' + value + '</option>');
                                } else {
                                    $("#cities").append('<option value="' + key + '">' +
                                        value + '</option>');
                                }
                            });
                            console.log(res);

                        } else {
                            $("#cities").empty();
                        }
                    }
                });
            } else {
                $("#cities").empty();

            }
        });

    </script>

    <script
        src="{{ asset('/adminto/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}">
    </script>
    <script src="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}">
    </script>
    <script>
        jQuery('.datepicker-input').datepicker();

    </script>

    <script>
        //    loader

        $('.select2').select2();

    </script>

    <script>
        function getNotification(url, icon, title, message) {
            if (!Notification) {
                $('body').append('*Browser does not support Web Notification');
                return;
            }
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            } else {
                var notificationUrl = url;
                var notificationObj = new Notification(title, {
                    icon: icon,
                    body: message,
                });
                notificationObj.onclick = function () {
                    window.open(notificationUrl);
                    notificationObj.close();
                };
                setTimeout(function () {
                    notificationObj.close();
                }, 5000);
            }
        }

    </script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('102a762d9098926ae234', {
            cluster: 'eu'
        });

        var channel = pusher.subscribe('my-channel-{{auth()->user()->id}}');
        channel.bind('general', function (data) {
            //alert(JSON.stringify(data));
            getNotification(data['link'], "{{ asset('/adminto/assets/images/logo.png')}}", 'MadarExpress', data[
                'message']);
            $('#notify-sign').removeClass('hidden');
            $.get('{{route("noti-count")}}')
                .done(function (res) {
                    $('#noti-count').html(res);
                });
            $('#notifications').prepend(`
                <li class="list-group-item active">
                            <a href="` + data['link'] + `" class="user-list-item">
                                <div class="avatar">
                                    <img src="" alt="">
                                </div>
                                <div class="user-desc" alt="` + data['message'] + `" title="` + data['message'] + `">
                                    <span class="desc">` + data['message'] + `</span>
                                    <span class="time">` + data['date'] + `</span>
                                </div>
                            </a>
                        </li>
                `);
            playSound('{{url("/notify.wav")}}');
            toastr["success"](data['message'])

            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        });

        function playSound(url) {
            const audio = new Audio(url);
            audio.play();
        }
    </script>

    <script type="text/javascript">
        $(".hijri-date-input").hijriDatePicker({
            hijri: true
        });

    </script>
    @endif
    @yield('script')
    <script>
        $(".switch-btn").on("click", function () {
            $(this).toggleClass('open');
            $('.side-menu.left').toggleClass('move');
            $('.content-page').toggleClass('move');

        });

    </script>
</body>

</html>
