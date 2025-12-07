<div class="card-box">

    <div class="row">
        <div class="col-lg-12">
            <p class="custom-label-centerd text-left"> معلومات المتجر </p>
        </div>
        <div class="col-lg-6">
            {{--  //////////////////////////////////////////  --}}

            <div class="form-group">
                <label class=""> اسم المستلم *</label>
                <div class=" append">
                    {!! Form::text("recipent_name",null,['class'=>'form-control select2','style' =>" display:
                    inline-block;"])!!}
                </div>
            </div>

            <div class="form-group">
                <label class=""> رقم الجوال : </label>
                <div class="">
                    {!! Form::text("phone",null,['class'=>'form-control', 'placeholder' => "",
                    'required' => ''])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-6">



            <div class="form-group">
                <label class=""> العنوان كامل * </label>
                <div class="">
                    {!! Form::text("adress_details",null,['class'=>'form-control', 'placeholder' => "",
                    'required' => ''])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="">  ملحوظات * </label>
                <div class="">
                    {!! Form::text("notes",null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                <label class="">المدينه</label>
                <div class=" append">
                    {!! Form::select("city_id",TheCity(),null,['class'=>"form-control select2 ", "autocomplete"=>
                    'off'])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-6">

            {{--  <div class="form-group">
                <label class="">الحاله</label>
                <div class=" append">
                    {!! Form::select("status",OrderStatus(),null,['class'=>"form-control select2 ", "autocomplete"=>
                    'off'])!!}
                </div>
            </div>  --}}
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="">Refrence no</label>
                <div class=" append">
                    {!! Form::text("refrence_no",null,['class'=>"form-control select2 ", "autocomplete"=>
                    'off'])!!}
                </div>
            </div>



        </div>


    </div>
</div>

<div class="card-box">
    <p class="custom-label-centerd text-left"> معلومات الشحن </p>
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label class=""> عدد المنتجات * </label>
                <div class="">
                    {!! Form::number("packages_number",null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label class=""> السعر * </label>
                <div class="">
                    {!! Form::number("price",null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label class="">طريقه الدفع:</label>
                <div class=" append">
                    {!! Form::select("payment_method_id",PaymentMethod(),null,['class'=>"form-control select2 ",
                    "autocomplete"=> 'off'])!!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>





<div class="card-box">
    <div class="row">



                    {{--  /////////////////////////////////////////////////////////////////  --}}
                    {!! Form::hidden("company_id",auth()->id(),[])!!}



        <div class="text-center">
            <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">  اضافة </button>
        </div>
    </div>


</div>


{{--  <div class="form-group">
            <label class=""> إلى:</label>
            <div class="">
                {!! Form::text("time_to",null,['class'=>"form-control timepicker ", "autocomplete"=> 'off'])!!}
            </div>
        </div>
        <div class="form-group">
            <label class=""> الحى : </label>
            <div class="">
                {!! Form::text("district",null,['class'=>'form-control', 'placeholder' => "",
                'required' => ''])!!}
            </div>
        </div>
        <div class="form-group">
            <label class="">الصور </label>
            <div class="">
                <input class="form-control" type="file" class="file" name="images" />
                <br>
            </div>
            @if(isset($user))
            @if($user->image!=null)
            <label class="col-md-2 control-label">الصورة الحاليه</label>
            <img src="{{$user->image}}" style="width: 300px;">
@endif
@endif
</div>
<div class="form-group">
    <label class=""> نسبه الخصم : </label>
    <div class="">
        {!! Form::number("discount",null,['class'=>'form-control', 'placeholder' => ""])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> حى الإستلام : </label>
    <div class="">
        {!! Form::text("receiving_district",null,['class'=>'form-control', 'placeholder' => ""])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> تاريخ التسليم : </label>
    <div class="">
        {!! Form::text("delivered_order",null,['class'=>'form-control datepicker', 'placeholder' => ""])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> قيمه البضاعه : </label>
    <div class="">
        {!! Form::number("value_of_goods",null,['class'=>'form-control', 'placeholder' => ""])!!}
    </div>
</div>
<div class="form-group">
    <label class="">طريقه الدفع:</label>
    <div class=" append">
        {!! Form::select("payment_method",PaymentMethod(),null,['class'=>"form-control select2 ",
        "autocomplete"=> 'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> طريقة دفع التوصيل:</label>
    <div class=" append">
        {!! Form::select("plug_type",PaymentMethod(),null,['class'=>"form-control select2 ", "autocomplete"=>
        'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> مرتجع :</label>
    <div class=" append">
        {!! Form::select("return_package",ReturnPackages(),null,['class'=>"form-control select2 ",
        "autocomplete"=> 'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> الحاله :</label>
    <div class=" append">
        {!! Form::select("case",UserStatus(),null,['class'=>"form-control select2 ", "autocomplete"=> 'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class="">حجم الشحنه:</label>
    <div class=" append">
        {!! Form::select("shipping_size",ShippingSize(),null,['class'=>"form-control select2 ", "autocomplete"=>
        'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class="">نوع السياره </label>
    <div class=" append">
        {!! Form::select("type_of_car",TypeOfCar(),null,['class'=>"form-control select2 ", "autocomplete"=>
        'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> السائق</label>
    <div class=" append">
        {!! Form::select("driver",Driver(),null,['class'=>"form-control select2 ", "autocomplete"=> 'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> مدينه الاستلام</label>
    <div class=" append">
        {!! Form::select("receiving_city",TheCity(),null,['class'=>"form-control select2 ", "autocomplete"=>
        'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> نفس الحى ؟ :</label>
    <div class=" append">
        {!! Form::select("same_zone",BooleanChoise(),null,['class'=>"form-control select2 ", "autocomplete"=>
        'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> السائقين المحتملين : </label>
    <div class=" append">
        {!! Form::select("suggested_drivers",SuggestedDrivers(),null,['class'=>"form-control select2 ",
        "autocomplete"=> 'off'])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> السعر : </label>
    <div class="">
        {!! Form::number("price",null,['class'=>'form-control', 'placeholder' => ""])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> الخصم : </label>
    <div class="">
        {!! Form::number("discount",null,['class'=>'form-control', 'placeholder' => ""])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> عدد المنتجات : </label>
    <div class="">
        {!! Form::number("number_of_packages",null,['class'=>'form-control', 'placeholder' => ""])!!}
    </div>
</div>
<div class="form-group">
    <label class=""> اسم المستلم: : </label>
    <div class="">
        {!! Form::text("the_recipient_s_name",null,['class'=>"form-control select2 ", "autocomplete"=>
        'off'])!!}
    </div>
</div> --}}








{{--
<div class="col-lg-12">
    <div class="panel-footer">
        <div class="clearfix">
            <div class="col-md-12 col-md-offset-5">
                <button type="submit" class="btn blue">
                    <i class="fa fa-check"></i>
                    حفظ
                </button>
                <a href="{{url('/dashboard/shops')}}" class="btn default cancel-button-panel">
                    <i class="fa fa-times"></i>
                    إلغاء الأمر
                </a>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>  --}}
