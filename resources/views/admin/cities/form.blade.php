<div class="col-sm-12">
    <div class="card-box text-left">


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> الاسم <span>AR</span></label>
                    {!! Form::text("name[ar]",(isset($city))? $city->getTranslation('name', 'ar') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> الاسم <span>EN</span></label>
                    {!! Form::text("name[en]",(isset($city))? $city->getTranslation('name', 'en') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-group">
                    <label class="">  كود المدينة  :   </label>
                    <div class="">
                        {!! Form::text("city_code",null,['class'=>'form-control', 'placeholder' => 'RDH', 'required' => ''])!!}
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-4">
                <div class="form-group">
                    <label class="">  تكلفة التوصيل   :   </label>
                    <div class="">
                        {!! Form::select("delivery_cost",null,['class'=>'form-control', 'min' => '1'])!!}
                    </div>
                </div>
            </div> --}}

            <div class="col-lg-4">
                <div class="form-group">
                    <label class="">  تكلفة التوصيل   :   </label>
                    <div class="">
                        {!! Form::select("delivery_cost",UserStatus(),null,['class'=>"form-control select2 ", "autocomplete"=> 'off', 'style' =>"width: 100%; display: inline-block;"])!!}
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
            <div class="form-group">
                    <label class=""> الاب *</label>
                    <div class="">
                        {!! Form::select("parent",CitiesParent(),null,['class'=>"form-control select2", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                    </div>
                </div>


            </div>

            <div class="col-lg-12">
                <div class="panel-footer">
                    <div class="clearfix">
                        <div class="col-md-12 col-md-offset-5">
                            <button type="submit" class="btn blue">
                                <i class="fa fa-check"></i>
                                حفظ
                            </button>
                            <a href="{{url('/dashboard/cities')}}" class="btn default cancel-button-panel">
                                <i class="fa fa-times"></i>
                                إلغاء الأمر
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
