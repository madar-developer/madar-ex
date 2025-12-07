<div class="col-sm-12">
    <div class="card-box">

        <div class="row">
            <div class="col-lg-12">


                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="">المدينة *</label>
                            <div class="">
                                {!! Form::select("city_id",TheCityCost(),null,['class'=>"form-control select2 ", "autocomplete"=>
                                'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">تكلفه التوصيل</label>
                            <div class="">
                                {!! Form::number("delivery_cost",null,['class'=>"form-control ", "step"=> 'any'])!!}
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
