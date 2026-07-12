{!! Form::open(['url' => route('drivers.cashed-orders', $driver->id), 'method' => 'post', 'id' => 'cashed-orders-form']) !!}
@csrf
<div style="overflow-x: auto; max-height: 60vh;">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>
                    #
                    <input type="checkbox" class="ids" id="checkAllCashed" checked>
                </th>
                <th>المستحق للسائق</th>
                <th>اسم المتجر</th>
                <th>رقم تليفون المتجر</th>
                <th>اسم المستلم</th>
                <th>رقم الهاتف</th>
                <th>المدينه</th>
                <th>العنوان بالتفصيل</th>
                <th>عدد القطع بالطرد</th>
                <th>السعر</th>
                <th>تكلفة الشحن</th>
                <th>طريقه الدفع</th>
                <th>الحالة</th>
                <th>رقم المرجع</th>
                <th>رقم التسلسل</th>
                <th>تاريخ االتسليم</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $item)
                <tr>
                    <td>
                        {{ $loop->iteration }}
                        <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="ids cashed-order-id" checked>
                    </td>
                    <td>{{ \App\Support\DriverFinance::driverCommission($item, $driver) }}</td>
                    <td>{{ @$item['company']['name'] }}</td>
                    <td>{{ @$item['company']['phone'] }}</td>
                    <td>{{ $item->recipent_name }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ @$item['City']['name'] }}</td>
                    <td>{{ $item->adress_details }}</td>
                    <td>{{ $item->packages_number }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ \App\Support\DriverFinance::codShipmentCost($item) }}</td>
                    <td>{{ @$item['PaymentMethod']['name'] }}</td>
                    <td>{{ __('words.'.$item->status) }}</td>
                    <td>{{ $item->refrence_no }}</td>
                    <td>{{ $item->serial }}</td>
                    <td>{{ $item->delivery_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="text-center">لا توجد طلبات للتصفية</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if ($orders->count() > 0)
    <div class="m-t-15 text-left">
        <button type="submit" class="btn btn-success" onclick="return confirm('هل تريد تصفية الطلبات المحددة من السائق؟')">
            تصفية الطلبات من السائق
        </button>
    </div>
@endif
{!! Form::close() !!}
