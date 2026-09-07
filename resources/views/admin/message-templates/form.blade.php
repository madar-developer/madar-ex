@if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="col-sm-12">
    <div class="card-box text-left">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>اسم القالب <span>*</span></label>
                    {!! Form::text('name', isset($template) ? $template->name : null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>حالة الطلب <span>*</span></label>
                    {!! Form::select('status', OrderStatus(), isset($template) ? $template->status : null, ['class' => 'form-control select2', 'placeholder' => '— اختر الحالة —', 'required' => true]) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>المتاجر <span>*</span></label>
                    @php
                        $selectedCompanies = old('company_ids', isset($template) ? $template->companies->pluck('id')->all() : []);
                    @endphp
                    {!! Form::select('company_ids[]', $companies, $selectedCompanies, ['class' => 'form-control select2', 'multiple' => 'multiple', 'required' => true, 'style' => 'width: 100%;']) !!}
                    <small class="text-muted">يمكن ربط القالب بمتجر واحد أو أكثر. المتجر لا يمكن أن يملك قالبين لنفس الحالة.</small>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>نص الرسالة <span>*</span></label>
                    {!! Form::textarea('body', isset($template) ? $template->body : null, ['class' => 'form-control', 'rows' => 5, 'required' => true]) !!}
                    <small class="text-muted">
                        المتغيرات المتاحة:
                        @foreach(\App\Models\MessageTemplate::placeholders() as $key => $label)
                            <code>{{ '{'.$key.'}' }}</code> ({{ $label }})@if(!$loop->last) ، @endif
                        @endforeach
                    </small>
                </div>
            </div>
            <div class="col-md-12">
                <div class="checkbox checkbox-primary">
                    <input id="active" name="active" type="checkbox" value="1" {{ old('active', isset($template) ? $template->active : true) ? 'checked' : '' }}>
                    <label for="active">مفعّل</label>
                </div>
            </div>
            <div class="col-md-12 text-center m-t-15">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> حفظ</button>
                <a href="{{ url('/dashboard/message-templates') }}" class="btn btn-default">إلغاء</a>
            </div>
        </div>
    </div>
</div>
