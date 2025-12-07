

{{--  'name', 'structure_no' , 'color' , 'manufacturing_year' , 'car_type' , 'work_city'  --}}
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card-box text-left">
        @php
            if(request()->has('carid'))
            {
                $carid = request()->get('carid');
            }else{
                $carid = null;
            }
        @endphp
        
                <div class="row">  
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="">   اختر سيارة  *</label>
                            <div class=" append">
                                {!! Form::select("car_id",Car(),$carid,['class'=>"form-control select2", "autocomplete"=> 'off', 'required' => ''])!!}
                            </div>
                        </div>
                            {{--  //////////////////////////////////////////  --}}
                            <div class="form-group">
                                <label class=""> التكلفه  * </span></label>
                                <div class="">
                                    {!! Form::text("cost",null,['class'=>'form-control'])!!}
                                </div>
                            </div>
        
                            <div class="form-group">
                                <label class="">  نوع الصيانة  *</label>
                                <div class="">
                                    <div class=" append">
                                        {!! Form::select("type",CarMaintenanceTypes(),null,['class'=>"form-control select2", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                                    </div>
                                </div>
                            </div>
        
                            <div class="form-group">
                                <label class="">  الشهر  *</label>
                                <div class="">
                                    {!! Form::text("month",null,['class'=>'form-control date-picker-month-year',
                                    'required' => '' ])!!}
                                </div>
                            </div>
                            {{--  <div class="form-group">
                                <label class="">  الشهر  *</label>
                                <div class="">
                                    {!! Form::text("month",null,['class'=>'form-control hijri-date-input',
                                    'required' => '' ])!!}
                                </div>
                            </div>  --}}
                         
        
                            <div class="form-group">
                                <label class="">  ملحوظات * <span></span></label>
                                <div class="">
                                    {!! Form::textarea("notes",null,['class'=>'form-control'])!!}
                                </div>
                            </div>
        
                       
        
                          
        
                            <div class="text-center">
                                <button class="btn btn-primary waves-effect waves-light btn-submit" type="submit">  حفظ </button>
                            </div>
        
        
                    </div>
        
                
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
        
