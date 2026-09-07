@extends('admin.layout.app')
@section('header')
<div class="add-btn">
    <a href="{{ url('/dashboard/message-templates/create') }}" type="button"
        class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5">
        <i class="fa fa-check"></i> إضافة قالب
    </a>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <form action="" method="get">
                <div class="row">
                    <div class="col-lg-3">
                        {!! Form::text('q', $search['q'] ?? null, ['class' => 'form-control', 'placeholder' => 'بحث في الاسم أو النص']) !!}
                    </div>
                    <div class="col-lg-3">
                        {!! Form::select('status', ['' => 'كل الحالات'] + OrderStatus(), $search['status'] ?? null, ['class' => 'form-control select2']) !!}
                    </div>
                    <div class="col-lg-3">
                        {!! Form::select('company_id', ['' => 'كل المتاجر'] + $companies, $search['company_id'] ?? null, ['class' => 'form-control select2']) !!}
                    </div>
                    <div class="col-lg-3">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i> بحث</button>
                        <a href="{{ url('/dashboard/message-templates') }}" class="btn btn-default btn-sm">مسح</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card-box text-left">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الحالة</th>
                        <th>المتاجر</th>
                        <th>النص</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = ($templates->currentPage() - 1) * $templates->perPage() + 1; @endphp
                    @forelse($templates as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ OrderStatus()[$item->status] ?? $item->status }}</td>
                        <td>{{ $item->companies->pluck('name')->implode('، ') }}</td>
                        <td style="max-width: 280px; white-space: normal;">{{ \Illuminate\Support\Str::limit($item->body, 100) }}</td>
                        <td>
                            @if($item->active)
                                <span class="label label-success">مفعّل</span>
                            @else
                                <span class="label label-danger">معطّل</span>
                            @endif
                        </td>
                        <td class="btns">
                            <a href="/dashboard/message-templates/{{ $item->id }}/edit"
                                class="btn btn-info waves-effect waves-light m-b-5 btn-xs">
                                <i class="fa fa-pencil"></i> تعديل
                            </a>
                            <a href="{{ route('message-templates.destroy', $item) }}" id="delete-btn"
                                class="btn btn-danger waves-effect waves-light m-b-5 btn-xs">
                                <i class="fa fa-times"></i> حذف
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">لا توجد قوالب بعد.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {!! $templates->appends($search)->links() !!}
        </div>
    </div>
</div>
@endsection
