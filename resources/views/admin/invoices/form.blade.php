<!--     <h4 class="header-title header-title-custom m-t-0 m-b-30">بيانات الفاتورة</h4>-->


<div class="card-box">
    <p class="custom-label-centerd text-left"> الاسعار  </p>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label class="">  المبلغ الكلى :</label>
                <div class=" append">
                    {!! Form::number("total_price",null,['class'=>"form-control ", "step"=> 'any'])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="">  المستحق للشركة :</label>
                <div class=" append">
                    {!! Form::number("company_price",null,['class'=>"form-control ", "step"=> 'any'])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="">  تكلفة التوصيل  :</label>
                <div class=" append">
                    {!! Form::number("madar_price",null,['class'=>"form-control ", "step"=> 'any'])!!}
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="">  تكلفة السائق  :</label>
                <div class=" append">
                    {!! Form::number("driver_cost",null,['class'=>"form-control ", "step"=> 'any'])!!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
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
            </div>
        </div>
    </div>
</div>
