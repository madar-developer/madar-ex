<div class="col-sm-12">
    <div class="card-box text-left">

        <!--     <h4 class="header-title header-title-custom m-t-0 m-b-30">بيانات الفاتورة</h4>-->

        <h5 class="sub-header-title-custom">البيانات الأساسية</h5>

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

            <div class="col-lg-12">
                <div class="form-horizontal">
                    {{--  //////////////////////////////////////////  --}}
                    <div class="form-group">
                        <label class="col-md-3 control-label">       اليوم :   </label>
                        <div class="col-md-9">
                            {!! Form::text("today",null,['class'=>'form-control datepicker', 'placeholder' => '   تاريخ اليوم 
                            ...', 'required' => '','style' =>"width: 90%; display: inline-block;"])!!}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">   قيمه التوصيل  : </label>
                        <div class="col-md-9">
                            {!! Form::number("connection_value",null,['class'=>'form-control', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">    مبلغ الشحنه  : </label>
                        <div class="col-md-9">
                            {!! Form::number("the_amount_of_shipping",null,['class'=>'form-control', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">     الرصيد : </label>
                        <div class="col-md-9">
                            {!! Form::number("balance",null,['class'=>'form-control', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">      الطلب : </label>
                        <div class="col-md-9">
                            {!! Form::select("the_demand",Order(),null,['class'=>'form-control', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>

                </div>
            </div><!-- end col -->

            <div class="col-lg-12">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-3 control-label"> العميل :</label>
                        <div class="col-md-9">
                            {!! Form::Select("customer",Users(),null,['class'=>"form-control select2 ", "autocomplete"=> 'off'])!!}
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-3 control-label"> الحاله :</label>
                        <div class="col-md-9">
                            {!! Form::select("nationality",UserStatus(),null,['class'=>"form-control select2 ", "autocomplete"=> 'off'])!!}
                        </div>
                    </div> 
          
  
   
                 
                </div>
            </div><!-- end col -->
            <div class="col-lg-6">
                <div class="form-horizontal">
           
                  
                </div>
            </div><!-- end col -->

            <div class="col-lg-12">
                <div class="form-horizontal">
               
                   
                 
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
        </div>
    </div>
</div>
