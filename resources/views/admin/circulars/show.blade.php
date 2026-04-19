@extends('admin.layout.app')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box text-left">
                <h4>{{ $circular->title }}</h4>
                <p><strong>النوع:</strong> {{ \App\Models\Circular::typeLabels()[$circular->type] ?? $circular->type }}</p>
                <p><strong>عدد الأيام:</strong> {{ $circular->days_count }}</p>
                <p><strong>الوصف:</strong></p>
                <p>{{ $circular->description }}</p>
                <a href="{{ url('/dashboard/circulars') }}" class="btn btn-default">رجوع</a>
                <a href="{{ url('/dashboard/circulars/' . $circular->id . '/edit') }}" class="btn btn-info">تعديل</a>
            </div>
        </div>
    </div>
@endsection
