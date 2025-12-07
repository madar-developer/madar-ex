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
            width: 23.3%;
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

    div{
        box-sizing: border-box;
    }
</style>
</head>
<body>
    <htmlpageheader name="page-header">
    </htmlpageheader>
<div style="width: 100%; height:6rem; margin-top:5px;">
    <div style="width: 25%; float:left; border: 1px solid; height:6rem; text-align:left;">
        <br>
         <img src="{{ public_path('/adminto/assets/images/logo.png')}}" style="width: 90%;padding-left:5px;"/>
    </div>
    <div style="width: 74%; float:left; border: 1px solid; height:6rem; text-align:center;">
        <br>
        @php
            echo '<img
                src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->id, 'C39') . '"
                alt="barcode" style="width: 70%; height:3rem; " />';
            @endphp
            <br />
            @php
            $len = strlen($order->id);
            $full = 15;
            $rem = 0;
            if ($full > $len) {
            $rem = $full - $len;
            }
        @endphp
        @for ($i = 0; $i < $rem; $i++) 0 @endfor {{$order->id}}
    </div>
</div>

<div style="width: 100%; height:8rem; text-align:left;">
    <div style="width: 24%; float:left; border: 1px solid; height:8rem;">
        <label for="" style="padding-left:15px;">From:</label> <br/>
        <strong style="padding-left:15px;">
             {{$order->Company()->first() ? ($order->Company()->first()->City->name ?? '') : ''}}
        </strong>
    </div>
    <div style="width: 24%; float:left; border: 1px solid; height:8rem;">
        <label for="" style="padding-left:15px;">To:</label> <br/>
        <strong style="padding-left:15px;">
             {{$order->City->name ?? ''}}
        </strong>
    </div>
    <div style="width: 32%; float:left; border: 1px solid; height:8rem;">
        <p style="padding-left:5px;"> Date : <strong>{{$order->created_at->todatestring() ?? ''}}</strong> </p>
        <p style="padding-left:5px;"> Refrence no: <strong>{{$order->refrence_no ?? ''}}</strong> </p>
        <p style="padding-left:5px;"> Order no: <strong>{{$order->id ?? ''}}</strong> </p>
    </div>
    <div style="width: 18%; float:left; border: 1px solid; height:8rem;">
        <label for="">COD:</label> <br/>
        <strong>
            {{$order->price ?? ''}}
            @if ( str_contains(url('/'), 'madarex.sa') )
                                                ر.س
                                                @else
                                                ريال
                                                @endif
        </strong>
    </div>
</div>

<div style="width: 100%; height:23.3rem; text-align:left;">
    <div style="width: 19%; float:left; border: 1px solid; height:23.3rem;">
        <div style="width:100%;">
            <table>
                <tr>
                    <td text-rotate="90">
                        <div style=" position:fixed;  -webkit-transform: rotate(90deg);transform: rotate(90deg); rotate:90;">
                            {{-- @php
                                echo '<img
                                src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->id, 'C39') . '"
                                alt="barcode" style="width: 70%; height:1rem;" />';
                                @endphp
                                <br> --}}
                            @php
                            $len = strlen($order->id);
                            $full = 15;
                            $rem = 0;
                            if ($full > $len) {
                                $rem = $full - $len;
                            }
                            @endphp
                            @for ($i = 0; $i < $rem; $i++) 0 @endfor {{$order->id}}
                        </td>

                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div style="width: 80%; float:left; border: 1px solid; height:23.3rem;">
        <div style="width:100%; border: 1px solid;overflow: auto;">
            <div style="width:100%; padding:left:15px;">
                <p style="padding-left:5px;">Account: <strong>{{$order->Company->id ?? ''}}</strong> </p>
                <p style="padding-left:5px;"> <strong>Madar Al-Riyadah</strong> </p>
                <p style="padding-left:5px;"> <strong>{{$order->Company->name ?? ''}}</strong> </p>
                <p style="padding-left:5px;"> <strong>{{$order->Company->adress_details ?? ''}}</strong> </p>
                <p style="padding-left:5px;"> <strong>{{$order->Company->City->name ?? ''}}</strong> </p>
            </div>
            <div style="width:50%; float:left;">
                <label for="">@if ( str_contains(url('/'), 'madarex.sa') )
                    المملكة العربية السعودية
                    @else
                    مصر
                    @endif</label>
            </div>
            <div style="width:50%; float:left;">
                <label for="">{{$order->Company->phone ?? ''}}</label>
            </div>
        </div>
        <div style="width:100%; border: 1px solid;overflow: auto;">
            <div style="width:100%;">
                <p style="padding-left:5px;"> <strong>Individual</strong> </p>
                <p style="padding-left:5px;"> <strong>{{$order->recipent_name}}</strong> </p>
                <p style="padding-left:5px;"> <strong>{{$order->adress_details}}</strong> </p>
                <p style="padding-left:5px;"> <strong>{{$order->City->name ?? ''}}</strong> </p>
                <p style="padding-left:5px;"> <strong>@if ( str_contains(url('/'), 'madarex.sa') )
                    المملكة العربية السعودية
                    @else
                    مصر
                    @endif</strong> </p>
            </div>
            <div style="width:50%; float:left;">
                <label for="" style="padding-left:5px;">{{$order->phone}}</label>
            </div>
            <div style="width:50%; float:left;">
                <label for="" style="padding-left:5px;">{{$order->phone}}</label>
            </div>
        </div>
    </div>
</div>

<div style="width: 100%; border: 1px solid; height:3rem; text-align:left;">
    <div style="width:100%;">
        <h4 style="padding-left: 5px;">Description: {{$order->description}}</h4>
    </div>

</div>
<htmlpagefooter name="page-footer">
</htmlpagefooter>
</body>
</html>
