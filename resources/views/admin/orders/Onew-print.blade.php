<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> Madar Express {{\Carbon\Carbon::now()->toDateString()}}</title>

    <link href="{{ asset('/adminto/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        body{
            text-align: center;
        }
        .border{
            border: 1px solid #000;
        }
        .border-right{
            border-right: 1px solid #000;
            height: 100%;
        }
        @media (min-width: 1200px)
        {
            .container {
                width: 800px;
            }

        }

@media (min-width: 992px)
{
    .container {
        width: 800px;
    }

}
    </style>
</head>

<body>
    <div class="container">
        <div class="row">

            <div class="col-md-12 border">
                <br>
                @php
                echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->id, 'C39') . '" alt="barcode"
                    style="width: 70%; height:3rem; " />';
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
            <div class="col-md-12 border">
                    <div class="col-md-4 border-right">
                        <img src="{{ url('/adminto/assets/images/logo.png')}}" style="width: 90%;height: 6rem;padding: 10px;" />
                    </div>
                    <div  class="col-md-4 border-right" style="height: 6rem;" >
                        <label for="" style="padding-left:15px;">Origin:</label> <br />
                        <strong style="padding-left:15px;">
                            {{$order->Company()->first() ? ($order->Company()->first()->City->name ?? '') : ''}}
                        </strong>
                    </div>
                    <div  class="col-md-4 ">
                        <label for="" style="padding-left:15px;">Destination:</label> <br />
                        <strong style="padding-left:15px;">
                            {{$order->City->name ?? ''}}
                        </strong>
                    </div>
            </div>
            <div class="col-md-12 border" style="text-align: left;">
                <div class="col-md-6"  style="margin:15px 0;">
                    <strong style="direction: ltr; ">
                        {{-- <br> --}}
                         Service : {{$order->PaymentMethod->name ?? ''}}
                    </strong>
                </div>
                <div class="col-md-6"  style="margin:15px 0;">
                    <strong style="direction: ltr;">
                        {{-- <br> --}}
                        COD Amount : {{$order->price ?? ''}} @if ( str_contains(url('/'), 'madarex.sa') ) RS @else EGP @endif
                    </strong>

                </div>
            </div>
            <div class="col-md-12 border"  style="text-align: left;">
                <div  class="col-md-6" style="margin:15px 0; padding:0;">
                    {{-- <br> --}}
                    <strong >
                        &nbsp;&nbsp; Date : {{$order->created_at->todatestring() ?? ''}}
                    </strong>
                </div>
                <div  class="col-md-6" style="margin:15px 0;">
                    {{-- <br> --}}
                    <strong >
                        Ref : {{$order->refrence_no ?? ''}}
                    </strong>

                </div>
            </div>
            <div  class="col-md-12 border"  style="text-align: left;">
                <div  class="col-md-6" style="margin:15px 0;">
                    <strong>
                       Items : {{$order->packages_number}}
                    </strong>
                    {{-- <br> --}}
                    <strong>
                       Description : {{$order->description}}
                    </strong>
                </div>
                <div  class="col-md-6" style="margin:15px 0;">
                    {{-- <br> --}}
                    <strong>
                        Weight :
                    </strong>

                </div>
            </div>
            <div  class="col-md-12 border"  style="text-align: left; padding: 0;">
                <div  class="col-md-8 border-right" style="padding: 0;">
                    <div  class="col-md-12" style="padding: 15px 30px; border-bottom: 2px solid #000; ">
                        <p><strong>
                            From: <br>
                                {{$order->Company()->first() ? ($order->Company()->first()->name ?? '') : ''}}
                            </strong> -
                            {{$order->Company()->first() ? ($order->Company()->first()->City->name ?? '') : ''}}</p>
                        <br />
                    </div>
                    <div  class="col-md-12" style="padding: 15px 30px; ">
                        <p><strong>
                            To: &nbsp;&nbsp;{{$order->recipent_name}}
                                <br>
                                @if ($order->phone)

                                Phone: {{$order->phone}}
                                @endif
                            </strong>
                            &nbsp;&nbsp; {{$order->adress_details ?? ''}}
                        </p>
                    </div>
                </div>
                <div  class="col-md-4" style="text-align: center;">
                    {{-- quad code --}}
                    {!! DNS2D::getBarcodeSVG($order->id.'', 'QRCODE',7,7) !!}
                </div>
            </div>
            <div  class="col-md-12 border">
                <br>
                @php
                echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->id, 'C39') . '" alt="barcode"
                    style="width: 70%; height:3rem; " />';
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
    </div>
</body>
</html>
