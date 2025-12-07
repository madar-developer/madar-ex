
@if (count($errors) > 0)
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif



                    <div class="col-lg-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">العنوان <span>*</span></label>
                                <div class="col-md-9">
                                    {!! Form::text("title",null,['class'=>'form-control', 'placeholder' => 'العنوان', 'required' => ''])!!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">المحتوي <span>*</span></label>
                                <div class="col-md-9">
                                    {!! Form::textarea("content",null,['class'=>'form-control', 'placeholder' => 'المحتوي', 'required' => ''])!!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-md-3 control-label">المستلم (companies)</label>
                                <div class="col-md-9">
                                    @php
                                    $def = [
                                        '' => 'no',
                                        'all' => 'الكل'
                                ];
                                        $companies = \App\Models\Company::pluck('name', 'id')->toArray();
                                    @endphp
                                    {!! Form::select("companies[]",array_merge($def, $companies),null,['class'=>"form-control select2 multiple", 'multiple' => '', 'style' =>"width: 90%; display: inline-block;"])!!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">المستلم (drivers)</label>
                                <div class="col-md-9">
                                    @php
                                        $drivers = \App\Models\Driver::pluck('first_name', 'id')->toArray();
                                    @endphp
                                    {!! Form::select("drivers[]",array_merge($def, $drivers),null,['class'=>"form-control select2 multiple", 'multiple' => '', 'style' =>"width: 90%; display: inline-block;"])!!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- end col -->

<div class="text-center col-md-12">
    <button type="submit" class="btn btn-primary waves-effect waves-light m-r-10 ">حفظ</button>
</div>