<div class="card-box text-left">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class=""> العنوان <span>AR</span></label>
                {!! Form::text("title[ar]",(isset($slider))? $slider->getTranslation('title', 'ar') :
                null,['class'=>'form-control', 'placeholder' => ""])!!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class=""> العنوان <span>EN</span></label>
                {!! Form::text("title[en]",(isset($slider))? $slider->getTranslation('title', 'en') :
                null,['class'=>'form-control', 'placeholder' => ""])!!}
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="">  الصوره  <span>Ar</span></label>
                <div class="">
                    {!! Form::file("image[ar]",['class'=>'form-control'])!!}
                    @if(isset($slider) && $slider->image != null)
                        <img src="{{getImage($slider->getTranslation('image', 'ar'))}}" style="height: 200px;">
                        @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="">  الصوره <span>EN</span></label>
                <div class="">
                    {!! Form::file("image[en]",['class'=>'form-control'])!!}
                    @if(isset($slider) && $slider->image != null)
                        <img src="{{getImage($slider->getTranslation('image', 'en'))}}" style="height: 200px;">
                        @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="">النوع  *</label>
                <div class=" append">
                    {!! Form::select("type",SliderType(),null,['class'=>"form-control select2 "])!!}
                </div>
            </div>
        </div>

            <div class="col-lg-12 text-center">
                <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> حفظ </button>
            </div>
    </div>
</div>
