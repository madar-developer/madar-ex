@extends('admin.layout.app')
@section('style')
@section('header')
@endsection
@section('content')


<!-- Page-Title -->

<div class="row">

    <div class="col-sm-12">
        <div class="card-box">
            <form action="" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12 part-top">
                        <div class="row">

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">


                            {{-- <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="serial" value="{{(array_key_exists('serial', $search))? $search['serial'] : ''}}"
                            class="form-control" placeholder=" رقم الطلب">
                        </div>
                    </div>

                </div>
        </div> --}}

        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="serial_from"
                            value="{{(array_key_exists('serial_from', $search))? $search['serial_from'] : ''}}"
                            class="form-control" placeholder="  رقم الطلب من">
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="serial_to"
                            value="{{(array_key_exists('serial_to', $search))? $search['serial_to'] : ''}}"
                            class="form-control" placeholder="  رقم الطلب الي">
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="refrence_no"
                            value="{{(array_key_exists('refrence_no', $search))? $search['refrence_no'] : ''}}"
                            class="form-control" placeholder="رقم الطلب على متجر التاجر">
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        {!! Form::select("company_id",StoreOrCompany(),(array_key_exists('company_id ', $search))?
                        $search['company_id '] : null,['class'=>"form-control select2 "])!!}

                    </div>
                </div>

            </div>
        </div>



        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="company_phone"
                            value="{{(array_key_exists('company_phone', $search))? $search['company_phone'] : ''}}"
                            class="form-control" placeholder="  رقم تليفون المتجر">
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="recipent_name"
                            value="{{(array_key_exists('recipent_name', $search))? $search['recipent_name'] : ''}}"
                            class="form-control" placeholder="اسم المستلم">
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="phone"
                            value="{{(array_key_exists('phone', $search))? $search['phone'] : ''}}" class="form-control"
                            placeholder="رقم تليفون المستلم">

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="row">


        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        {!! Form::select("city_id",TheCity('المدينه من '),(array_key_exists('city_id', $search))?
                        $search['city_id'] : null,['class'=>"form-control select2 "])!!}
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        {!! Form::select("city_id",TheCity('المدينه الى'),(array_key_exists('city_id', $search))?
                        $search['city_id'] : null,['class'=>"form-control select2 "])!!}
                    </div>
                </div>

            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        {!! Form::select("status",array_merge(['' => 'اختر
                        الحالة'],OrderStatus()),(array_key_exists('status', $search))? $search['status'] :
                        null,['class'=>"form-control select2 "])!!}
                    </div>
                </div>

            </div>
        </div>

        <div class="col-md-2">
            <div class="form-horizontal m-b-15">
                <button type="button" onclick="$(this).closest('form').submit();"
                    class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                        class="fa fa-search"></i> بحث  </button>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-horizontal m-b-15">
                <button type="button" onclick="$(this).closest('form').submit();"
                    class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                        class="fa fa-search"></i> اصدار تقرير اكسل</button>
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-horizontal">
                <a href="{{url('/dashboard/orders')}}"
                    class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i
                        class="fa fa-trash"></i> مسح خيارات البحث</a>
            </div>
        </div>

    </div>
</div>
</div>
</form>
</div>
</div>
</div>



@endsection