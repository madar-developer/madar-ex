<div class="card-box text-left">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class=""> تاريخ طلب المندوب * </span></label>
                <div class="">
                    {!! Form::text("pickup_date",null,['class'=>'form-control datepicker'])!!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class=""> اسم المتجر *</label>
                <div class="">
                    {!! Form::text("name",(isset($request_driver))?$request_driver->name : auth('company')->user()->name,['class'=>'form-control',
                    'required' => ''])!!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class=""> جوال المتجر *</label>
                <div class="">
                    {!! Form::text("phone",(isset($request_driver))?$request_driver->phone : auth('company')->user()->phone,['class'=>'form-control',
                    'required' => ''])!!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class=""> الاوقات المتوفرة * <span></span></label>
                <div class="">
                    {!! Form::select("time_slot",TimeSlots(),null,['class'=>"form-control "])!!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class=""> العنوان *</label>
                <div class="">
                    {!! Form::text("address",null,['class'=>'form-control',
                    'required' => '' ])!!}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class=""> عدد الشحنات * <span></span></label>
                <div class="">
                    {!! Form::text("shipments",null,['class'=>'form-control'])!!}
                </div>
            </div>
        </div>
            <div class="text-center">
                <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit"> حفظ </button>
            </div>
    </div>
</div>
