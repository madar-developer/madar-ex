<div class="col-sm-12">
    <div class="card-box text-left">

        <!--     <h4 class="header-title header-title-custom m-t-0 m-b-30">بيانات الحواله</h4>-->

        <h5 class="sub-header-title-custom"> اضافه </h5>

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
                    <div class="form-group">
                        <label class="col-md-3 control-label">  الاسم  :   </label>
                        <div class="col-md-9">
                            {!! Form::text("name",null,['class'=>'form-control', 'placeholder' => '    
                            ...', 'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"> البريد الالكترونى :</label>
                        <div class=" col-md-9">
                            {!! Form::email("email",null,['class'=>"form-control", "autocomplete"=> 'off' ])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">   كلمه المرور :</label>
                        <div class=" col-md-9">
                            {!! Form::password("password",['class'=>"form-control", "autocomplete"=> 'off' ])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">   نوع الحساب :</label>
                        <div class=" col-md-9">
                            {!! Form::select("role",role(),null,['class'=>"form-control select2", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">    اسم الفرع :</label>
                        <div class=" col-md-9">
                            {!! Form::select("parent_id",Branch(),null,['class'=>"form-control select2", "autocomplete"=> 'off', 'id' => 'banks', 'style' =>"width: 100%; display: inline-block;"])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label"> المدن :</label>
                        <div class=" col-md-9">
                            {!! Form::select("cities[]",AllCitysN(),null,['class'=>"form-control select2 multiple", "autocomplete"=> 'off', 'multiple' => 'multiple'])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">  الصوره :</label>
                        <div class=" col-md-9">
                            {!! Form::file("image",['class'=>"form-control", "autocomplete"=> 'off'])!!}
                            @if(isset($admin) && $admin->image != null)
                                <img src="{{getImage($admin->image)}}" style="height: 200px;">
                            @endif
                        </div>
                    </div>
                    {{--  <div class="form-group">
                        <label class="col-md-3 control-label">  العنوان :</label>
                        <div class=" col-md-9">
                            {!! Form::text("address",null,['class'=>"form-control select2", "autocomplete"=> 'off'])!!}
                        </div>
                    </div>  --}}
                 

      

     </div>

            <div class="col-lg-12">
                <div class="panel-footer">
                    <div class="clearfix">
                        <div class="col-md-12 col-md-offset-5">
                            <button type="submit" class="btn blue">
                                <i class="fa fa-check"></i>
                                حفظ
                            </button>
                            <a href="{{url('/dashboard/admins')}}" class="btn default cancel-button-panel">
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
