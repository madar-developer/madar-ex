@extends('admin.layout.app')
@section('header')
    <div class="add-btn">
        <a href="{{ url('/dashboard/notifications') }}" type="button"
            class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5">
            <i class="fa fa-send"></i> إرسال تعميم
        </a>
    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <form action="" method="get">
                <div class="row">
                    <div class="col-lg-4">
                        {!! Form::text('q', $search['q'] ?? null, ['class' => 'form-control', 'placeholder' => 'بحث في العنوان أو المحتوى']) !!}
                    </div>
                    <div class="col-lg-4">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i> بحث</button>
                        <a href="{{ url('/dashboard/circular-sends') }}" class="btn btn-default btn-sm">مسح</a>
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
                        <th>العنوان</th>
                        <th>المرسل</th>
                        <th>المستلمون</th>
                        <th>تاريخ الإرسال</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = ($sends->currentPage() - 1) * $sends->perPage() + 1; @endphp
                    @forelse($sends as $item)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $item->title }}</td>
                        <td>{{ optional($item->admin)->name ?? '—' }}</td>
                        <td>{{ $item->audienceSummary() }}</td>
                        <td>{{ $item->created_at ? $item->created_at->format('Y-m-d H:i') : '' }}</td>
                        <td class="btns">
                            <a href="{{ url('/dashboard/circular-sends/'.$item->id) }}"
                                class="btn btn-info waves-effect waves-light m-b-5 btn-xs">
                                <i class="fa fa-eye"></i> عرض
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">لا يوجد إرسال تعاميم بعد.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {!! $sends->appends($search)->links() !!}
        </div>
    </div>
</div>
@endsection
