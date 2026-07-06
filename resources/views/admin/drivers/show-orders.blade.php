
<div class="col-md-12">
    <table class="table table-bordered table-striped text-center m-0">
        <thead >
            <tr>
                <th class="text-center" colspan="4"> التفاصيل</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>السائق</td>
                <td colspan="3">{{$driver->first_name ?? ''}} {{$driver->last_name ?? ''}} ({{ $driver->commission ?? 0 }}%)</td>
            </tr>
            <tr>
                <td>الحساب الكلي (COD)</td>
                <td colspan="3">{{$row->total_amount}}</td>
            </tr>
            <tr>
                <td>حساب السائق</td>
                <td colspan="3">{{$row->driver_amount}}</td>
            </tr>
            <tr>
                <td>صافي الربح</td>
                <td colspan="3">{{$row->net_profit}}</td>
            </tr>
            <tr>
                <td>تاريخ الانشاء</td>
                <td colspan="3">{{$row->created_at->toDateString()}}</td>
            </tr>

        </tbody>
    </table>
</div>
                <div class="col-lg-12">
                    <div class="box-tebal">

                        <div role="tabpanel" class="tab-pane " style="overflow-x: auto;">
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                                <thead>
                                    @php
                                    $i = 1;
                                    @endphp
                                    <tr>
                                        <th>
                                            #
                                        </th>
                                        <th> اسم المتجر </th>
                                        <th> رقم تليفون المتجر </th>
                                        <th> اسم المستلم </th>
                                        <th> رقم التليفون</th>
                                        @if (auth('admin')->user()->role == 'admin')
                                        <th> تم الاضافه بواسطه</th>
                                        @endif

                                        <th> المدينه </th>
                                        <th> السعر</th>
                                        @include('admin.partials.driver-finance-order-headers')
                                        <th> طريقه الدفع </th>
                                        <th> الحالة </th>
                                        <th> رقم المرجع </th>
                                        <th> رقم التسلسل </th>
                                        <th> تاريخ الانشاء</th>



                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($orders as $item)

                                    <tr>
                                        <td>

                                            {{$i++}} </td>
                                            <td>{{$item->Company->name ?? ''}} </td>
                                            <td>{{$item->Company->phone ?? ''}} </td>
                                            <td>{{$item->recipent_name}} </td>
                                            <td>{{$item->phone}} </td>
                                            @if (auth('admin')->user()->role == 'admin')
                                            <td>{{$item->BranchData->Admin->name ?? ''  }}</td>
                                            @endif
                                            <td> {{ $item->City->name ?? '' }}</td>
                                            <td>{{$item->price}}</td>
                                            @include('admin.partials.driver-finance-order-cells', ['financeDriver' => $driver])
                                            <td>{{$item->PaymentMethod->name ?? '' }}</td>

                                            <td>
                                                {{@$item->OrderLog()->where('status', $item->status)->latest()->first()->details}}
                                            </td>
                                            <td>{{$item->refrence_no}}</td>
                                            <td>{{$item->serial}}</td>
                                            <td>{{$item->created_at}}</td>


                                    </tr>
                                    @endforeach




                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
