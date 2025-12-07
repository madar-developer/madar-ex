<div class="col-sm-12">
    <div class="card-box text-left">


        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> الاسم <span>AR</span></label>
                    {!! Form::text("name[ar]",(isset($status))? $status->getTranslation('name', 'ar') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> الاسم <span>EN</span></label>
                    {!! Form::text("name[en]",(isset($status))? $status->getTranslation('name', 'en') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> التفاصيل <span>AR</span></label>
                    {!! Form::text("details[ar]",(isset($status))? $status->getTranslation('details', 'ar') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> التفاصيل <span>EN</span></label>
                    {!! Form::text("details[en]",(isset($status))? $status->getTranslation('details', 'en') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group " >
                    <label class="">        اللون :   </label>
                    <div class="">
                        {!! Form::text("color",null,['class'=>'form-control colorpicker-default', 'autocomplete' => 'off', 'style' =>"width: 90%; display: inline-block;"])!!}
                    </div>
                </div>
                </div>

                <div class="col-lg-6">
            <div class="form-group ">
                <label class=""> الصوره</label>
                <div class="">
                    {!! Form::file("image",['class'=>'form-control ','style' =>"width: 90%; display: inline-block;"])!!}
                    @if(isset($status) && $status->image != null)
                    <img src="{{getImage($status->image)}}" style="height: 200px;">
                    @endif
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
                            <a href="{{url('/dashboard/order-status')}}" class="btn default cancel-button-panel">
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
