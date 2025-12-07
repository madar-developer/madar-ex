<div class="card-box">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <label class="">   اسم المحصل * </label>
                <div class="">
                    {!! Form::text("collector",null,['class'=>'form-control' ,  'placeholder' => "",
                    'required' => ''])!!}
                </div>
            </div>
            <div class="form-group">
                <label class="">الحساب المحول الية *</label>
                <div class="">
                    {!! Form::select("company_cache_type_id",CompanyCacheTypesBC($transfere->company_id),null,['class'=>"form-control select2 ", "autocomplete"=>
                    'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                </div>
            </div>
            <div class="form-group">
                <label class="">  صورة الحوالة * <span></span></label>
                <div class="">
                    {!! Form::file("image",['class'=>'form-control '])!!}
                    @if(isset($transfer) && $transfer->image != null)
                    <img src="{{getImage($transfer->image)}}" style="height: 200px;">
                    @endif
                </div>
            </div>
        </div>
        {!! Form::hidden('active', 1, []) !!}
    </div>
</div>
<div class="card-box">
    <div class="row">
        <div class="text-center">
            <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">  حفظ </button>
        </div>
    </div>
</div> 
