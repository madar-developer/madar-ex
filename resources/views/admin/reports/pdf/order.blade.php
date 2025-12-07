<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="ltr">
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
</style>
</head>
<body>
<div style="width: 100%; border-right:0px solid #000; height:6rem; border: 1px solid; height:6rem; text-align:center;">
    <br> 
            @php
            /* echo '<img
                src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->id, 'C39E',2,55,array(1,1,1), true) . '"
                alt="barcode" style="width: 50%;  " />';*/
                
            echo '<img
                src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->serial, 'C128B',2,55,array(1,1,1), true) . '"
                alt="barcode" style="width: 70%;  " />';
            @endphp
            <br />
            @php
                $len = strlen($order->serial);
                $full = 15;
                $rem = 0;
                if ($full > $len) {
                    $rem = $full - $len;
                }
            @endphp
        {{$order->serial}}
</div>

<div style="width: 100%;  height:4rem; text-align:center; border-right: 1px solid #000;">
    <div style="width: 32%; float:left; border: 1px solid; height:4rem;">
        <img src="{{ public_path('/adminto/assets/images/logo.png')}}" style="width: 90%;"/>
    </div>
    <div style="width: 33%; float:left; border: 1px solid; height:4rem;">
        <label for="" style="padding-left:15px;">Origin:</label> <br/>
        <strong style="padding-left:15px;">
             {{$order->Company()->first() ? ($order->Company()->first()->City->name ?? '') : ''}}
        </strong>
    </div>
    <div style="width: 33.65%; float:left; border: 1px solid; height:4rem;border-right:0;">
        <label for="" style="padding-left:15px;">Destination:</label> <br/>
        <strong style="padding-left:15px;">
             {{$order->City->name ?? ''}}
        </strong>
    </div>
</div>
<div style="width: 100%;  height:4rem; text-align:left; border-right: 1px solid #000;">
    <div style="width: 49.2%; float:left; border: 1px solid; height:4rem; border-right:0; border-top:0;">
        <div style="direction: ltr;">
            <br>
            <strong>&nbsp;&nbsp; Service :</strong>  {{$order->PaymentMethod->name ?? ''}}
        </div>
    </div>
    <div style="width: 50%; float:left; border: 1px solid; height:4rem;  border-left:0; border-right:0; border-top:0;">
        <strong style="padding-left:15px; direction: ltr;">
            <br>
             COD Amount : {{$order->price ?? ''}} @if ( str_contains(url('/'), 'madarex.sa') )  SAR  @else EGP  @endif
        </strong>

    </div>
</div>
<div style="width: 100%;  height:4rem; text-align:left;border-right: 1px solid #000;">
    <div style="width: 49.2%; float:left; border: 1px solid; height:4rem; border-right:0;">
        <br>
        <strong style="padding-left:15px;">
            &nbsp;&nbsp; Date : {{$order->created_at->todatestring() ?? ''}}
        </strong>
    </div>
    <div style="width: 50%; float:left; border: 1px solid; height:4rem; border-left:0;border-right:0; border-top:0;">
        <br>
        <strong style="padding-left:15px;">
             Ref : {{$order->refrence_no ?? ''}}
        </strong>

    </div>
</div>
<div style="width: 100%;  height:5.5rem; text-align:left; border-right: 1px solid #000;">
    {{-- <div style="width: 49.2%; float:left; border: 1px solid; height:4rem; border-right:0;"> --}}
    <div style="width: 99.2%; float:left; border: 1px solid; height:5.5rem; border-right:0;">
        <strong style="padding-top:15px;">
            &nbsp;&nbsp; Items : {{$order->packages_number}}
        </strong>
        <br>
        <div style="padding-left:15px;  font-size: 10pt; ">
           <strong>Description :</strong>  {{\Str::limit($order->description,115)}}
        </div>
    </div>
    {{-- <div style="width: 50%; float:left; border: 1px solid; height:4rem; border-left:0;border-right:0; border-top:0;">
        <br>
        <strong style="padding-left:15px;">
             Weight :
        </strong>

    </div> --}}
</div>
<div style="width: 100%;  height:8.5rem; text-align:left; border-right: 1px solid;">
    <div style="width: 70%; float:left; border-right: 1px solid; height:8.5rem;">
        <div style="width: 99.5%; float:left; border-bottom: 1px solid; height:3rem;">
            <p><strong style="padding-left:15px;">
                &nbsp;&nbsp;From: &nbsp;&nbsp;{{$order->Company()->first() ? ($order->Company()->first()->name ?? '') : ''}}
            </strong> -
            &nbsp;&nbsp;{{$order->Company()->first() ? ($order->Company()->first()->City->name ?? '') : ''}}</p> <br/>
        </div>
        <div style="width: 99.5%; float:left;  height:5rem;">
            <p><strong style="padding-left:15px;">
                &nbsp;&nbsp;To: &nbsp;,&nbsp;{{$order->recipent_name}}
                @if ($order->phone)
                &nbsp;&nbsp; Phone: {{$order->phone}}
                @endif
            </strong>
              &nbsp;,&nbsp; {{\Str::limit($order->adress_details,70)}}
            </p>
        </div>
    </div>
    <div style="width: 29.1%; text-align:center; border: 1px solid; border-right:0; height:8.5rem; padding-top:5px; ">
        {{-- quad code --}}
        {!! str_replace('< version="1.0" standalone="no"?>','',str_replace('?xml', '',DNS2D::getBarcodeSVG($order->serial.'', 'QRCODE',5,5))) !!}
    </div>
</div>
{{-- <div style="width: 100%; border-right:0px solid #000; height:6rem; border: 1px solid; height:6rem; text-align:center;">
    <br>
    @php
    /*echo '<img
        src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->id, 'C39') . '"
        alt="barcode" style="width: 70%; height:3rem; " />';*/
    @endphp
    <br />
    @php
        $len = strlen($order->serial);
        $full = 15;
        $rem = 0;
        if ($full > $len) {
            $rem = $full - $len;
        }
    @endphp
    {{$order->serial}}
</div> --}}

</body>
</html>
