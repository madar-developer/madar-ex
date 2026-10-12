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

            <div class="col-md-12 m-t-10">
                <hr>
                <h5 class="m-b-15">إعدادات واتساب (Meta Cloud API)</h5>
            </div>
            <div class="col-md-12">
                <div class="checkbox checkbox-primary">
                    <input id="whatsapp_enabled" name="whatsapp_enabled" type="checkbox" value="1"
                        {{ old('whatsapp_enabled', isset($template) ? $template->whatsapp_enabled : false) ? 'checked' : '' }}>
                    <label for="whatsapp_enabled">تفعيل إرسال واتساب لهذا القالب</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>اسم قالب واتساب (Template Name)</label>
                    {!! Form::text(
                        'whatsapp_template_name',
                        old('whatsapp_template_name', isset($template) ? $template->whatsapp_template_name : null),
                        ['class' => 'form-control', 'placeholder' => 'delivery_template_for_an_immediate_customer']
                    ) !!}
                    <small class="text-muted">يجب أن يطابق الاسم المعتمد في Meta حرفياً.</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>معرّف قالب واتساب (اختياري)</label>
                    {!! Form::text(
                        'whatsapp_template_id',
                        old('whatsapp_template_id', isset($template) ? $template->whatsapp_template_id : null),
                        ['class' => 'form-control', 'placeholder' => 'Meta template ID']
                    ) !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>لغة القالب</label>
                    {!! Form::text(
                        'whatsapp_language',
                        old('whatsapp_language', isset($template) ? ($template->whatsapp_language ?: 'ar') : 'ar'),
                        ['class' => 'form-control', 'placeholder' => 'ar']
                    ) !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>ترتيب متغيرات واتساب (Body)</label>
                    @php
                        $waVars = old(
                            'whatsapp_variables',
                            isset($template) && is_array($template->whatsapp_variables)
                                ? implode(',', $template->whatsapp_variables)
                                : 'recipient_name,refrence_no,company_name,payment_method,amount,serial'
                        );
                    @endphp
                    {!! Form::text('whatsapp_variables', $waVars, [
                        'class' => 'form-control',
                        'placeholder' => 'recipient_name,refrence_no,company_name,payment_method,amount,serial',
                    ]) !!}
                    <small class="text-muted">
                        بالترتيب نفسه في قالب Meta، مفصولة بفواصل. المفاتيح المتاحة:
                        @foreach(\App\Models\MessageTemplate::placeholders() as $key => $label)
                            <code>{{ $key }}</code>@if(!$loop->last) ، @endif
                        @endforeach
                    </small>
                </div>
            </div>

            <div class="col-md-12 text-center m-t-15">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> حفظ</button>
                <a href="{{ url('/dashboard/message-templates') }}" class="btn btn-default">إلغاء</a>
            </div>
        </div>
    </div>
</div>
