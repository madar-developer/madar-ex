<div class="col-sm-12">
    <div class="card-box text-left">


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> طريقه الدفع <span>AR</span></label>
                    {!! Form::text("name[ar]",(isset($method))? $method->getTranslation('name', 'ar') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> طريقه الدفع <span>EN</span></label>
                    {!! Form::text("name[en]",(isset($method))? $method->getTranslation('name', 'en') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
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
                            <a href="{{url('/dashboard/payment-methods')}}" class="btn default cancel-button-panel">
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
