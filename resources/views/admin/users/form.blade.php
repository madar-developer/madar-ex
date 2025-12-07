<div class="col-md-3"></div>
<div class="col-md-6">
    <div class="card-box text-left">



        <div class="row">  
            <div class="col-lg-12">
                
                    {{--  //////////////////////////////////////////  --}}
                    <div class="form-group">
                        <label class=""> اسم المستخدم *</span></label>
                        <div class="">
                            {!! Form::text("name",null,['class'=>'form-control'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="">رقم الجوال  *</label>
                        <div class="">
                            {!! Form::text("phone",null,['class'=>'form-control',
                            'required' => ''])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="">البريد الالكترونى  *</label>
                        <div class="">
                            {!! Form::email("email",null,['class'=>'form-control',
                            'required' => '' ])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="">كلمة المرور * <span></span></label>
                        <div class="">
                            {!! Form::password("password",['class'=>'form-control'])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=""> العنوان كامل  * <span></span></label>
                        <div class="">
                            {!! Form::text("full_address",null,['class'=>'form-control',
                            'required' => ''])!!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class=""> الحاله  * <span></span></label>
                        <div class="">
                            {!! Form::select("active",UserStatus(),null,['class'=>"form-control select2 ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="">النوع  *</label>
                        <div class=" append">
                            {!! Form::select("user_type",BooleanChoices(),null,['class'=>"form-control select2 ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                        </div>
                    </div>

                  

                    <div class="text-center">
                        <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">  اضافة </button>
                    </div>


            </div>

        
        </div>
    </div>
</div>
<div class="col-md-3"></div>