@extends('admin.layout.app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <form action="" method="get">
                <div class="row">
                    <div class="col-lg-3">
                        {!! Form::text('q', $search['q'] ?? null, ['class' => 'form-control', 'placeholder' => 'بحث في المفتاح أو النص']) !!}
                    </div>
                    <div class="col-lg-2">
                        {!! Form::select('category', ['' => 'كل التصنيفات'] + \App\Models\NotificationTemplate::categoryOptions(), $search['category'] ?? null, ['class' => 'form-control select2']) !!}
                    </div>
                    <div class="col-lg-2">
                        {!! Form::select('channel', ['' => 'كل القنوات'] + \App\Models\NotificationTemplate::channelOptions(), $search['channel'] ?? null, ['class' => 'form-control select2']) !!}
                    </div>
                    <div class="col-lg-5 btns-row">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i> بحث</button>
                        <a href="{{ url('/dashboard/notification-templates') }}" class="btn btn-default btn-sm"><i class="fa fa-trash"></i> مسح</a>
                        <a href="{{ route('notification-templates.sync') }}" class="btn btn-warning btn-sm" onclick="return confirm('استعادة النصوص الافتراضية للنظام؟')"><i class="fa fa-refresh"></i> مزامنة الافتراضي</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>المفتاح</th>
                        <th>التصنيف</th>
                        <th>القناة</th>
                        <th>العنوان</th>
                        <th>النص</th>
                        <th>المتغيرات</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = ($templates->currentPage() - 1) * $templates->perPage() + 1; @endphp
                    @forelse($templates as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td><code>{{ $item->key }}</code></td>
                        <td>{{ \App\Models\NotificationTemplate::categoryOptions()[$item->category] ?? $item->category }}</td>
                        <td>{{ \App\Models\NotificationTemplate::channelOptions()[$item->channel] ?? $item->channel }}</td>
                        <td>{{ $item->title }}</td>
                        <td style="max-width: 320px; white-space: normal;">{{ \Illuminate\Support\Str::limit($item->body, 120) }}</td>
                        <td><small>{{ $item->placeholders }}</small></td>
                        <td>{{ $item->active ? 'مفعل' : 'معطل' }}</td>
                        <td>
                            <a href="{{ url('/dashboard/notification-templates/'.$item->id.'/edit') }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            لا توجد رسائل. اضغط <strong>مزامنة الافتراضي</strong> لاستيراد رسائل النظام.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {!! $templates->appends($search)->links() !!}
        </div>
    </div>
</div>
@endsection
