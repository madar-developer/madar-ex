<html>

<head>
    <link rel="shortcut icon" href="{{asset('/assets/images/madar-logo-dark.png')}}">
    <title> مدار اكسبريس | للشحن والتغليف</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css" />
    <link href="{{url('/assets/css/owl.carousel.css')}}" rel="stylesheet">
    <link href="{{url('/assets/css/bootstrap.css')}}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('/css/modalStyle.css')}}?v={{time()}}" rel="stylesheet" type="text/css" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel='stylesheet'
        type='text/css' />
    <link href="{{url('/assets/css/style.css')}}" rel='stylesheet' type='text/css' />
    <link href="{{url('/assets/css/mt-style.css')}}" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
        <style>
            .failed i{
                color: red;
            }
            .alert2 {
              padding: 10px;
              background-color: green;
              color: white;
              direction:rtl;
            }

            .closebtn {
              margin-left: 15px;
              color: white;
              font-weight: bold;
              float: left;
              font-size: 22px;
              line-height: 20px;
              cursor: pointer;
              transition: 0.3s;
            }

            .closebtn:hover {
              color: black;
            }
            @media (min-width: 992px) {

.intro-content {
    min-height: 250px;
    background: #fecd3680;
    padding: 30px;
}


}

#accordion input,
.form-control {
font-size: 14px !important;
font-family: 'DroidArabicKufiRegular';
font-weight: 600 !important;
}

.contact-form input[type="submit"] {
background-color: #0d0f30;
color: #fff;
background: url('{{url("/image27.png")}}') no-repeat 0px 0px;
padding-top: 12px;
padding-bottom: 12px;
border: 0 !important;
}
.contact-form input[type="submit"] {
    background-color: #0d0f30 !important;
    color: #fff;
    background: url(https://madarex.sa/image27.png) no-repeat 8px 10px;
    padding-top: 12px;
    padding-bottom: 12px;
    border: 0 !important;
    display: flex;
}

.contact-form input[type="submit"]:hover {
    background-color: #ffc535 !important;
    color: #fff !important;
}
            </style>
</head>

<body>



    <div id="loading-screen">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>

    <div class="modal fade" id="sendEmail" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="intro-content">
                        <h2>طلب عرض سعر </h2>
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="  البريد الالكتروني " id="">
                                <a class="btn follow" type="submit">ارسال </a>
                            </div>
                        </form>
                        <div class="message">
                            <i class="fa fa-check"></i>
                            <p>
                                تم ارسال الايميل بنجاح <br>سيتم ارسال عرض السعر في اقرب وقت
                            </p>
                        </div>
                    </div>
                    <!-- intro-content -->
                </div>
                <!-- modal-body -->
            </div>
        </div>
    </div>
    <div class="modal cstm-modal fade" id="showState" role="dialog">
        <div class="modal-dialog modal-lg"  id="order-response">
        </div>
    </div>
    @if (0)

    <div class="modal fade" id="showState" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="intro-content">
                        <h2>حالة الشحنة </h2>
                        <div class="transport-state">
                            {{-- /////////////////////////////// --}}
                            <div class="row" id="order-response">
                                {{-- <div class="col-lg-4">
                                        <div class="one-state done">
                                            <i class="fa fa-check"></i>
                                            <h4>مرحلة   1 </h4>
                                        </div>
                                        <!-- one-state -->
                                    </div>
                                    <!-- col-lg-4 -->
                                    <div class="col-lg-4">
                                        <div class="one-state in-progress">
                                            <img style="width: 60px;" src="https://image.flaticon.com/icons/svg/984/984233.svg">
                                            <h4>مرحلة   2 </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="one-state to-do">
                                            <i class="fa fa-check"></i>
                                            <h4>مرحلة   3 </h4>
                                        </div>
                                    </div>
                                    <!-- col-lg-4 --> --}}
                            </div>
                        </div>
                    </div>
                    <!-- intro-content -->
                </div>
                <!-- modal-body -->
            </div>
        </div>
    </div>
    @endif

    <div class="header-section">
        <!----- start-header---->
        <div id="home" class="header">
            @if (session()->has('success'))
            <div class="alert2">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                <strong>تنبية : </strong> {{session()->get('success')}}
              </div>
            @endif
            <div class="container">
                <div class="top-header">
                    <div class="logo">
                        <a href="#">
                            <img src="{{url('/assets/images/logo.png')}}" title="logo" />
                        </a>
                    </div>
                    <!----start-top-nav---->
                    <nav class="top-nav">
                        <ul class="top-nav">
                             <li class="page-scroll">
                                <a href="{{url('/')}}#top" class="scroll">الرئيسية </a>
                            </li>
                             <li class="page-scroll">
                                <a href="#fea" class="scroll"> مميزات التطبيق </a>
                            </li>
                             <li class="page-scroll">
                                <a href="#services" class="scroll"> خدماتنا </a>
                            </li>
                             <li class="page-scroll">
                                <a href="#download" class="scroll">تحميل التطبيق </a>
                            </li>
                            <li class="page-scroll">
                                {{-- <a href="#" data-toggle="modal" data-target="#sendEmail">اطلب عرض سعر </a> --}}
                                 <a href="#accordion">اطلب عرض سعر </a>
                            </li>
                            <li class="page-scroll">
                            <a class="login-link" href="{{route('company-register')}}" class="scroll"> انشاء حساب </a>
                            </li>
                            <li class="page-scroll">
                            <a class="login-link" href="{{url('/admin/login')}}" class="scroll"> دخول </a>
                            </li>
                        </ul>
                        <a href="#" id="pull">
                            <img src="{{url('/assets/images/nav-icon.png')}}" title="menu" />
                        </a>
                    </nav>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!----- //End-header---->
        <!-- slider -->
        <div id="top" class="callbacks_container">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="wow slideInLeft" data-wow-delay="0.6s">
                            <div class="intro-image">
                                <img style="width: 100%;" class="img-responsive"
                                    src="{{url('/assets/images/slider-img.png')}}">
                            </div>
                        </div>
                    </div>
                    <!-- col-lg-6 -->
                    <div class="col-lg-6">
                        <!--
                        
                        <div class="intro-content">
                            <h2> تتبع الشحنات </h2>
                            <p>الرجاء ادخال رقم الشحنة او رقم الطلب والضغط على متابعة لمعرفة حالة الشحنة </p>
                            <form>
                                {{-- <div class="form-group">
                                    <select name="store" id="orderStore" class="form-control" style="height: 4rem;padding: 18px;font-weight: bold;font-size: 17px;">
                                        <option value="">اختر المتجر</option>
                                        @foreach (\App\Models\Company::where('active', '1')->get() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="ادخل رقم الشحنة او رقم الطلب   "
                                        id="orderStatus">
                                    <a data-toggle="modal" data-target="#showState" class="btn follow"
                                        id='follow'>متابعة </a>
                                </div>
                            </form>
                        </div>
                        -->
                        <div class="intro-content">
                            <h2> تتبع الشحنات </h2>
                            <p>الرجاء ادخال رقم البوليصة والضغط على متابعة لمعرفة حالة الشحنة </p>
                            <form>
                                {{-- <div class="form-group">
                                    <select name="store" id="orderStore" class="form-control" style="height: 4rem;padding: 18px;font-weight: bold;font-size: 17px;">
                                        <option value="">اختر المتجر</option>
                                        @foreach (\App\Models\Company::where('active', '1')->get() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="ادخل رقم البوليصة   "
                                        id="orderStatus">
                                    <a data-toggle="modal" data-target="#showState" class="btn follow"
                                        id='follow'>متابعة </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- col-lg-6 -->

                </div>
            </div>
            <!-- container -->
        </div>
        <!----- //End-slider---->
        <div class="clearfix"></div>
    </div>
    <div id="fea" class="features">
        <div class="container">
            <div class="section-head text-center">
                <h3>
                    <span class="frist"></span>
                    مميزات التطبيق   <span class="second"></span>
                </h3>
            </div>
            <!----features-grids----->
            <div class="features-grids">
                <div class="col-md-4 features-grid">
                    <div class="features-grid-info wow slideInRight">
                        <div class="col-md-2 features-icon">
                            <img class="{{url('/assets/img-responsive')}}" src="{{url('/assets/images/delivery-vector-free-icon-set-02.png')}}" height="55px" width="55px">
                        </div>
                        <div class="col-md-10 features-info">
                            <h4>تصميم انيق وجميل  </h4>
                            <p>تقدر توصل أى شىء داخل مدينتك فى نفس اليوم أو تشحن لمدينه اخرى مع امكانيه الدفع عند الاستلام </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="features-grid-info wow slideInRight">
                        <div class="col-md-2 features-icon">
                            <img class="{{url('/assets/img-responsive')}}" src="{{url('/assets/images/delivery-vector-free-icon-set-03.png')}}" height="55px" width="55px">
                        </div>
                        <div class="col-md-10 features-info">
                            <h4>حدد موقعك  </h4>
                            <p>أختر موقعك على الخريطه واحفظ عناوينك المفضلة </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="features-grid-info wow slideInRight">
                        <div class="col-md-2 features-icon">
                            <img class="{{url('/assets/img-responsive')}}" src="{{url('/assets/images/delivery-vector-free-icon-set-04.png')}}" height="55px" width="55px">
                        </div>
                        <div class="col-md-10 features-info">
                            <h4>محتوى الشحنة </h4>
                            <p>حدد محتوى شحنتك واطلع على افضل الاسعار للتوصيل </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="features-grid-info wow slideInRight">
                        <div class="col-md-2 features-icon">
                            <img class="{{url('/assets/img-responsive')}}" src="{{url('/assets/images/delivery-vector-free-icon-set-05.png')}}" height="55px" width="55px">
                        </div>
                        <div class="col-md-10 features-info">
                            <h4>بيانات المستلم  </h4>
                            <p>حدد محتوى شحنتك واطلع على افضل الاسعار للتوصيل  </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!---end-features-grid---->
                <div class="col-md-4 features-grid wow slideInUp" data-wow-offset="70">
                    <div class="big-divice">
                        <img src="{{url('/assets/images/divice.png')}}" title="features-demo"/>
                    </div>
                </div>
                <!---end-features-grid---->
                <div class="col-md-4 features-grid">
                    <div class="features-grid-info wow slideInLeft">
                        <div class="col-md-2 features-icon1">
                            <img class="{{url('/assets/img-responsive')}}" src="{{url('/assets/images/delivery-vector-free-icon-set-07.png')}}" height="55px" width="55px">
                        </div>
                        <div class="col-md-10 features-info">
                            <h4>ارسال الشحنة </h4>
                            <p>ابدا الانطلاق الان ومتابعة طلباتك من قائمة شحناتي </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="features-grid-info wow slideInLeft">
                        <div class="col-md-2 features-icon1">
                            <img class="{{url('/assets/img-responsive')}}" src="{{url('/assets/images/delivery-vector-free-icon-set-08.png')}}" height="55px" width="55px">
                        </div>
                        <div class="col-md-10 features-info">
                            <h4>شحناتي </h4>
                            <p>قائمة بكل الشحنات مع امكانية تتبع كل شحنه ومكان كابتنلبات داخل كل يوم على حده </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="features-grid-info wow slideInLeft">
                        <div class="col-md-2 features-icon1">
                            <img class="{{url('/assets/img-responsive')}}" src="{{url('/assets/images/delivery-vector-free-icon-set-01.png')}}" height="55px" width="55px">
                        </div>
                        <div class="col-md-10 features-info">
                            <h4>قائمة الفواتير </h4>
                            <p>قائمة بجميع فواتيرك مع ملخص يومى للرصيد المالى والاطلاع على قائمه الطلبات داخل كل يوم على حده </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="features-grid-info wow slideInLeft">
                        <div class="col-md-2 features-icon1">
                            <img class="{{url('/assets/img-responsive')}}" src="{{url('/assets/images/delivery-vector-free-icon-set-06.png')}}" height="55px" width="55px">
                        </div>
                        <div class="col-md-10 features-info">
                            <h4>الحوالات البنكية   </h4>
                            <p>قائمة الحوالات البنكية التى تم تحويلها لك مع تفاصيل كل حوالة   </p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!---end-features-grid---->
                <div class="clearfix"></div>
            </div>
        </div>
        <!---728x90--->

        <div class="clearfix"></div>
    </div>

    <div id="services">
        <div class="section-head text-center wow slideInUp">
            <h3>
                <span class="frist"></span>
                خدماتنا <span class="second"></span>
            </h3>
        </div>
        <div class="services-content">
            <div class="container">

                <div class="row">
                    <div class="col-lg-4">
                        <div class="one-service">
                            <img class="img-responsive" src="{{url('/assets/images/icon03.png')}}">
                            <h3> توصيل الطلبات </h3>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="one-service">
                            <img class="img-responsive" src="{{url('/assets/images/icon01.png')}}">
                            <h3> شحن الطلبات </h3>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="one-service">
                            <img class="img-responsive" src="{{url('/assets/images/icon02.png')}}">
                            <h3> التخزين و التغليف </h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-12">

                        <div class="bs-example">
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> اطلب
                                                عرض سعر
                                                <span class="fa fa-chevron-down"></span> </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse">
                                        <div class="panel-body">


                                            <div class="contact-form">
                                                <form method="POST" action="{{url('/post_form')}}" id="contact-form">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-lg-3">
                                                            <input type="text" name="name" id="name" placeholder=" الاسم "
                                                                required />
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <input type="email" name="email" id="email" placeholder=" الايميل  "
                                                                required />
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <input type="text" name="phone" id="phone" placeholder="رقم الجوال  "
                                                                required />
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <input type="text" name="city" id="city" placeholder=" المدينه  "
                                                                required />
                                                        </div>
                                                        <div class="col-lg-3">
                                                            <input type="text" name="organization_name" id="organization_name" placeholder=" اسم الجهه  "
                                                                required />
                                                        </div>
                                                        <div class="col-lg-12 text-center">
                                                            <input style="margin: auto;" type="submit"
                                                                value=" ارسال " />
                                                        </div>
                                                    </div>
                                                </form>


                                                <div class="message" id="contact-message" style="display: none;">
				                                <i class="fa fa-check"></i>
				                                <p>
				                                    تم ارسال رسالتك بنجاح وسيتم التواصل معكم قريباً
				                                </p>
				                            </div>


                                            </div>


                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>


    <div id="gallery" class="screen-shot-gallery">
        <div class="container">
            <div class="section-head text-center wow slideInUp">
                <h3>
                    <span class="frist"></span>
                    صور من التطبيق <span class="second"></span>
                </h3>
            </div>
        </div>
        <!----sreen-gallery-cursual---->
        <div class="sreen-gallery-cursual">
            <!-- //requried-jsfiles-for owl -->
            <!-- start content_slider -->
            <div class="container">
                <div id="owl-demo" class="owl-carousel wow slideInUp">

                    @foreach (App\Models\Slider::where('type', '<>', 'app')->get() as $item)

                    <div class="item">
                        <img class="lazyOwl img-responsive" data-src="{{getImage($item->image)}}"
                            alt="screen-name">
                    </div>

                    @endforeach
                </div>
            </div>
            <!--//sreen-gallery-cursual---->
        </div>
    </div>

    <div class="featured">
        <div class="section-head text-center wow slideInUp">
            <h3>
                <span class="frist"></span>
                 عملائنا  <span class="second"></span>
            </h3>
        </div>
        <div id="owl-demo2" class="owl-carousel wow slideInUp">

            @foreach (App\Models\Partner::get() as $item)

            <div class="item">
                <img src="{{getImage($item->image)}}" title="{{$item->title}}"/>
            </div>
            @endforeach

        </div>
    </div>






    <div class="show-reel text-center">
        <div class="vedio-container">
            <div class="section-head text-center wow slideInUp">
                <h3>
                    <span class="frist"></span>
                    عن مدار اكسبرس <span class="second"></span>
                </h3>
                <p>هي شركة متخصصة في توصيل طلبات المتاجر الالكترونية والشركات والأفراد داخل مدينة الرياض في نفس اليوم عن طريق تطبيق خاص على أجهزة  الاندرويد و الآيفون بسيارات خاصة بالشركة ومجهزة تجهيز كامل للتوصيل من المتجر الى باب العميل بكل سهولة وأمان مع خدمة الدفع عن الاستلام .</p>
            </div>
            <div class="wow slideInUp">
              <iframe width="100%" height="444" src="https://www.youtube.com/embed/QoFk472vF-Y" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>




    <div id="download" class="footer">
        <div class="container">

            <div class="row">
                <div class="col-lg-3">
                    <h2> عن التطبيق </h2>
                    <a href="#"> <img style="margin-bottom: 15px;" src="{{url('/assets/images/logo-white.png')}}"> </a>
                    <p> مدار اكسبريس للشحن و التغليف في المملكة العربية السعودية </p>
                </div>
                <div class="col-lg-3">
                    <div class="links">
                        <h2> اهم الروابط </h2>
                        <ul class="footer-nav">
                            <li class="page-scroll">
                                <a href="#fea" class="scroll">مميزات التطبيق </a>
                            </li>
                            <li class="page-scroll">
                                <a href="#services" class="scroll"> خدماتنا </a>
                            </li>
                            <li class="page-scroll">
                                <a href="#clients" class="scroll"> عملائنا </a>
                            </li>
                            <li class="page-scroll">
                                <a href="#gallery" class="scroll"> صور من التطبيق </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <h2>حمل التطبيق الأن </h2>
                    <div class="download text-center wow slideInUp">
                        <a
                            href="https://apps.apple.com/us/app/madar-express-%D9%85%D8%AF%D8%A7%D8%B1-%D8%A7%D9%83%D8%B3%D8%A8%D8%B1%D8%B3/id1321185983?ls=1">
                            <img src="{{url('/assets/images/apple-store-logo-png-7.png')}}"> </a>
                        <a href="https://play.google.com/store/apps/details?id=com.madarex2" target="_blank"> <img
                                src="{{url('/assets/images/google-play-store.png')}}"> </a>
                    </div>
                </div> <!-- col-lg-4 -->
                <div class="col-lg-3 contact">
                    <h2> تواصل معنا </h2>
                    <p> <i class="fa fa-phone"></i> {!!getSettingValue('phone') !!} </p>
                    <p> <i class="fa fa-envelope"></i> info@madarex.sa</p>
                    <div class="icons">
                        <a class="" href="https://www.facebook.com/MadarExpress/" target="_blank">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a class="" href="https://www.youtube.com/watch?v=QoFk472vF-Y" target="_blank">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a class="" href="https://twitter.com/madar_ex" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>

            </div>

        </div>


    </div>

    <p class="copyright"> جميع الحقوق محفوظة - تصميم <a href="https://loc.sa/" target="_blank"> مدار الرياده لتقنيه المعلومات  </a> </p>
    <!---- start-smoth-scrolling---->
    <script src="{{url('/assets/js/jquery.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script type="text/javascript" src="{{url('/assets/js/move-top.js')}}"></script>
    <script type="text/javascript" src="{{url('/assets/js/easing.js')}}"></script>
    <script src="{{url('/assets/js/owl.carousel.js')}}"></script>
    <script src="/js/jquery.validate.min.js"></script>
<script src="/js/messages_ar.js"></script>

    <script type="text/javascript">
        $('#contact-form').validate({
            rules:{
                name:{
                    required:true,
                },
                phone:{
                    required:true,
                    // matches: "[0-9\-\(\)\s]+",
                    minlength:9,
                    maxlength:14
                },
                city:{
                    required:true,
                },
                organization_name:{
                    required:true,
                },
                email:{
                    required:true,
                    email:true
                }
            }
        });
        jQuery(document).ready(function ($) {
            $(".scroll").click(function (event) {
                event.preventDefault();
                $('html,body').animate({
                    scrollTop: $(this.hash).offset().top
                }, 1000);
            });
        });

    </script>
    <script>
        $(document).ready(function () {
            $("#owl-demo").owlCarousel({
                items: 3,
                lazyLoad: true,
                autoPlay: true
            });
        });

    </script>
    <script>
        $(document).ready(function () {
            $("#owl-demo2").owlCarousel({
                items: 5,
                lazyLoad: true,
                autoPlay: true,
                pagination: false,
            });
        });

    </script>
    <script>
        $(document).ready(function () {
            $("#loading-screen").fadeOut();
        });

    </script>
    <script>
        $(function () {
            var pull = $('#pull');
            menu = $('nav ul');
            menuHeight = menu.height();
            $(pull).on('click', function (e) {
                e.preventDefault();
                menu.slideToggle();
            });
            $(window).resize(function () {
                var w = $(window).width();
                if (w > 320 && menu.is(':hidden')) {
                    menu.removeAttr('style');
                }
            });
        });

    </script>
    <script>
        new WOW().init();

    </script>


    <script>
        $(document).ready(function () {
            // Add minus icon for collapse element which is open by default
            $(".collapse.in").each(function () {
                $(this)
                    .siblings(".panel-heading")
                    .find("span.fa")
                    .addClass("rotate");
            });

            // Toggle plus minus icon on show hide of collapse element
            $(".collapse")
                .on("show.bs.collapse", function () {
                    $(this)
                        .parent()
                        .find("span.fa")
                        .addClass("rotate");
                })
                .on("hide.bs.collapse", function () {
                    $(this)
                        .parent()
                        .find("span.fa")
                        .removeClass("rotate");
                });
        });
        //$(document).on('click', '#follow', function () {
            //var orderStatus = $('#orderStatus').val()
           // $.get('{{url("get-order-status")}}/' + orderStatus).done(function (data) {
              //  if (data['code'] == 200) {

                //    $('#order-response').html(`
               // <div class="col-lg-12 text-center">
                 //   <div class="one-state done">
                   //     <i class="fa fa-close-o">X</i>
                    //    <h4> ` + data['message'] + `</h4>
                   // </div>
                   // `);
               // } else {
                 //   $('#order-response').html(`
                // <div class="col-lg-12 text-center">
                 //   <div class="one-state done">
                      //  <i class="fa fa-check"></i>
                      //  <h4> ` + data['message'] + `</h4>
                   // </div>
                  //  `);
               // }
               // console.log(data);
           // });
      //  });

      $(document).on('click','#follow',function(){
            var orderStatus = $('#orderStatus').val()
            var orderStore = $('#orderStore').val()
            $.get('{{url("get-order-status")}}/'+orderStatus + '?store=' + orderStore).done(function(data){
                if(data['code'] == 200)
                {

                    $('#showState').html(data['html']);
                }else{
                    $('#showState').html(data['message']);
                }
                console.log(data);
            });
          });






        /////////////////////////////contact message..re////////////////////////
        $(document).on('submit', '#contact-form', function (e) {
            e.preventDefault()
            var data = $(this).serialize();
                    $('#contact-form input[type=submit]').attr('disabled', true);
                    $('#contact-form input[type=submit]').val('يرجي الانتظار جاري ارسال الرسالة');
            $.post( $(this).attr('action'), data ).done(function (data) {
                if (data['code'] == 200) {
                    $('#contact-message').show();
                    $('#contact-form input[type=submit]').attr('disabled', false);
                    $('#contact-form input[type=submit]').val('ارسال');
                    $('#contact-form input[type=text]').val('');
                    setTimeout(function(){
                        $('#contact-message').hide();
                    },15000);
                } else {

                }
                console.log(data);
            });
        });

    </script>




</body>

</html>
