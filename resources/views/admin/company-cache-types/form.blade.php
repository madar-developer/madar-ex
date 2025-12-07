<div class="col-sm-12">
    <div class="card-box text-left">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="">اختر وسيلة التحصيل *</label>
                        <div class="">
                            {!! Form::select("available_method_id",AvailableMethodsP(),null,['class'=>"form-control select2 ", "autocomplete"=>
                            'off', 'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=""> العنوان : </label>
                        <div class="">
                            {!! Form::text("title",null,['class'=>'form-control', 'placeholder' => ' ', 'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class=""> التفاصيل : </label>
                        <div class="">
                            {!! Form::text("description",null,['class'=>'form-control', 'placeholder' => ' ', 'required' => ''])!!}
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
