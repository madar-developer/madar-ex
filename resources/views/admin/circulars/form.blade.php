<div class="col-sm-12">
    <div class="card-box text-left">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>العنوان <span>*</span></label>
                    {!! Form::text('title', isset($circular) ? $circular->title : null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>الوصف</label>
                    {!! Form::textarea('description', isset($circular) ? $circular->description : null, ['class' => 'form-control', 'rows' => 4]) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>النوع <span>*</span></label>
                    @php
                        $typeOptions = \App\Models\Circular::typeLabels();
                    @endphp
                    {!! Form::select('type', $typeOptions, isset($circular) ? $circular->type : null, ['class' => 'form-control', 'placeholder' => '— اختر —', 'required' => true]) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>عدد الأيام <span>*</span></label>
                    {!! Form::number('days_count', isset($circular) ? $circular->days_count : 0, ['class' => 'form-control', 'min' => 0, 'required' => true]) !!}
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
                            <a href="{{ url('/dashboard/circulars') }}" class="btn default cancel-button-panel">
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
