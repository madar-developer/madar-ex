<div class="col-sm-12">
    <div class="card-box text-left">

        <!--     <h4 class="header-title header-title-custom m-t-0 m-b-30">بيانات العميل</h4>-->

        <div class="col-lg-12">
            <p class="custom-label-centerd text-left">    معلومات المتجر   </p>
        </div>

        <div class="row">
            <!-- @if (count($errors) > 0)
                    <div class="alert alert-danger">
                      <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                    @endif -->

            <div class="col-lg-6">
                    <div class="form-group">
                        <label class=""> اسم المتجر * </label>
                        <div class="">
                            {!! Form::text("name",null,['class'=>'form-control', 'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=""> رقم الجوال * </label>
                        <div class="">
                            {!! Form::text("phone",null,['class'=>'form-control', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="">البريد الالكترونى  <span>*</span></label>
                        <div class="">
                            {!! Form::email("email",null,['class'=>'form-control',
                            'required' => '' ])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="">كلمة المرور  <span>*</span></label>
                        <div class="">
                            {!! Form::password("password",null,['class'=>'form-control', 
                            'required' => ''
                            ])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=""> تأكيد كلمه المرور <span></span></label>
                        <div class="">
                            {!! Form::password("password_confirmation",null,['class'=>'form-control', 'required' => '' ])!!}
                        </div>
                    </div>
                  

                    
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="">المدينة *</label>
                    <div class="">
                        {!! Form::select("city",TheCity(),null,['class'=>"form-control select2 ", "autocomplete"=>
                        'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="">العنوان كامل *</label>
                    <div class="">
                        {!! Form::text("adress_details",null,['class'=>"form-control select2 ", "autocomplete"=>
                        'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                    </div>
                </div>
                <div class="form-group">
                    <label class=""> السجل التجارى * </label>
                    <div class="">
                        {!! Form::text("commercial_record",null,['class'=>'form-control', 'placeholder' => "",
                        'required' => ''])!!}
                    </div>
                </div>
                <div class="form-group">
                    <label class=""> حالة المتجر * </label>
                    <div class="">
                        {!! Form::select("active",null,UserStatus(),['class'=>'form-control', 'placeholder' => "",
                        'required' => ''])!!}
                    </div>
                </div>
                {{-- <div class="form-group">
                    <label class="">النوع *</label>
                    <div class="">
                        {!! Form::select("country",Country(),null,['class'=>"form-control select2 ",
                        "autocomplete"=> 'off', 'id' => 'banks', 'style' =>" display: inline-block;"])!!}
                    </div>
                </div> --}}

                  
            </div>
        </div>
    </div>
</div>
</div>

        <div class="card-box">

            <div class="row">
                <div class="col-lg-12">
                    <p class="custom-label-centerd text-left">    معلومات السعر   </p>
                    <div class="form-group">
                        <label for="userName"> طريقة دفع قيمة التوصيل *</label>
                        <select class="form-control">
                            <option>   </option>
                            <option> الدفع مقدما  </option>
                            <option> على الحساب يخصم من الرصيد لاحقا  </option>
                        </select> 
                    </div>
                </div>
                <div class="col-lg-6">

                    <div class="one-option">
                        <div class="checkbox checkbox-primary text-center">
                            <input id="checkbox2" type="checkbox" checked>
                            <label for="checkbox2"> توصيل داخل الرياض </label>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="userName">    السعر  *</label>
                                    <input type="text" name="nick" parsley-trigger="change" required placeholder="     " class="form-control" id="userName">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="userName">     الدفع عند الاستلام   *</label>
                                    <input type="text" name="nick" parsley-trigger="change" required placeholder="     " class="form-control" id="userName">
                                </div>
                            </div>
                        </div>
                    </div> <!-- one-option -->

                </div> <!-- col-lg-6 -->



                <div class="col-lg-6">

                    <div class="one-option">
                        <div class="checkbox checkbox-primary text-center">
                            <input id="checkbox2" type="checkbox" checked>
                            <label for="checkbox2"> توصيل داخل الرياض </label>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="userName">    السعر  *</label>
                                    <input type="text" name="nick" parsley-trigger="change" required placeholder="     " class="form-control" id="userName">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="userName">     الدفع عند الاستلام   *</label>
                                    <input type="text" name="nick" parsley-trigger="change" required placeholder="     " class="form-control" id="userName">
                                </div>
                            </div>
                        </div>
                    </div> <!-- one-option -->

                </div> <!-- col-lg-6 -->



                <div class="clearfix"></div>

                <div class="text-center">
                    <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">  اضافة </button>
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
                        <a href="{{url('/dashboard/shops')}}" class="btn default cancel-button-panel">
                            <i class="fa fa-times"></i>
                            إلغاء الأمر
                        </a>
                    </div>
                </div>
            </div>
        </div>
   
