@extends('admin.layout.app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            {!! Form::model($template, ['url' => ['/dashboard/notification-templates/'.$template->id], 'method' => 'PATCH']) !!}
            <div class="form-group">
                <label>المفتاح</label>
                <input type="text" class="form-control" value="{{ $template->key }}" readonly>
            </div>
            <div class="form-group">
                <label>التصنيف</label>
                <input type="text" class="form-control" value="{{ \App\Models\NotificationTemplate::categoryOptions()[$template->category] ?? $template->category }}" readonly>
            </div>
            <div class="form-group">
                <label>القناة</label>
                <input type="text" class="form-control" value="{{ \App\Models\NotificationTemplate::channelOptions()[$template->channel] ?? $template->channel }}" readonly>
            </div>
            <div class="form-group">
                <label>العنوان (اختياري)</label>
                {!! Form::text('title', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group">
                <label>نص الرسالة *</label>
                {!! Form::textarea('body', null, ['class' => 'form-control', 'rows' => 5, 'required' => true]) !!}
            </div>
            <div class="form-group">
                <label>المتغيرات المتاحة</label>
                <input type="text" class="form-control" value="{{ $template->placeholders }}" readonly>
                <small class="text-muted">استخدم المتغيرات داخل النص مثل: {order_id} {status} {recipient_name}</small>
            </div>
            <div class="checkbox checkbox-primary">
                <input id="active" name="active" type="checkbox" value="1" {{ $template->active ? 'checked' : '' }}>
                <label for="active">مفعل</label>
            </div>
            <div class="text-center m-t-20">
                <button type="submit" class="btn btn-primary">حفظ</button>
                <a href="{{ url('/dashboard/notification-templates') }}" class="btn btn-default">رجوع</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
