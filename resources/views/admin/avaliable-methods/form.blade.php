<div class="col-sm-12">
    <div class="card-box text-left">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> العنوان <span>AR</span></label>
                    {!! Form::text("title[ar]",(isset($method))? $method->getTranslation('title', 'ar') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class=""> العنوان <span>EN</span></label>
                    {!! Form::text("title[en]",(isset($method))? $method->getTranslation('title', 'en') :
                    null,['class'=>'form-control', 'placeholder' => ""])!!}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class=""> الصوره : </label>
                    <div class="">
                        {!! Form::file("image",['class'=>'form-control', 'placeholder' => ' ','style' =>"width: 90%; display: inline-block;"])!!}
                        @if(isset($method) && $method->image != null)
                            <img src="{{getImage($method->image)}}" style="height: 200px;">
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
                                <a href="{{url('/dashboard/avaliable-methods')}}"
                                    class="btn default cancel-button-panel">
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
