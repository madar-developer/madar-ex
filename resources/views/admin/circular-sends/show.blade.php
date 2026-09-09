@extends('admin.layout.app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box text-left">
            <h4>{{ $send->title }}</h4>
            <p><strong>المرسل:</strong> {{ optional($send->admin)->name ?? '—' }}</p>
            <p><strong>تاريخ الإرسال:</strong> {{ $send->created_at ? $send->created_at->format('Y-m-d H:i') : '' }}</p>
            <p><strong>المستلمون:</strong> {{ $send->audienceSummary() }}</p>
            <p><strong>المحتوى:</strong></p>
            <p>{!! nl2br(e($send->description)) !!}</p>
        </div>
    </div>
</div>

@if($companies->isNotEmpty())
<div class="row">
    <div class="col-sm-12">
        <div class="card-box text-left">
            <h4>المتاجر ({{ $companies->count() }})</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->recipient_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($drivers->isNotEmpty())
<div class="row">
    <div class="col-sm-12">
        <div class="card-box text-left">
            <h4>السائقون ({{ $drivers->count() }})</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->recipient_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@if($admins->isNotEmpty())
<div class="row">
    <div class="col-sm-12">
        <div class="card-box text-left">
            <h4>الإدارة ({{ $admins->count() }})</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->recipient_name }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12 text-center m-b-20">
        <a href="{{ url('/dashboard/circular-sends') }}" class="btn btn-default">رجوع</a>
    </div>
</div>
@endsection
