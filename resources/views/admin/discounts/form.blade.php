<div class="col-sm-12">
    <div class="card-box text-left">

        <!--     <h4 class="header-title header-title-custom m-t-0 m-b-30">بيانات العميل</h4>-->

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
                        <label class="col-md-3 control-label">       الدولة :   </label>
                        <div class="col-md-9 append">
                            {!! Form::select("country",Country(),null,['class'=>'form-control', 'placeholder' => '  إسم  الدوله 
                            ...','style' =>"width: 90%; display: inline-block;"])!!}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">    المدينة : </label>
                        <div class="col-md-9 append">
                            {!! Form::select("city",TheCity(),null,['class'=>'form-control', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">  من  : </label>
                        <div class="col-md-9">
                            {!! Form::text("from",null,['class'=>'form-control datepicker', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">       الى : </label>
                        <div class="col-md-9">
                            {!! Form::text("to_me",null,['class'=>'form-control datepicker', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">     نسبة الخصم %  : </label>
                        <div class="col-md-9">
                            {!! Form::number("discount_percentage",null,['class'=>'form-control', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>

                </div>
            </div><!-- end col -->

      
    

         
     </div>

            <div class="col-lg-12">
                <div class="panel-footer">
                    <div class="clearfix">
                        <div class="col-md-12 col-md-offset-5">
                            <button type="submit" class="btn blue">
                                <i class="fa fa-check"></i>
                                حفظ
                            </button>
                            <a href="{{url('/dashboard/discounts')}}" class="btn default cancel-button-panel">
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
