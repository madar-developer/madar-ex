@extends('admin.layout.app')
@section('style')
@endsection
@section('header')
    <div class="add-btn">
        <a href="{{ url('/dashboard/circulars/create') }}" type="button"
            class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> إضافة
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box text-left">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-tebal">
                            <div role="tabpanel" class="tab-pane " style="overflow-x: auto;">
                                <table id="datatable-buttons" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>العنوان</th>
                                            <th>النوع</th>
                                            <th>عدد الأيام</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach ($circulars as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ \App\Models\Circular::typeLabels()[$item->type] ?? $item->type }}</td>
                                                <td>{{ $item->days_count }}</td>
                                                <td class="btns">
                                                    <a href="/dashboard/circulars/{{ $item->id }}/edit" type="button"
                                                        class="btn btn-info waves-effect waves-light m-b-5 btn-xs"> <i
                                                            class="fa fa-pencil"></i> تعديل </a>
                                                    <a href="{{ route('circulars.destroy', $item) }}" id="delete-btn"
                                                        type="button"
                                                        class="btn btn-danger waves-effect waves-light m-b-5 btn-xs"> <i
                                                            class="fa fa-times"></i> حذف </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
