<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>  Madar Express {{\Carbon\Carbon::now()->toDateString()}}</title>

    <link href="{{ public_path('/adminto/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
	<style>
        @page {
            header: page-header;
            footer: page-footer;
            margin: 100px 0px;
        }
                    table {
        border-collapse: collapse;
        width: 100%;
        }

        th, td {
        text-align: right;
        padding: 8px;
        }
        tr:nth-child(even) {background-color: #f2f2f2;}
        html, body {
                background-color: #fff;
                color: #000000;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
                text-align: right;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }
        #logo{
            width: 25%;
            height:75px;
        }
        .cvv{
            width:100%; text-align: center;
            height:90px;
        }
        .cvv2{
            float: right;padding-top: 15px;font-size: 18px;
            top: 30px;
        }
        .cvv3{
            width:100%;
            margin-bottom: 100px;
            background-color: #d5f0f5;
        }
        .file-title{
            width: 100%;
            text-align: center;
            margin: 20px;
        }
</style>
@yield('css')
</head>
<body>
    <htmlpageheader name="page-header">
        <div class="cvv3">
            <div class="cvv">
                    <img id="logo" src="{{ public_path('/adminto/assets/images/logo.png')}}" width="120"/>
            </div>
        </div>
    </htmlpageheader>
        @yield('content')

    <htmlpagefooter name="page-footer">
           رقم الصفحة {PAGENO}
        <div style="text-align:left;">{{\Carbon\Carbon::now()->toDateString()}}</div>
    </htmlpagefooter>
</body>
</html>
