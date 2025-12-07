<!DOCTYPE html>
<html lang="en" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App Favicon -->
        <link rel="shortcut icon" href="{{asset('/assets/images/madar-logo-dark.png')}}">

        <!-- App title -->
        <title>Madar Express | Login</title>

        <!-- App CSS -->
        <link href="{{asset('/adminto/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/adminto/assets/css/core.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/adminto/assets/css/components.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/adminto/assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/adminto/assets/css/pages.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/adminto/assets/css/menu.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/adminto/assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="{{asset('/adminto/assets/js/modernizr.min.js')}}"></script>
        
        <!-- cusotm style -->
        <link href="{{asset('/adminto/assets/css/my-style.css')}}" rel="stylesheet" type="text/css" />

    </head>
    <body>

        <div class="account-pages"></div>
        <div class="overlay"></div>
        <div class="clearfix"></div>
        <div class="login-page">
            <div class="wrapper-page">
            	<div class="card-box">

                    <div class="card-header text-center">
                        <img style="margin: auto;" class="img-responsive" src="{{asset('/adminto/assets/images/logo.png')}}">
                    </div>
 

                    <div class="panel-body">
                        <form class="form-horizontal m-t-20" action="" method="post">
                            @csrf

                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" required="" placeholder="الايميل او رقم الجوال   ">
                                    
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" required="" placeholder="كلمة المرور ">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                </div>
                            </div>


                            <div class="form-group text-center m-t-30">
                                <div class="col-xs-12">
                                    <button class="btn btn-login btn-bordred btn-block waves-effect waves-light" type="submit"> تسجيل دخول </button>
                                </div>
                            </div>
                            <div class="card-bottom">
                                
                                <a href="{{url('/')}}"> الرئيسية </a>
                            </div>

                        </form>
                    </div> <!-- panel-body -->

                </div>
                <!-- end card-box-->
                
            </div>
            <!-- end wrapper page -->
        </div>
        

        
    	<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="{{asset('/adminto/assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/bootstrap-rtl.min.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/detect.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/fastclick.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/jquery.slimscroll.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/jquery.blockUI.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/waves.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/wow.min.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/jquery.nicescroll.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/jquery.scrollTo.min.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('/adminto/assets/js/jquery.core.js')}}"></script>
        <script src="{{asset('/adminto/assets/js/jquery.app.js')}}"></script>
	
	</body>
</html>



