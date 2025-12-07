<div class="col-sm-12">
    <div class="card-box">

        <div class="row">
            <div class="col-lg-12">
                <p class="custom-label-centerd text-left"> معلومات المتجر </p>


                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class=""> اسم المتجر * </label>
                            <div class="">
                                {!! Form::text("name",null,['class'=>'form-control', 'required' => ''])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=""> رقم التليفون * </label>
                            <div class="">
                                {!! Form::text("phone",null,['class'=>'form-control', 'placeholder' => "",
                                'required' => ''])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">البريد الالكترونى <span>*</span></label>
                            <div class="">
                                {!! Form::email("email",null,['class'=>'form-control',
                                'required' => '' ])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">كلمة المرور <span>*</span></label>
                            <div class="">
                                {!! Form::password("password",['class'=>'form-control',
                                'required' => ''
                                ])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">تكلفه ارجاع الطلب *</label>
                            <div class="">
                                {!! Form::number("return_cost",null,['class'=>"form-control  ", "autocomplete"=>
                                'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                            </div>
                        </div>
                        {{-- <div class="form-group">
                            <label class=""> تأكيد كلمه المرور <span></span></label>
                            <div class="">
                                {!! Form::password("password_confirmation",['class'=>'form-control', 'required' => '' ])!!}
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="">المدينة *</label>
                            <div class="">
                                {!! Form::select("city_id",TheCity(),null,['class'=>"form-control select2 ", "autocomplete"=>
                                'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">العنوان كامل *</label>
                            <div class="">
                                {!! Form::text("adress_details",null,['class'=>"form-control select2 ", "autocomplete"=>
                                'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=""> السجل التجارى * </label>
                            <div class="">
                                {!! Form::text("commercial_record",null,['class'=>'form-control', 'placeholder' => "",
                                'required' => ''])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=""> حالة المتجر * </label>
                            <div class="">
                                {!! Form::select("active",UserStatus(),null,['class'=>'form-control', 'placeholder' => "",
                                'required' => ''])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class=" "> الموقع علي الخريطة </label>
                            <div class="map-content ">
                                <div style="margin-top: 8px">
                                    {!! Form::text("address",null,['class'=>'form-control', 'id' => 'autocomplete'])!!}
                                </div>
        
                                <div id="mapCanvas"></div>
        
                                <div id="infoPanel" style="display: none;">
                                    <b>Marker status:</b>
                                    <div id="markerStatus"><i>Click and drag the marker.</i></div>
                                    <b>Current position:</b>
                                    <div id="info"></div>
                                    <b>Closest matching address:</b>
                                    <div id="address"></div>
                                </div>
                                {!! Form::hidden("latitude",null,['class'=>'form-control', 'id' => 'lat'])!!}
                                {!! Form::hidden("longitude",null,['class'=>'form-control', 'id' => 'lng'])!!}
                            </div>
                        </div>
                        {{-- <div class="form-group">
                                <label class="">النوع *</label>
                                <div class="">
                                    {!! Form::select("country",Country(),null,['class'=>"form-control select2 ",
                                    "autocomplete"=> 'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                                </div>
                            </div> --}}


                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="clear-fix">
</div>

<div class="card-box">
    <div class="row">
        {{-- <div class="col-lg-12">
            <p class="custom-label-centerd text-left"> معلومات السعر </p>
            <div class="form-group">
                <label class=""> طريقة دفع قيمة التوصيل *</label>
                <div class="">
                    {!! Form::select("Payment_method_id",PaymentMethod(),null,['class'=>"form-control select2 ",
                    "autocomplete"=>
                    'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                </div>
            </div>
        </div> --}}
        <div class="col-lg-6">

            <div class="one-option">
                <div class="checkbox checkbox-primary text-center">
                    <input id="checkbox22" value="1" name="inside_delivery" type="checkbox" checked>
                    <label for="checkbox22"> توصيل داخل الرياض </label>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class=""> السعر *</label>
                            <div class="">
                                {!! Form::number("inside_price",null,['class'=>"form-control select2 ", "autocomplete"=>
                                'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class=""> الدفع عند الاستلام *</label>
                            <div class="">
                                {!! Form::number("inside_payment_method_id",null,['class'=>"form-control select2
                                ",
                                "autocomplete"=>
                                'off', 'id' => 'banks'])!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- one-option -->

        </div> <!-- col-lg-6 -->



        <div class="col-lg-6">

            <div class="one-option">
                <div class="checkbox checkbox-primary text-center">
                    <input id="checkbox2" value="1" name="outside_delivery" type="checkbox" checked>
                    <label for="checkbox2"> توصيل خارج الرياض </label>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class=""> السعر *</label>
                            <div class="">
                                {!! Form::number("outside_price",null,['class'=>"form-control select2 ", "autocomplete"=>
                                'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class=""> الدفع عند الاستلام *</label>
                            <div class="">
                                {!! Form::number("outside_payment_method_id",null,['class'=>"form-control select2
                                ",
                                "autocomplete"=>
                                'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- one-option -->

        </div> <!-- col-lg-6 -->



        <div class="clearfix"></div>

        <div class="text-center">
            <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> اضافة </button>
        </div>
    </div>

</div>
</div>
