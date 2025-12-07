<div class="card-box">

    <div class="row">
        <div class="col-lg-12">
            <p class="custom-label-centerd text-left"> معلومات المستلم </p>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class=""> اسم المستلم *</label>
                <div class=" append">
                    {!! Form::text("recipent_name",null,['class'=>'form-control select2','style' =>" display:
                    inline-block;"])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class=""> رقم التليفون : </label>
                <div class="">
                    {!! Form::text("phone",null,['class'=>'form-control', 'placeholder' => "",
                    'required' => ''])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class=""> العنوان كامل * </label>
                <div class="">
                    {!! Form::text("adress_details",null,['class'=>'form-control', 'placeholder' => "",
                    'required' => ''])!!}
                </div>
            </div>
        </div>
        <div class="flex-wrap">
            <div class="col-md-12 col-lg-8">
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
            </div>
            <div class="col-md-12 col-lg-4">
                <div class="form-group">
                    <label class="">  ملحوظات * </label>
                    <div class="">
                        {!! Form::textarea("notes",null,['class'=>'form-control', 'placeholder' => "","rows"=>
                        '3'])!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label class="">المدينه</label>
                    <div class=" append">
                        {!! Form::select("city_id",TheCityP(),null,['class'=>"form-control select2 ", "autocomplete"=>
                        'off', 'data-district_id' => isset($order)? $order->district_id : ''])!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label class="">الحي</label>
                    <div class=" append">
                        {!! Form::select("district_id",[],null,['class'=>"form-control select2 ", "autocomplete"=>
                        'off', 'id' => 'cities'])!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">

                <div class="form-group">
                    <label class="">الحاله</label>
                    <div class=" append">
                        {!! Form::select("status",OrderStatus(),null,['class'=>"form-control select2 ", "autocomplete"=>
                        'off'])!!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <label class="">رقم المرجع</label>
                    <div class=" append">
                        {!! Form::text("refrence_no",null,['class'=>"form-control select2 ", "autocomplete"=>
                        'off'])!!}
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="card-box">
    <p class="custom-label-centerd text-left"> معلومات الشحن </p>
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <div class="form-group">
                <label class="">نوع الطلب</label>
                <div class=" append">
                    {!! Form::select("order_type",Order_Type(),null,['class'=>"form-control select2 "])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-3 packages">
            <div class="form-group">
                <label class=""> عدد القطع * </label>
                <div class="">
                    {!! Form::number("packages_number",null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-3 return_packages">
            <div class="form-group">
                <label class=""> عدد القطع المرتجعة * </label>
                <div class="">
                    {!! Form::number("return_packages",null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label class=""> السعر * </label>
                <div class="">
                    {!! Form::number("price",null,['class'=>'form-control', 'step' => ".01"])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label class="">نوع السعر:</label>
                <div class=" append">
                    {!! Form::select("include_delivery_cost",Price_type(),null,['class'=>"form-control select2 ",
                    "autocomplete"=> 'off'])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label class=""> الوزن * </label>
                <div class="">
                    {!! Form::number("weight",null,['class'=>'form-control', 'step' => "any"])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label class=""> تاريخ اسلام الشحنة من المتجر  </label>
                <div class="">
                    {!! Form::text("pick_up_date",null,['class'=>'form-control datetimepicker', 'placeholder' => "التاريخ"])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class=""> وصف الشحنة * </label>
                <div class="">
                    {!! Form::textarea("description",null,['class'=>'form-control', 'placeholder' => "",
                    'rows' => '1'])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label class="">طريقه الدفع:</label>
                <div class=" append">
                    {!! Form::select("payment_method_id",PaymentMethod(),null,['class'=>"form-control select2 ",
                    "autocomplete"=> 'off'])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label class="">فتح الطرد:</label>
                <div class=" append">
                    {!! Form::select("can_open",Can_Open(),null,['class'=>"form-control select2 ",
                    "autocomplete"=> 'off']) !!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>




<div class="col-md-6">
    <div class="card-box">
        <div class="row">


            <p class="custom-label-centerd text-left">  معلومات الشاحن  </p>

            <div class="col-lg-12">
                <div class="form-group">
                    <label class=""> اختار المتجر  *</label>
                    <div class=" append">
                        {!! Form::select("company_id",StoreOrCompany(),null,['class'=>"form-control select2 ",
                        "autocomplete"=> 'off' , 'required' => ''])!!}
                    </div>
                </div>
            </div>


            {{-- <div class="text-center">
                <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">  اضافة </button>
            </div> --}}
        </div>


    </div>

</div>
<div class="col-md-6">
    <div class="card-box">
        <div class="row">


            <p class="custom-label-centerd text-left">  معلومات التوصيل  </p>

            <div class="col-lg-12">
                <div class="form-group">
                    <label class=""> تخصيص سائق التوصيل  *</label>
                    <div class=" append">
                        {!! Form::select("driver_id",DriversList(),null,['class'=>"form-control select2 ",
                        "autocomplete"=> 'off' , ])!!}
                    </div>
                </div>
            </div>


            {{-- <div class="text-center">
                <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">  اضافة </button>
            </div> --}}
        </div>


    </div>

</div>

<div class="sub-wr col-md-12 text-center">
    <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> اضافة
    </button>

</div>


