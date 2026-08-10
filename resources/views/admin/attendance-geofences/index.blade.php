@extends('admin.layout.app')
@section('style')
<style type="text/css">
    #mapCanvas {
        width: 100%;
        height: 400px;
        margin-top: 10px;
    }
</style>
@endsection
@section('header')
<div class="add-btn">
    <a href="{{ url('/dashboard/attendance-geofences/create') }}" type="button"
        class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5">
        <i class="fa fa-check"></i> إضافة دائرة
    </a>
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box text-left">
            <table id="datatable-buttons" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>خط العرض</th>
                        <th>خط الطول</th>
                        <th>نصف القطر (متر)</th>
                        <th>الحالة</th>
                        <th>العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($geofences as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->latitude }}</td>
                        <td>{{ $item->longitude }}</td>
                        <td>{{ $item->radius_meters }}</td>
                        <td>
                            @if($item->active)
                                <span class="label label-success">مفعّل</span>
                            @else
                                <span class="label label-danger">معطّل</span>
                            @endif
                        </td>
                        <td class="btns">
                            <a href="/dashboard/attendance-geofences/{{ $item->id }}/edit"
                                class="btn btn-info waves-effect waves-light m-b-5 btn-xs">
                                <i class="fa fa-pencil"></i> تعديل
                            </a>
                            <a href="{{ route('attendance-geofences.destroy', $item) }}" id="delete-btn"
                                class="btn btn-danger waves-effect waves-light m-b-5 btn-xs">
                                <i class="fa fa-times"></i> حذف
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
