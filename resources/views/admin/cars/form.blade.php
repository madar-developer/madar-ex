<div class="card-box text-left">
    <div class="row">
        <div class="col-lg-6">
            {{--  //////////////////////////////////////////  --}}
            <div class="form-group">
                <label class=""> اسم السيارة * </span></label>
                <div class="">
                    {!! Form::text("name",null,['class'=>'form-control'])!!}
                </div>
            </div>
            <div class="form-group">
                <label class=""> رقم الهيكل *</label>
                <div class="">
                    {!! Form::text("structure_no",null,['class'=>'form-control',
                    'required' => ''])!!}
                </div>
            </div>
            <div class="form-group">
                <label class=""> المدينة التى تعمل بها السيارة * <span></span></label>
                <div class="">
                    {!! Form::text("work_city",null,['class'=>"form-control select2 ", "autocomplete"=> 'off', 'id' =>
                    'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>
            <div class="form-group">
                <label class=""> اللون *</label>
                <div class="">
                    {!! Form::text("color",null,['class'=>'form-control',
                    'required' => '' ])!!}
                </div>
            </div>
            <div class="form-group">
                <label class=""> سنة الصنع * <span></span></label>
                <div class="">
                    {!! Form::text("manufacturing_year",null,['class'=>'form-control date-picker-year'])!!}
                </div>
            </div>
            <div class="form-group">
                <label class="">  صورة الاستمارة * <span></span></label>
                <div class="">
                    {!! Form::file("form_image",['class'=>'form-control '])!!}
                    @if(isset($car) && $car->form_image != null)
                    <img src="{{getImage($car->form_image)}}" style="height: 200px;">
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class=""> رقم اللوحه  * <span></span></label>
                <div class="">
                    {!! Form::text("plate_num",null,['class'=>'form-control',
                    'required' => ''])!!}
                </div>
            </div>
            <div class="form-group">
                <label class=""> تاريخ إنتهاء الاستمارة  *</label>
                <div class=" append">
                    {!! Form::text("license_expiration_date",null,['class'=>"form-control select2 datepicker", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>
            <div class="form-group">
                <label class=""> تاريخ انتهاء الاستماره  * (هجري)</label>
                <div class=" append">
                    {!! Form::text("license_expiration_date_hijri",null,['class'=>"form-control  hijri-date-input", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>
            <div class="form-group">
                <label class=""> تاريخ إنتهاء التأمين  *</label>
                <div class=" append">
                    {!! Form::text("insurance_expiration_date",null,['class'=>"form-control select2 datepicker", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>
            <div class="form-group">
                <label class=""> تاريخ انتهاء التأمين  * (هجري)</label>
                <div class=" append">
                    {!! Form::text("insurance_expiration_date_hijri",null,['class'=>"form-control  hijri-date-input", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>
            <div class="form-group">
                <label class="">    نوع السيارة  *</label>
                <div class=" append">
                    {!! Form::select("type",CarTypes(),null,['class'=>"form-control select2", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>
            <div class="form-group">
                <label class="">   عدد الكيلو مترات  *</label>
                <div class=" append">
                    {!! Form::number("kms",null,['class'=>"form-control  ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>
        </div>
            <div class="text-center">
                <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> حفظ </button>
            </div>
    </div>
</div>