{{-- 'first_name', 'last_name' , 'email' , 'phone' , 'identical_number' , 'password' , 'nationality' , 'license_number',
        'license_date_expiration' --}}

    <div class="card-box text-left">



        <div class="row">
            <div class="col-lg-6">

                    {{--  //////////////////////////////////////////  --}}
                    <div class="form-group">
                        <label class=""> أسم السائق  *</span></label>
                        <div class="">
                            {!! Form::text("first_name",null,['class'=>'form-control'])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class=""> أسم العائلة  *</label>
                        <div class="">
                            {!! Form::text("last_name",null,['class'=>'form-control',
                            'required' => ''])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class=""> رقم التليفون  *</label>
                        <div class="">
                            {!! Form::text("phone",null,['class'=>'form-control',
                            'required' => '' ])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class=""> رقم الهوية * <span></span></label>
                        <div class="">
                            {!! Form::number("identical_number",null,['class'=>'form-control'])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="">  البريد الإلكترونى  * <span></span></label>
                        <div class="">
                            {!! Form::email("email",null,['class'=>'form-control',
                            'required' => ''])!!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class=""> كلمة المرور  * <span></span></label>
                        <div class="">
                            {!! Form::password("password",['class'=>"form-control  ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="">الجنسية  *</label>
                        <div class=" append">
                            {!! Form::text("nationality",null,['class'=>"form-control  ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="">المدينه  *</label>
                        <div class="append">
                            {!! Form::Select("city_id",AllCitys(),null,['class'=>"form-control select2 " , "autocomplete"=> 'off' ])!!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="">الاحياء  *</label>
                        <div class="append">
                            @php
                                $arr = [];
                                if(isset($driver))
                                {
                                    $arr = \App\Models\City::whereIn('id', $driver->cities)->pluck('name','id')->toArray();
                                }
                            @endphp
                            {!! Form::Select("cities[]",$arr,null,['class'=>"form-control select2 multiple",'multiple'=>'multiple' , "autocomplete"=> 'off', 'id'=>"cities"])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="">صورة الرخصه  *</label>
                        <div class="append">
                            {!! Form::file("license_image",null,['class'=>"form-control select2 multiple",'multiple'=>'multiple' , "autocomplete"=> 'off'])!!}
                        </div>
                    </div>



            </div>
            <div class="col-md-6">
                    <div class="form-group">
                        <label class="">رقم الرخصة  *</label>
                        <div class=" append">
                            {!! Form::text("license_number",null,['class'=>"form-control  ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                        </div>
                    </div>
                <div class="form-group">
                    <label class=""> تاريخ إنتهاء الرخصة  *</label>
                    <div class=" append">
                        {!! Form::text("license_date_expiration",null,['class'=>"form-control  datepicker", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                    </div>
                </div>
                <div class="form-group">
                    <label class=""> تاريخ إنتهاء الرخصة  * (هجري)</label>
                    <div class=" append">
                        {!! Form::text("license_expiration_date_hijri",null,['class'=>"form-control  hijri-date-input", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                    </div>
                </div>
                <div class="form-group">
                    <label class=""> تاريخ إنتهاء الهوية  *</label>
                    <div class=" append">
                        {!! Form::text("identity_expiration_date",null,['class'=>"form-control  datepicker", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                    </div>
                </div>
                <div class="form-group">
                    <label class=""> تاريخ إنتهاء الهويه  * (هجري)</label>
                    <div class=" append">
                        {!! Form::text("identity_expiration_date_hijri",null,['class'=>"form-control  hijri-date-input", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="">   اضافه سيارة  *</label>
                    <div class=" append">
                        {!! Form::select("car_id",Car(),null,['class'=>"form-control select2 ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                    </div>
                </div>
                <div class="form-group">
                    <label class=""> تاريخ  استلام السيارة  *</label>
                    <div class=" append">
                        {!! Form::text("car_receive_date",null,['class'=>"form-control  datepicker", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                    </div>
                </div>
                <div class="form-group">
                    <label class=""> تاريخ استلام السياره  * (هجري)</label>
                    <div class=" append">
                        {!! Form::text("car_receive_date_hijri",null,['class'=>"form-control  hijri-date-input", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                    </div>
                </div>
            <div class="form-group">
                <label class="">    نوع السائق  *</label>
                <div class=" append">
                    {!! Form::select("type",DriverTypes(),null,['class'=>"form-control select2", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>
            <div class="form-group">
                <label class="">    الراتب  *</label>
                <div class=" append">
                    {!! Form::number("fixed_salary",null,['class'=>"form-control  ", "autocomplete"=> 'off', 'min' => '0'])!!}
                </div>
            </div>
            <div class="form-group">
                <label class="">   العمولة  *</label>
                <div class=" append">
                    {!! Form::number("commission",null,['class'=>"form-control  ", "autocomplete"=> 'off', 'min' => '0'])!!}
                </div>
            </div>
            <div class="form-group">
                <label class="">    صورة الهويه  *</label>
                <div class=" append">
                    {!! Form::file("identity_image",['class'=>"form-control  ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>
            <div class="form-group">
                <label class="">    صورة الاستماره  *</label>
                <div class=" append">
                    {!! Form::file("form_image",['class'=>"form-control  ", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                </div>
            </div>

            </div>


                    <div class="text-center">
                        <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">  حفظ </button>
                    </div>


        </div>
    </div>

