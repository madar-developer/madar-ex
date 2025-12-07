<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/print/css/bootstrap.min.css">
    <link rel="stylesheet" href="/print/css/myCss.css">
    <title>basic</title>
</head>

<body>
    <!---- header------------------>


    <div class="page madar" dir="ltr" id="DivIdToPrint">

        <div class="form-cont">
            <form method="" action="" name="" class="">

                <div class="form-row px-4 ">
                    <div class="form-group col-4 ">
                        <div class="img-wr">
                            <img src="{{asset('/adminto/assets/images/logo.png')}}" alt="">
                        </div>
                    </div>
                    <div class="form-group col-8 text-center">

                        <div class="img-wr">
                            @php
            echo '<img
                src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->serial, 'C39') . '"
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

                </div>
                <hr>
                <div class="form-row px-4">
                    <div class="col-5 flx">
                        <div class="col-6 bl">
                            <div class="bold">from : </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="" value="{{$order->Company()->first() ? ($order->Company()->first()->City->name ?? '') : ''}}">
                            </div>

                        </div>
                        <div class="col-6 bl">
                            <div class="bold">to : </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="" value="{{$order->City->name ?? ''}}">
                            </div>

                        </div>

                    </div>
                    <div class="col-7 flx">
                        <div class="col-7 bl ">
                            <div class="date form-group">
                                <label class="w3" for="exampleInputEmail1"> date :</label>
                                <input type="text" class="form-control" id="" value="{{$order->created_at->format('d')}}">
                                <span>/</span>
                                <input type="text" class="form-control" id="" value="{{$order->created_at->format('m')}}">
                                <span>/</span>
                                <input type="text" class="form-control" id="" value="{{$order->created_at->format('Y')}}">
                            </div>
                            <div class="form-group  line">
                                <label class="w5" for="p-of">reference :</label>
                                <input type="text" name="p-of" class="form-control" id="" value="{{$order->refrence_no}}">
                            </div>
                            <div class="form-group  line">
                                <label class="w5" for="p-of">order no :</label>
                                <input type="text" name="p-of" class="form-control" id="" value="{{$order->id}}">
                            </div>



                        </div>
                        <div class="col-5">
                            <div class="bold">COD</div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="" value="{{$order->price . ' ' . (( str_contains(url('/'), 'madarex.sa') )? ' ر.س ' : 'ريال')}}">
                            </div>


                        </div>

                    </div>
                </div>
                <hr>
                <div class="flx px-4">
                    <div class="left bl pr-4" style="transform: rotate( 90deg );">
                        @php
            echo '<img
                src="data:image/png;base64,' . DNS1D::getBarcodePNG($order->serial, 'C39') . '"
                alt="barcode" style="width: 19rem; height:5rem; " />';
            @endphp
                    </div>
                    <div class="right text-right pt-3" dir="rtl">
                        <div class="box">
                            <div class="bold">معلومات المرسل : </div>
                            <div class="form-group col-md-12 line">
                                <label for="p-of">الاسم :</label>
                                <input type="text" name="p-of" class="form-control" id="" value="{{$order->Company->name ?? ''}}">
                            </div>
                            <div class="form-group col-md-12 line">
                                <label for="p-of">رقم الجوال : </label>
                                <input type="text" name="p-of" class="form-control" id="" value="{{$order->Company->phone ?? ''}}">
                            </div>
                            <div class="form-group col-md-12 line">
                                <label for="p-of">العنوان : </label>
                                <input type="text" name="p-of" class="form-control" id="" value="{{$order->Company->adress_details ?? ''}}">
                            </div>

                        </div>
                        <div class="box mt-5">
                            <div class="bold">معلومات المستلم : </div>
                            <div class="form-group col-md-12 line">
                                <label for="p-of">الاسم :</label>
                                <input type="text" name="p-of" class="form-control" id="" value="{{$order->recipent_name}}">
                            </div>
                            <div class="form-group col-md-12 line">
                                <label for="p-of">رقم الجوال : </label>
                                <input type="text" name="p-of" class="form-control" id="" value="{{$order->phone}}">
                            </div>
                            <div class="form-group col-md-12 line">
                                <label for="p-of">العنوان : </label>
                                <input type="text" name="p-of" class="form-control" id="" value="{{$order->adress_details}}">
                            </div>

                        </div>
                        <div class="box my-5">
                            <div class="bold"> معلومات الشحنة : </div>
                            <div class="form-group">
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="4">{{$order->description}}</textarea>
                            </div>

                        </div>

                    </div>


                </div>









            </form>
        </div>





    </div>



    <!-- Optional JavaScript -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="/print/js/jquery-3.4.1.min.js"></script>
    <script src="/print/js/popper.min.js"></script>
    <script src="/print/js/bootstrap.min.js"></script>



</body>

</html>
