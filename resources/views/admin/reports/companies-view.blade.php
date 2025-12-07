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

                            
        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="name"
                            value="{{(array_key_exists('name', $search))? $search['name'] : ''}}"
                            class="form-control" placeholder="اسم المتجر">
                    </div>
                </div>

            </div>
        </div>


        <div class="col-lg-2">
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-md-12">
                        <input type="text" name="phone"
                            value="{{(array_key_exists('phone', $search))? $search['phone'] : ''}}"
                            class="form-control" placeholder="  رقم تليفون المتجر">
                    </div>
                </div>

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
                <a href="{{url('/dashboard/companies')}}"
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