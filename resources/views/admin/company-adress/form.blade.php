<div class="col-sm-12">
    <div class="card-box">

        <div class="row">
            <div class="col-lg-12">


                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="">المدينة *</label>
                            <div class="">
                                {!! Form::select("city_id",TheCityP(),null,['class'=>"form-control select2 ", "autocomplete"=>
                                'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">الاسم المستعار</label>
                            <div class="">
                                {!! Form::text("nick_name",null,['class'=>"form-control ", "autocomplete"=> 'off'])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">اسم الشارع</label>
                            <div class="">
                                {!! Form::text("street_name",null,['class'=>"form-control ", "autocomplete"=> 'off'])!!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="">رقم المبنى</label>
                            <div class="">
                                {!! Form::text("building",null,['class'=>"form-control ", "autocomplete"=> 'off'])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">رقم الطابق</label>
                            <div class="">
                                {!! Form::text("floor",null,['class'=>"form-control ", "autocomplete"=> 'off'])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">رقم الشقه</label>
                            <div class="">
                                {!! Form::text("flat",null,['class'=>"form-control ", "autocomplete"=> 'off'])!!}
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class=""> الاساسي  * <span></span></label>
                            <div class="">
                                {!! Form::select("main",UserStatus(),null,['class'=>"form-control select2 ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                            </div>
                        </div>
                    </div>
                        <div class="col-md-12">
                            
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

                </div>
            </div>
        </div>
    </div>
<div class="clear-fix">
</div>

<div class="card-box">
    <div class="row">

        <div class="clearfix"></div>

        <div class="text-center">
            <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> اضافة </button>
        </div>
    </div>

</div>
</div>
