<div class="col-sm-12">
    <div class="card-box text-left">

        <!--     <h4 class="header-title header-title-custom m-t-0 m-b-30">بيانات المدونه</h4>-->

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
                        <label class="col-md-3 control-label">الصورة </label>
                        <div class="col-md-9">
                            <input class="form-control" type="file" class="file" name="image" />
                            <br>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-md-3 control-label">    عنوان المدونة : </label>
                        <div class="col-md-9">
                            {!! Form::text("name",null,['class'=>'form-control', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">  الناشرة  : </label>
                        <div class="col-md-9">
                            {!! Form::text("by",null,['class'=>'form-control', 'placeholder' => "",
                            'required' => ''])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">       تفاصيل المدونة : </label>
                        <div class="col-md-9">
                            {!! Form::text("content",null,['class'=>'form-control', 'placeholder' => "",
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
                            <a href="{{url('/dashboard/blogs')}}" class="btn default cancel-button-panel">
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
