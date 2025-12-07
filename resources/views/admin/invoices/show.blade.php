@extends('admin.layout.app')
@section('style')
    <style>
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
        @media print {
            .xc{
                text-align: left !important;
                 border: 1px solid #000 !important;
                  background-color: #000 !important;
                   color: #fff !important;width: 2rem !important;
            }
        }
    </style>
@endsection
@section('header')
<!-- Page title -->
<ul class="nav navbar-nav navbar-left">
    <li>
        <button class="button-menu-mobile  ">
            <i class="zmdi zmdi-menu"></i>
        </button>
    </li>
    <li>
        <h4 class="page-title">
            عرض الفواتير </h4>
    </li>
</ul>
<ul class="nav navbar-nav navbar-right">
    <li>
        <div class="add-btn">
            <button type="button" id='btn' value='Print' onclick='printDiv();'
                class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-print"></i>
                Print </button>
        </div>
    </li>
</ul>
<ul class="nav navbar-nav navbar-right">
    <li>

    </li>
</ul>
@endsection
@section('content')
<div class="card-box">
    <div class="row">
        <div class="col-lg-12">
            <p class="custom-label-centerd text-left"> بيانات الاستلام </p>
        </div>
        <div class="" style="padding: 20px;direction: ltr;" id="DivIdToPrint">
            <div class="row">
                <div class="col-md-12" style="">
                    <table class="table tab1"
                        style="border: 0 !important; direction: rtl; text-align: right; width: 18rem; float: right;">
                        <tr>
                            <td style="border: 0 !important;background-color: orange; width: 9rem; text-align: right;">
                                الاجمالي
                                <br />
                                Tolal
                            </td>
                            <td
                                style="border: 0 !important;background-color: #cecece;width: 9rem;border-radius: 20px 0px 0 20px;">
                            </td>
                        </tr>
                    </table>
                </div>
                <br/>
                <br/>
                <br/>
                <div class="col-md-12 text-center" style="">
                    <h4>  {{$invoice->created_at}} </h4>
                  </div>
                <div class="col-md-6">
                    <table class="table" style="border-top: 0 !important;">

                        <tr>
                            <td class="xc" style="text-align: left; border: 1px solid #000; background-color: #000; color: #fff;width: 2rem;"
                                rowspan="5" bgcolor="#000000" >
                                <a style="    writing-mode: tb-rl; transform: rotate(180deg);">بيانات الارسال</a> </td>
                            <td style="text-align: left; border: 1px solid #000; background-color: #cecece;"> Customer
                                No:
                            </td>
                            <td style="border: 0;"></td>
                            <td style="border: 0;"></td>
                            <td style="border: 0;"></td>

                        </tr>

                        <tr>
                            <td style="text-align: left;">Revicer Name :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->reciever_name ?? ''}}</td>
                            <td style="text-align: right;"> : اسم المرسل</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Phone :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->reciever_phone}}</</td>
                            <td style="text-align: right;"> : الهاتف</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Mobile :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->reciever_mobile ?? ''}}</td>
                            <td style="text-align: right;"> : رقم التليفون</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Email :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->reciever_email ?? ''}}</td>
                            <td style="text-align: right;"> : البريد الالكترونى</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; border: 1px solid #000; background-color: #000; color: #fff;width: 2rem;"
                                rowspan="7">
                                <a style="    writing-mode: tb-rl;transform: rotate(180deg);">SHIPPER</a> </td>
                            <td style="text-align: left;">Country :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->reciever_country ?? ''}}</td>
                            <td style="text-align: right;"> : الدوله </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">City :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->reciever_city ?? ''}}</td>
                            <td style="text-align: right;"> : المدينه</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Adress :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->reciever_address ?? ''}}</td>
                            <td style="text-align: right;"> : العنوان</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Signature :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->reciever_signature ?? ''}}</td>
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


                </div>



                <div class="col-md-6">

                    <table class="table" style="border-top: 0 !important;">

                        <tr>
                            <td style="text-align: left; border: 1px solid #000; background-color: #000; color: #fff;width: 2rem;"
                                rowspan="5">
                                <a style="    writing-mode: tb-rl; transform: rotate(180deg);">بيانات الاستلام</a> </td>
                            <td style="text-align: left; border: 1px solid #000; background-color: #cecece;"> Customer
                                No:
                            </td>
                            <td style="border: 0;"></td>
                            <td style="border: 0;"></td>
                            <td style="border: 0;"></td>

                        </tr>

                        <tr>
                            <td style="text-align: left;">Revicer Name :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->sender_name ?? ''}}</td>
                            <td style="text-align: right;"> : اسم المستلم</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Phone :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->sender_phone ?? ''}}</td>
                            <td style="text-align: right;"> : الهاتف</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Mobile :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->sender_mobile ?? ''}}</td>
                            <td style="text-align: right;"> : رقم التليفون</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Email :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->sender_mail ?? ''}}</td>
                            <td style="text-align: right;"> : البريد الالكترونى</td>
                        </tr>
                        <tr>
                            <td style="text-align: left; border: 1px solid #000; background-color: #000; color: #fff;width: 2rem;"
                                rowspan="7">
                                <a style="    writing-mode: tb-rl;transform: rotate(180deg);">RECIEVER</a> </td>
                            <td style="text-align: left;">Country :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->sender_country ?? ''}}</td>
                            <td style="text-align: right;"> : الدوله </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">City :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->sender_city ?? ''}}</td>
                            <td style="text-align: right;"> : المدينه</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Adress :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->sender_address ?? ''}}</td>
                            <td style="text-align: right;"> : العنوان</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Signature :</td>
                            <td colspan="5" style="text-align:center;">{{$invoice->Order->sender_signature ?? ''}}</td>
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
                </div>

                <div class="col-md-6">
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
                                <td>{{$invoice->packages_number}}</</td>
                                <td style="text-align: right;">عدد المنتجات</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<img src="{{url('/assets/images/invoice.jpg')}}" style="    margin-top: -19px;
width: 1229px; />
@endsection
@section('script')
 <script>
    function printDiv()
    {

      var divToPrint=document.getElementById('DivIdToPrint');

      var newWin=window.open('','Print-Window');

      newWin.document.open();

      newWin.document.write(`<html><style>
        table {
            border-collapse: collapse;
            border: 1px solid black;
            width:48% !important;
            float:right !important;
            margin:1% !important;
            bachground-color:red;
        }
        .tab1{
            width:100% !important;
        }


        td,
        th {

            text-align: left;
            padding: 7px;
            border-bottom: 1px solid black;
        }

    </style><link href="{{ asset('/adminto/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" media="print" type="text/css" />
      <body onload="window.print()">`+divToPrint.innerHTML+`</body></html>`);

      newWin.document.close();

      setTimeout(function(){newWin.close();},10);

    }
 </script>
@endsection
