<html>

<head>
    <title> مدار اكسبريس | للشحن والتغليف</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css" />
    <link href="{{url('/assets/css/owl.carousel.css')}}" rel="stylesheet">
    <link href="{{url('/assets/css/bootstrap.css')}}" rel='stylesheet' type='text/css' />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css" rel='stylesheet'
        type='text/css' />
    <link href="{{url('/assets/css/style.css')}}" rel='stylesheet' type='text/css' />
    <link href="{{url('/assets/css/mt-style.css')}}" rel='stylesheet' type='text/css' />
    <link href="{{url('/css/madarCstom.css')}}" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
        <style type="text/css">
            #mapCanvas{
                width: 100%;
                height: 300px;
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

    <div class="header-section">

        <div id="home" class="header">
            <div class="container">
                <div class="top-header register-v">
                    <div class="logo">
                        <a href="#">
                            <img src="https://madarex.sa/assets/images/logo.png" title="logo">
                        </a>
                    </div>

                    <a href="/" class="home-link">الرئيسية </a>
                </div>
            </div>
        </div>


        <div class="clearfix"></div>
    </div>
    <div class="reg-page">
        <div class="container">

            <div class="flex-row">

                <div class="col-xs-12 col-lg-8">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                        {!!Form::open( ['url' => route('company-register') ,'method' => 'Post','files' => true, 'class' => 'form-style']) !!}
                        <h3> انشاء حساب</h3>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label class=""> اسم المتجر </label>
                                <input class="form-control" required="" name="name" type="text">
                            </div>
                            <div class="form-group col-md-6">
                                <label class=""> رقم الجوال </label>
                                <input class="form-control" required="" name="phone" type="tel">
                            </div>
                            <div class="form-group col-md-6">
                                <label class=""> البريد الالكترونى </label>
                                <input class="form-control" required="" name="email" type="email">
                            </div>
                            <div class="form-group col-md-6">
                                <label class=""> كلمة المرور </label>
                                <input class="form-control" required="" name="password" type="password">
                            </div>
                            <div class="form-group col-md-6">
                                <label class=""> تأكيد كلمه المرور</label>
                                <input class="form-control" required="" name="password_confirmation" type="password">
                            </div>
                            <div class="form-group col-md-6">
                                <label class=""> السجل التجارى </label>
                                <input class="form-control" required="" name="commercial_record" type="text">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="">المدينة </label>
                                <div class="">
                                    {!! Form::select("city_id",TheCityP(),null,['class'=>"form-control select2 ", "autocomplete"=>
                                    'off', ])!!}
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label class=""> العنوان كامل </label>
                                <input class="form-control" required="" name="adress_details" type="text">
                            </div>
                            <div class="form-group col-md-12">
                                <label class=" "> الموقع علي الخريطة </label>
                                <div class="map-content ">
                                    <div style="margin-top: 8px">
                                        {!! Form::text("address",null,['class'=>'form-control', 'id' => 'autocomplete'])!!}
                                    </div>

                                    <div id="mapCanvas"></div>

                                    <div id="infoPanel" style="display: none;">
                                        <b>Marker status:</b>
                                        <div id="markerStatus"><i>Click and drag the marker.</i></div>
                                        <b>Current position:</b>
                                        <div id="info"></div>
                                        <b>Closest matching address:</b>
                                        <div id="address"></div>
                                    </div>
                                    {!! Form::hidden("latitude",null,['class'=>'form-control', 'id' => 'lat'])!!}
                                    {!! Form::hidden("longitude",null,['class'=>'form-control', 'id' => 'lng'])!!}
                                </div>
                            </div>
                            <div class="text-center">
                                <button class="btn  btn-submit" type="submit" style="font-size: 20px;"> انشاء حساب
                                </button>
                            </div>




                        </div>

                        {!!Form::close() !!}
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="img-wr">
                        <img src="/assets/images/slider-img.png" alt="">
                    </div>
                </div>

            </div>

        </div>
    </div>
    {{-- <div id="fea" class="features">
        <div class="container">
            <div class="section-head text-center">
                <h3>
                    <span class="frist"></span>
                     انشاء حساب   <span class="second"></span>
                </h3>
            </div>
            <!----features-grids----->
            <div class="features-grids">
                <div class="col-md-12">
                    {!!Form::open( ['url' => route('company-register') ,'method' => 'Post','files' => true]) !!}
                    <div class="col-sm-12">
                        <div class="card-box">

                            <div class="row">
                                <div class="col-lg-12">

                                    @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class=""> اسم المتجر * </label>
                                                <div class="">
                                                    {!! Form::text("name",null,['class'=>'form-control', 'required' => ''])!!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class=""> رقم الجوال * </label>
                                                <div class="">
                                                    {!! Form::text("phone",null,['class'=>'form-control', 'placeholder' => "",
                                                    'required' => ''])!!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="">البريد الالكترونى <span>*</span></label>
                                                <div class="">
                                                    {!! Form::email("email",null,['class'=>'form-control',
                                                    'required' => '' ])!!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="">كلمة المرور <span>*</span></label>
                                                <div class="">
                                                    {!! Form::password("password",['class'=>'form-control',
                                                    'required' => ''
                                                    ])!!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class=""> تأكيد كلمه المرور <span></span></label>
                                                <div class="">
                                                    {!! Form::password("password_confirmation",['class'=>'form-control', 'required' => '' ])!!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="">المدينة *</label>
                                                <div class="">
                                                    {!! Form::select("city_id",TheCityP(),null,['class'=>"form-control select2 ", "autocomplete"=>
                                                    'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="">العنوان كامل *</label>
                                                <div class="">
                                                    {!! Form::text("adress_details",null,['class'=>"form-control select2 ", "autocomplete"=>
                                                    'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class=""> السجل التجارى * </label>
                                                <div class="">
                                                    {!! Form::text("commercial_record",null,['class'=>'form-control', 'placeholder' => "",
                                                    'required' => ''])!!}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class=" "> الموقع علي الخريطة </label>
                                                <div class="map-content ">
                                                    <div style="margin-top: 8px">
                                                        {!! Form::text("address",null,['class'=>'form-control', 'id' => 'autocomplete'])!!}
                                                    </div>

                                                    <div id="mapCanvas"></div>

                                                    <div id="infoPanel" style="display: none;">
                                                        <b>Marker status:</b>
                                                        <div id="markerStatus"><i>Click and drag the marker.</i></div>
                                                        <b>Current position:</b>
                                                        <div id="info"></div>
                                                        <b>Closest matching address:</b>
                                                        <div id="address"></div>
                                                    </div>
                                                    {!! Form::hidden("latitude",null,['class'=>'form-control', 'id' => 'lat'])!!}
                                                    {!! Form::hidden("longitude",null,['class'=>'form-control', 'id' => 'lng'])!!}
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="clear-fix">
                    </div>

                    <div class="card-box">
                        <div class="row">


                            <div class="clearfix"></div>

                            <div class="text-center">
                                <button class="btn btn-warning waves-effect waves-light btn-submit" type="submit" style="font-size: 20px;"> انشاء حساب </button>
                                </br>
                                </br>
                            </div>
                        </div>

                    </div>
                    </div>

                {!!Form::close() !!}
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <!---728x90--->

        <div class="clearfix"></div>
    </div> --}}







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
                    <p> <i class="fa fa-phone"></i> 920020804 </p>
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
    <script type="text/javascript">
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
            $.get('{{url("get-order-status")}}/'+orderStatus).done(function(data){
                if(data['code'] == 200)
                {

                    $('#order-response').html(data['html']);
                }else{
                    $('#order-response').html(data['message']);
                }
                console.log(data);
            });
          });






        /////////////////////////////contact message..re////////////////////////
        $(document).on('submit', '#contact-form', function (e) {
            e.preventDefault()
            var data = $(this).serialize();
            $.post( $(this).attr('action'), data ).done(function (data) {
                if (data['code'] == 200) {
                    $('#contact-message').show();
                    setTimeout(function(){
                        $('#contact-message').hide();
                    },5000);
                } else {

                }
                console.log(data);
            });
        });

    </script>


<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyCob-v1r_8bpOUPIm4TiaxrD3roc_XLFFo&language=ar"></script>
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

        function initialize() {
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
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>

</body>

</html>
