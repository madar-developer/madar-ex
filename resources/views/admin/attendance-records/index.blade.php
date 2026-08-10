@extends('admin.layout.app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box text-left">
            <form method="GET" action="{{ url('/dashboard/attendance-records') }}" class="form-inline m-b-20">
                <div class="form-group m-r-10">
                    <label class="m-r-5">السائق</label>
                    <select name="driver_id" class="form-control">
                        <option value="">الكل</option>
                        @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                            {{ $driver->first_name }} {{ $driver->last_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group m-r-10">
                    <label class="m-r-5">النوع</label>
                    <select name="type" class="form-control">
                        <option value="">الكل</option>
                        <option value="check_in" {{ request('type') == 'check_in' ? 'selected' : '' }}>حضور</option>
                        <option value="check_out" {{ request('type') == 'check_out' ? 'selected' : '' }}>انصراف</option>
                    </select>
                </div>
                <div class="form-group m-r-10">
                    <label class="m-r-5">التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="form-group m-r-10">
                    <label class="m-r-5">الحالة</label>
                    <select name="within_geofence" class="form-control">
                        <option value="">الكل</option>
                        <option value="1" {{ request('within_geofence') === '1' ? 'selected' : '' }}>مقبول</option>
                        <option value="0" {{ request('within_geofence') === '0' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">بحث</button>
            </form>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>السائق</th>
                        <th>النوع</th>
                        <th>الدائرة</th>
                        <th>المسافة (م)</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>ملاحظات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $i => $record)
                    <tr>
                        <td>{{ $records->firstItem() + $i }}</td>
                        <td>{{ $record->driver ? $record->driver->first_name . ' ' . $record->driver->last_name : '-' }}</td>
                        <td>{{ $record->type === 'check_in' ? 'حضور' : 'انصراف' }}</td>
                        <td>{{ $record->geofence ? $record->geofence->name : '-' }}</td>
                        <td>{{ $record->distance_meters !== null ? round($record->distance_meters) : '-' }}</td>
                        <td>
                            @if($record->within_geofence)
                                <span class="label label-success">مقبول</span>
                            @else
                                <span class="label label-danger">مرفوض</span>
                            @endif
                        </td>
                        <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ $record->notes ?: '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">لا توجد سجلات</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $records->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
