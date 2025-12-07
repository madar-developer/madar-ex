<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="/css/style.css">
    <style>
        svg{
            margin: auto;
        }
    </style>
    <title>bill</title>
</head>

@if (isset($show))
<body >

    @else
    <body onload="window.print()">

@endif
    <!---- header------------------>


    <div class="page">
        <div class=" top-pic" style="text-align: center;">
            @php
               /* echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->serial, 'C39') . '" style="width:50%;" alt="barcode"
                     />';*/
            
            //echo DNS1D::getBarcodeHTML('4445645656', 'C128');
            echo '<img
                src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->serial, 'C128B',2,65,array(1,1,1), true) . '"
                alt="barcode" style="width: 70%;  " />';
            @endphp
                <br />
                <span>

                    @php
                    $len = strlen($order->serial);
                    $full = 15;
                    $rem = 0;
                    if ($full > $len) {
                        $rem = $full - $len;
                    }
                    @endphp
                     {{$order->serial}}
                </span>
        </div>
        <div class="flex">
            <div class="w33 p4">
                <img src="{{ url('/adminto/assets/images/logo.png')}}" alt="">
            </div>
            <div class="w33 p4 ">
                <span class="lbl">origin :</span>
                <br>
                <span class="val"> {{$order->Company()->first() ? ($order->Company()->first()->City->name ?? '') : ''}}</span>
            </div>
            <div class="w33 p4">
                <span class="lbl">destination :</span>
                <br>
                <span class="val"> {{$order->City->name ?? ''}}</span>
            </div>


        </div>
        <div class="flex row3">


            <div class="w50">
                <span class="lbl">Service :</span>
                <span class="val"> {{$order->PaymentMethod->name ?? ''}}</span>
            </div>
            <div class="w50">
                <span class="lbl">COD Amount :</span>
                <span class="lbl"> {{$order->price ?? ''}} @if ( str_contains(url('/'), 'madarex.sa') ) SAR @else EGP @endif</span>

            </div>
        </div>
        <div class="flex row3">


            <div class="w50">
                <span class="lbl"> Date :</span>
                <span class="val"> {{$order->created_at->todatestring() ?? ''}} </span>
            </div>
            <div class="w50">
                <span class="lbl">Ref : </span>
                <span class="lbl"> {{$order->refrence_no ?? ''}}</span>

            </div>
        </div>
        <div class="flex row3">


            <div class="w50">
                <div>
                    <span class="lbl"> Items :</span>
                    <span class="val"> {{$order->packages_number}}</span>
                </div>
                <div>
                    <span class="lbl"> Description : </span>
                    <span class="val"> {{$order->description}} </span>
                </div>

            </div>
            <div class="w50">
                <span class="lbl">Weight :</span>
                <span class="val"> --</span>

            </div>
        </div>
        <div class="row4">
            <div class="w75">
                <div class="bb">
                    <span class="lbl"> From: </span>
                    <span class="val"> {{$order->Company()->first() ? ($order->Company()->first()->name ?? '') : ''}} - {{$order->Company()->first() ? ($order->Company()->first()->City->name ?? '') : ''}}</span>
                </div>
                <div>
                    <span class="lbl"> to : </span>
                    <span class="val">{{$order->recipent_name}}</span>
                    <br>
                    <span class="lbl"> phone : </span>

                    <span class="val"> {{$order->phone}}</span>
                    <br>
                    &nbsp;,&nbsp; {{\Str::limit($order->adress_details,70)}}


                </div>

            </div>
            <div class="w25">
                {!! DNS2D::getBarcodeSVG($order->serial.'', 'QRCODE',4,4) !!}
            </div>

        </div>
        {{-- <div class="top-pic v2" style="text-align: center;">
            @php
                /* echo '<img src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->serial, 'C39') . '" alt="barcode"
                    style="" />'; */
                @endphp
                <br />
                <span>
                    @php
                    $len = strlen($order->serial);
                    $full = 15;
                    $rem = 0;
                    if ($full > $len) {
                    $rem = $full - $len;
                    }
                    @endphp
                     {{$order->serial}}

                </span>
        </div> --}}





    </div>




</body>

</html>
