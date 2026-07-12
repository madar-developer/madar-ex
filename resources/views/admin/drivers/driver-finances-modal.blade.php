<div style="overflow-x: auto; max-height: 65vh;">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>الفرع</th>
                <th>السائق</th>
                <th>الحساب الكلي</th>
                <th>عدد الشحنات</th>
                <th>صافي الربح</th>
                <th>الحاله</th>
                <th>التحصيل من السائق</th>
                <th>تاريخ الانشاء</th>
                <th>عرض الطلبات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($driver_finances as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ @$item['Admin']['name'] }}</td>
                    <td>{{ @$item['Driver']['first_name'] }} {{ @$item['Driver']['last_name'] }}</td>
                    <td>{{ $item->total_amount }}</td>
                    <td>{{ \App\Models\Order::whereIn('id', explode(',', $item->orders))->count() }}</td>
                    <td>{{ $item->net_profit }}</td>
                    <td>
                        {!! Form::model($item, ['url' => ['/dashboard/driver-finances/'.$item->id], 'method' => 'PATCH', 'class' => 'form']) !!}
                            {!! Form::hidden('update_row', '1', []) !!}
                            {!! Form::select('status', \App\Models\DriverFianance::getLevels($item->status), null, ['class' => 'form-control select2', 'autocomplete' => 'off', 'onchange' => '$(this).closest(\'form\').submit()']) !!}
                        {!! Form::close() !!}
                    </td>
                    <td>
                        {!! Form::model($item, ['url' => ['/dashboard/driver-finances/'.$item->id], 'method' => 'PATCH', 'class' => 'form']) !!}
                            {!! Form::hidden('update_row', '1', []) !!}
                            {!! Form::select('collected_from_driver', \App\Models\DriverFianance::getDriverLevels($item->collected_from_driver), null, ['class' => 'form-control select2', 'autocomplete' => 'off', 'onchange' => '$(this).closest(\'form\').submit()']) !!}
                        {!! Form::close() !!}
                    </td>
                    <td>{{ $item->created_at->toDateString() }}</td>
                    <td>
                        <button type="button" data-url="{{ url('/dashboard/driver-finance-orders/'.$item->id) }}" class="btn btn-primary btn-sm transfer-info" data-toggle="modal" data-target="#finance-orders-modal">
                            عرض الفواتير
                        </button>
                        <a href="{{ route('driver-finance.recalculate', $item->id) }}" class="btn btn-warning waves-effect waves-light m-b-5 btn-xs" title="إعادة الحساب" onclick="return confirm('إعادة حساب المبالغ لهذه التصفية؟')">
                            <i class="fa fa-refresh"></i>
                        </a>
                        <a href="{{ route('driver-finance-collect.pdf', $item->id) }}" class="btn btn-info waves-effect waves-light m-b-5 btn-xs" title="ExportPDF">
                            <i class="fa fa-download"></i>
                        </a>
                        <a href="{{ route('driver-finance-collect.excel', $item->id) }}" class="btn btn-info waves-effect waves-light m-b-5 btn-xs" title="Export Excel">
                            <i class="fa fa-file-excel-o"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">لا توجد تصفيات</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
