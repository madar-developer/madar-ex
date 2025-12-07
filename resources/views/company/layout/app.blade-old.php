<!DOCTYPE html>
<html lang="en" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="دليلك الأول للعقارات فى القاهرة الجديدة و الرحاب و مدينتى و شقق للبيع و الايجار و فلل للبيع و الايجار فى الرحاب و مدينتى و القاهرة الجديدة.">
        <meta name="keywords" content="شقق للبيع فى مدينتى, شقق للبيع فى الرحاب, فلل للبيع فى مدينتى, فلل للايجار فى الرحاب, شقق للبيع فى القاهرة الجديدة, فلل للبيع فى القاهرة الجديدة, شقق للبيع فى التجمع الخامس, شقق للايجار فى التجمع الخامس" />
        <meta name="rights" content="جميع الحقوق محفوظة لالباصات" />
        <meta name='audience' content='all' />
        <meta name="distribution" content="global" />
        <meta name="robots" content="all" />
        <meta name='rating' content='general' />

        <meta name="expires" content="never" />  
        
        <meta name="author" content="Coderthemes">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <meta name="generator" content="loc.sa" />
       
        
        
        
        {{-- <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/adminto/assets/images/favicon/apple-icon-57x57.png')}}"> --}}
        <link rel="icon" type="image/jpg" href="/front/images/logova.jpg"/>
<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/adminto/assets/images/favicon/apple-icon-60x60.png')}}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/adminto/assets/images/favicon/apple-icon-72x72.png')}}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/adminto/assets/images/favicon/apple-icon-76x76.png')}}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/adminto/assets/images/favicon/apple-icon-114x114.png')}}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/adminto/assets/images/favicon/apple-icon-120x120.png')}}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/adminto/assets/images/favicon/apple-icon-144x144.png')}}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/adminto/assets/images/favicon/apple-icon-152x152.png')}}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/adminto/assets/images/favicon/apple-icon-180x180.png')}}">
<link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('/adminto/assets/images/favicon/android-icon-192x192.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/adminto/assets/images/favicon/favicon-32x32.png')}}">
<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/adminto/assets/images/favicon/favicon-96x96.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/adminto/assets/images/favicon/favicon-16x16.png')}}">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="{{ asset('/adminto/assets/images/favicon/ms-icon-144x144.png')}}">
<meta name="theme-color" content="#ffffff">



        <title>مدار اكسبرس | {{(isset($title))? $title : ''}}  </title>
        <link href="https://fonts.googleapis.com/css?family=Cairo:400,600,700,900|Tajawal:400,500,700&amp;subset=arabic" rel="stylesheet">
        <!-- Modal -->
        <link href="{{ asset('/adminto/assets/plugins/custombox/dist/custombox.min.css')}}" rel="stylesheet">
        <link href="{{ asset('/adminto/assets/plugins/toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />

        <link href="{{ asset('/adminto/assets/plugins/multiselect/css/multi-select.css')}}"  rel="stylesheet" type="text/css" />
        <link href="{{ asset('/adminto/assets/plugins/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css">
        <link href="{{ asset('/adminto/assets/plugins/select2/dist/css/select2-bootstrap.css')}}" rel="stylesheet" type="text/css">

        <link href="/adminto/assets/plugins/switchery/switchery.min.css" rel="stylesheet" />

        <link href="{{ asset('/adminto/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/adminto/assets/css/core.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/adminto/assets/css/components.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/adminto/assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/adminto/assets/css/pages.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/adminto/assets/css/menu.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/adminto/assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        @yield('style')
        <script src="{{ asset('/adminto/assets/js/modernizr.min.js')}}"></script>
        <style type="text/css">
            /*@media (min-width: 992px){
                #topnav .navigation-menu>li>a {
                    padding-left: 0;
                    }
                }*/
                
                #load{
                    width:100%;
                    height:100%;
                    position:fixed;
                    z-index:9999;
                    background:url("{{url('/preloader.gif')}}") no-repeat center center rgba(0,0,0,0.25)
                }
                .pop_image_item{
                    width: 300px;
                    height: 300px;
                    border-radius: 15px;
                    border: 2px solid #435966;
                    }
                    .tab-pane {
                        overflow-x: scroll !important;
                    }
        </style>


    </head>


    <body>
        {{-- <div id="load"></div> --}}

        <!-- Navigation Bar-->
        <header id="topnav">
            <div class="topbar-main">
                <div class="container">

                    <!-- LOGO -->
                    <div class="topbar-left">
                        <a href="{{url('/dashboard')}}" class="logo">
                            Madar Ex
                        </a>
                    </div>
                    <!-- End Logo container-->


                    <div class="menu-extras">

                        <ul class="nav navbar-nav navbar-right pull-right">
                            <li>
                                <form role="search" class="navbar-left app-search pull-left hidden-xs">
                                     <input type="text" placeholder="Search..." class="form-control">
                                     <a href=""><i class="fa fa-search"></i></a>
                                </form>
                            </li>
                            <li>
                            @php
                            if(auth()->user()->role == 'admin')
                            {
                                $user = \App\Models\Admin::where('role', 'admin')->first();
                            }else{
                                $user = auth()->user();
                            }
                            @endphp
                                <!-- Notification -->
                                <div class="notification-box">
                                    <ul class="list-inline m-b-0">
                                        <li>
                                            <a href="javascript:void(0);" class="right-bar-toggle">
                                                <i class="zmdi zmdi-notifications-none"></i>
                                            </a>
                                            <div id="notify-sign" class="noti-dot {{($user->unreadnotifications->count() > 0)? '' : 'hidden'}}">
                                                <span class="dot"></span>
                                                <span class="pulse"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Notification bar -->
                            </li>

                            <li class="dropdown user-box {!! (CheckPermission('settings_show'))? '' : 'hidden'  !!}" >
                                <a href="{{url('/dashboard/settings/site')}}" class=" waves-effect waves-light profile " >
                                    <span><i class="fa fa-cog"></i></span>
                                </a>
                            </li>

                            <li class="dropdown user-box">
                                <a href="" class="dropdown-toggle waves-effect waves-light profile " data-toggle="dropdown" aria-expanded="true">
                                    <img src="{{(auth()->user()->image)?getImage(auth()->user()->image):asset('/adminto/assets/images/users/avatar-1.jpg')}}" alt="user-img" class="img-circle user-img">
                                    <div class="user-status away"><i class="zmdi zmdi-dot-circle"></i></div>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><a href="{{url('/dashboard/settings/admins-edit/'.auth()->id())}}"><i class="ti-user m-r-5"></i> الملف الشخصي</a></li>
                                    <li><a href="{{ url('/dashboard/branches') }}"><i class="ti-settings m-r-5"></i>ادارة الفروع</a></li>
                                    <li><a href="{{url('/dashboard/settings/site')}}"><i class="ti-settings m-r-5"></i> الأعدادات</a></li>
                                    <li>  <a href="{{ route('logout') }}"
                                                 onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                <span class="ti-power-off m-r-5"></span> تسجيل الخروج
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="dashboard" value="1">
                                            </form>
                                        </li>
                                </ul>
                            </li>
                        </ul>
                        
                        <div class="menu-item">
                            <!-- Mobile menu toggle-->
                            <a class="navbar-toggle">
                                <div class="lines">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <!-- End mobile menu toggle-->
                        </div>
                    </div>

                </div>
            </div>

            <div class="navbar-custom">
                <div class="container">
                    <div id="navigation">
                        @include('admin.layout.nav')
                    </div>
                </div>
            </div>
        </header>
        <!-- End Navigation Bar-->


        <div class="wrapper">
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


                <!-- Footer -->
                <footer class="footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                كل الحقوق محفوظة لدى   2019 تصميم وبرمجة
                                <a href="http://loc.sa" target="_blank">
                                    مدار الريادة لتقنية المعلومات
                                </a>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- End Footer -->

            </div>
            <!-- end container -->



            <!-- Right Sidebar -->
            <div class="side-bar right-bar">
                <a href="javascript:void(0);" class="right-bar-toggle">
                    <i class="zmdi zmdi-close-circle-o"></i>
                </a>
                <h4 class="">التنبيهات</h4>
                <div class="notification-list nicescroll">
                    <ul class="list-group list-no-border user-list" id="notifications">
                       
                        {{-- <li class="list-group-item active">
                            <a href="#" class="user-list-item">
                                <div class="avatar">
                                    <img src="{{ asset('/adminto/assets/images/users/avatar-3.jpg')}}" alt="">
                                </div>
                                <div class="user-desc">
                                    <span class="name">James Anderson</span>
                                    <span class="desc">There are new settings available</span>
                                    <span class="time">2 days ago</span>
                                </div>
                            </a>
                        </li> --}}

                    </ul>
                </div>
            </div>
            <!-- /Right-bar -->

        </div>
@if(\Request::url() != url('/dashboard') && \Request::url() != url('/company') && \Request::url() != url('/school') )
        <!-- jQuery  -->
        <script src="{{ asset('/adminto/assets/js/jquery.min.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/bootstrap-rtl.min.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/detect.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/fastclick.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/jquery.slimscroll.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/jquery.blockUI.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/waves.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/wow.min.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/jquery.nicescroll.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/jquery.scrollTo.min.js')}}"></script>
        
<script src="/adminto/assets/plugins/switchery/switchery.min.js"></script>
        <script type="text/javascript" src="{{ asset('/adminto/assets/plugins/multiselect/js/jquery.multi-select.js')}}"></script>
        <script type="text/javascript" src="{{ asset('/adminto/assets/plugins/jquery-quicksearch/jquery.quicksearch.js')}}"></script>
        <script src="{{ asset('/adminto/assets/plugins/select2/dist/js/select2.min.js')}}" type="text/javascript"></script>


        <!-- Modal-Effect -->
        <script src="{{ asset('/adminto/assets/plugins/custombox/dist/custombox.min.js')}}"></script>
        <script src="{{ asset('/adminto/assets/plugins/custombox/dist/legacy.min.js')}}"></script>
        <!-- Toastr js -->
        <script src="{{ asset('/adminto/assets/plugins/toastr/toastr.min.js')}}"></script>
        <!-- App js -->
        <script src="{{ asset('/adminto/assets/js/jquery.core.js')}}"></script>
        <script src="{{ asset('/adminto/assets/js/jquery.app.js')}}"></script>
        {{-- <script src="{{ asset('/js/scripts.js')}}"></script> --}}
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

                $(".select2").select2();

                
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

          linkSelector : "a#delete-btn",

          init: function() {
              $(this.linkSelector).on('click', {self:this}, this.handleClick);
          },

          handleClick: function(event) {
              event.preventDefault();

              var self = event.data.self;
              var link = $(this);

              swal({
                  title: "هل أنت متأكد؟",
                  text: "أنك تريد حذف هذه المنطقه ؟",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              }).then(function(isConfirm){
                  if(isConfirm){
                      var cr = $('meta[name="csrf-token"]').attr('content');
                      var o = link;
                      // alert(cr);
                      $(o).append('<i class="fa fa-spin fa-spinner"></i>');
                      $.post($(o).attr('href'),{
                        _token:cr,
                        _method:'DELETE'
                      },function(data){
                        $(o).find('i').remove();
                        $(o).append('<i class="fa fa-check"></i>');
                        setTimeout(function(){
                          $(o).parent().parent().remove();
                          if(typeof cb  == 'function'){
                            cb();
                          }
                        },1000);
                      });
                  }
                  else{
                      swal("تم الالغاء", "الحذف  الغي بنجاح");
                  }
              });

          },
      };

      deleter.init();
  </script>
  <script>
      var deleterfile = {

          linkSelector : "a.delete-file",

          init: function() {
              $(this.linkSelector).on('click', {self:this}, this.handleClick);
          },

          handleClick: function(event) {
              event.preventDefault();

              var self = event.data.self;
              var link = $(this);

              swal({
                  title: "هل أنت متأكد؟",
                  text: "أنك تريد حذف هذه المنطقه ؟",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              }).then(function(isConfirm){
                  if(isConfirm){
                      var cr = $('meta[name="csrf-token"]').attr('content');
                      var o = link;
                      // alert(cr);
                      $(o).append('<i class="fa fa-spin fa-spinner"></i>');
                      $.post($(o).attr('href'),{
                        _token:cr,
                        _method:'DELETE'
                      },function(data){
                        $(o).find('i').remove();
                        $(o).append('<i class="fa fa-check"></i>');
                        setTimeout(function(){
                          $(o).parent().parent().remove();
                          if(typeof cb  == 'function'){
                            cb();
                          }
                        },1000);
                      });
                  }
                  else{
                      swal("تم الالغاء", "الحذف  الغي بنجاح");
                  }
              });

          },
      };

      deleterfile.init();
  </script>
  @endif
    <script>
        $(document).on('change', 'select[name=order_type]', function(){
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
        });
    </script>
        
    <script src="{{ asset('/adminto/assets/plugins/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
    <script src="{{ asset('/adminto/assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script>
    jQuery('.datepicker-input').datepicker();
    
    </script>
    
    <script>
        //    loader
        {{-- document.onreadystatechange = function () {
          var state = document.readyState
          if (state == 'interactive') {
              document.getElementById('contents').style.visibility="hidden";
          } else if (state == 'complete') {
              setTimeout(function(){
                  document.getElementById('interactive');
                  document.getElementById('load').style.visibility="hidden";
                  document.getElementById('contents').style.visibility="visible";
              },1000);
          }
      } --}}

      $(document).on('click', '[type=submit]', function(){
         // $(this).html('<i class="fa fa-spin fa-spinner"></i>');
          // $(this).prop('disabled', true);
          // return true;
      })
  </script>
        @yield('script')
    </body>
</html>