<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        body {
            font-family: sans-serif;
            text-align: right;
        }

        table {
            border-collapse: collapse;
            border: 1px solid black;
        }


        td,
        th {

            text-align: left;
            padding: 7px;
            border-bottom: 1px solid black;
        }

    </style>
</head>

<body>
    <htmlpageheader name="page-header">

    </htmlpageheader>
    <div class="card-box">

        <div class="row">
            <div class="" style="padding: 20px;direction: ltr;height: auto;" id="DivIdToPrint">
                <div class="row">
                    <div class="col-md-12" style="">
                        <table style="width: 100%; border: 0 !important; direction:rtl;">
                            <tr>
                                <td style=" text-align: right; ">

                                    <table style="border: 0 !important;width: 18rem; ">
                                        <tr>
                                            <td style="border: 0 !important; width: 9rem; text-align: center;"
                                                colspan="2">

                                                <img class="img-responsive" style="margin: auto;"
                                                    src="{{ public_path('/adminto/assets/images/logo.png')}}"
                                                    width="250" height="70">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td
                                                style="border: 0 !important;background-color: orange; width: 9rem; text-align: left;">
                                                الاجمالي
                                                <br />
                                                Tolal
                                            </td>
                                            <td
                                                style="border: 0 !important;background-color: #cecece;width: 9rem;border-radius: 20px 0px 0 20px; text-align: right;">
                                                {{$order->price}}
                                                @if ( str_contains(url('/'), 'madarex.sa') )
                                                ر.س
                                                @else
                                                ريال
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td colspan="5"></td>
                                <td>

                                    <table class="table tab1" style="border: 0 !important; direction: ltr; text-align: left; width: 100%;  ">
                                        <tr>
                                            <td
                                                style="border: 0 !important;border-radius: 20px 0px 0 20px; padding-left:20px;">
                                                @php
                                                echo '<img
                                                    src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->id, 'C39') . '"
                                                    alt="barcode" />';
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
                                            </td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                                    <table style="width:100%; hight:100px; border-top: 0 !important;">

                                        <tr>
                                            <td class="xc"
                                                style="text-align: left; border: 1px solid #000; background-color: #000; color: #fff;width: 2rem;"
                                                rowspan="5">
                                                <a style="    writing-mode: tb-rl; transform: rotate(180deg);">بيانات
                                                    الارسال</a> </td>

                                            <td
                                                style="text-align: left; border: 1px solid #000; background-color: #cecece;">
                                                Customer
                                                No:
                                            </td>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>
                                            <td style="border: 0;"></td>

                                        </tr>

                                        <tr>
                                            <td style="text-align: left;">Revicer Name :</td>
                                            <td colspan="5" style="text-align:center;">
                                                {{$order->Company->name ?? ''}}</td>
                                            <td style="text-align: right;"> : اسم المرسل</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Phone :</td>
                                            <td colspan="5" style="text-align:center;">
                                                {{$order->Company->phone ?? ''}}</td>
                                            <td style="text-align: right;"> : الهاتف</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Mobile :</td>
                                            <td colspan="5" style="text-align:center;">
                                                {{$order->Company->phone ?? ''}}</td>
                                            <td style="text-align: right;"> : الجوال</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Email :</td>
                                            <td colspan="5" style="text-align:center;">
                                                {{$order->Company->email ?? ''}}</td>
                                            <td style="text-align: right;"> : البريد الالكترونى</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: 1px solid #000; background-color: #000; color: #fff;width: 2rem;"
                                                rowspan="7">
                                                <a
                                                    style="    writing-mode: tb-rl;transform: rotate(180deg);">SHIPPER</a>
                                            </td>
                                            <td style="text-align: left;">Country :</td>
                                            <td colspan="5" style="text-align:center;">
                                                @if ( str_contains(url('/'), 'madarex.sa') )
                                                المملكة العربية السعودية
                                                @else
                                                مصر
                                                @endif
                                            </td>
                                            <td style="text-align: right;"> : الدوله </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">City :</td>
                                            <td colspan="5" style="text-align:center;">
                                                {{$order->Company->City->name ?? ''}}</td>
                                            <td style="text-align: right;"> : المدينه</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Adress :</td>
                                            <td colspan="5" style="text-align:center;">
                                                {{$order->Company->adress_details ?? ''}}
                                            </td>
                                            <td style="text-align: right;"> : العنوان</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Signature :</td>
                                            <td colspan="5" style="text-align:center;"></td>
                                            <td style="text-align: right;"> : التوقيع</td>
                                        </tr>
                                        <tr class="thead-dark">
                                            <th scope="col">PICKED BY</th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td scope="row">Employee Name</td>
                                            <td></td>
                                            <td colspan="2" style="text-align: right;">اسم المندوب</td>
                                            <td>Date</td>
                                            <td></td>
                                            <td style="text-align: right;">التاريخ</td>
                                        </tr>
                                    </table>

                            <br/>

                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" style="text-align:left;">PICKED BY</th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row">Employee Name</td>
                                                <td></td>
                                                <td> نوع الشحنه</td>
                                                <td>محتوى الشحنه</td>
                                                <td>{{$order->first()->packages_number}}</td>
                                                <td style="text-align: right;">عدد المنتجات</td>
                                            </tr>
                                        </tbody>
                                    </table>

                            <br/>
                            <pagebreak>
                                    <table style="width:100%; hight:100px; border-top: 0 !important;">

                                        <tr>
                                            <td style="text-align: left; border: 1px solid #000; background-color: #000; color: #fff;width: 2rem;"
                                                rowspan="5">
                                                <a style="    writing-mode: tb-rl; transform: rotate(180deg);">بيانات
                                                    الاستلام</a> </td>
                                            <td
                                                style="text-align: left; border: 1px solid #000; background-color: #cecece;">
                                                Customer
                                                No:
                                            </td>
                                            <td colspan="5" style="border: 0;"></td>

                                        </tr>

                                        <tr>
                                            <td style="text-align: left;">Revicer Name :</td>
                                            <td colspan="5" style="text-align:center;">
                                                {{$order->recipent_name}}
                                            </td>
                                            <td style="text-align: right;"> : اسم المستلم</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Phone :</td>
                                            <td colspan="5" style="text-align:center;">{{$order->phone}}
                                            </td>
                                            <td style="text-align: right;"> : الهاتف</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Mobile :</td>
                                            <td colspan="5" style="text-align:center;">{{$order->phone}}
                                            </td>
                                            <td style="text-align: right;"> : الجوال</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Email :</td>
                                            <td colspan="5" style="text-align:center;"></td>
                                            <td style="text-align: right;"> : البريد الالكترونى</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; border: 1px solid #000; background-color: #000; color: #fff;width: 2rem;"
                                                rowspan="7">
                                                <a
                                                    style="    writing-mode: tb-rl;transform: rotate(180deg);">RECIEVER</a>
                                            </td>
                                            <td style="text-align: left;">Country :</td>
                                            <td colspan="4" style="text-align:center;"></td>
                                            <td style="text-align: right;"> : الدوله </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">City :</td>
                                            <td colspan="5" style="text-align:center;">
                                                {{$order->City->Parent->name ?? ''}} /
                                                {{$order->City->name ?? ''}}
                                            </td>
                                            <td style="text-align: right;"> : المدينه</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Address :</td>
                                            <td colspan="5" style="text-align:center;">
                                                {{$order->adress_details}}
                                            </td>
                                            <td style="text-align: right;"> : العنوان</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">Signature :</td>
                                            <td colspan="5" style="text-align:center;"></td>
                                            <td style="text-align: right;"> : التوقيع</td>
                                        </tr>
                                        <tr class="thead-dark">
                                            <th scope="col">PICKED BY</th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td scope="row">Employee Name</td>
                                            <td></td>
                                            <td colspan="2" style="text-align: right;">اسم المندوب</td>
                                            <td>Date</td>
                                            <td></td>
                                            <td style="text-align: right;">التاريخ</td>
                                        </tr>
                                    </table>
                                    <br/>
                                    <table class="table">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col" style="text-align:left;">CREATED AT</th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td scope="row"> </td>
                                                <td></td>
                                                <td></td>
                                                <td>{{$order->created_at}}</td>
                                                <td></td>
                                                <td style="text-align: right;"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br/>

                    </div>
                    <br />
                </div>
            </div>
        </div>

<htmlpagefooter name="page-footer">
        <img src="{{public_path('/assets/images/invoice.jpg')}}" style="   hieght: 50px;
width: 100%;" />
    رقم الصفحة {PAGENO}
</htmlpagefooter>
</body>

</html>
