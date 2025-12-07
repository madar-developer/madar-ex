
<div class="col-md-12">
    <table class="table table-bordered table-striped text-center m-0">
        <thead >
            <tr>
                <th class="text-center" colspan="4"> الحوالة</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>المتجر</td>
                <td>{{$transfer->Company->name ?? ''}}</td>
            </tr>
            <tr>
                <td> الاجمالي</td>
                <td>{{$transfer->total_price}}</td>
            </tr>
            <tr>
                <td> المستحق للشركة</td>
                <td>{{$transfer->company_price}}</td>
            </tr>
            <tr>
                <td> اجمالي تكلفه التوصيل</td>
                <td>{{$transfer->madar_price}}</td>
            </tr>
            @if ($transfer->active == '1')

            <tr>
                <td> اسم المحصل</td>
                <td>{{$transfer->collector}}</td>
            </tr>
            <tr>
                <td> نوع التحويل</td>
                <td>{{$transfer->CompanyCacheType->AvailableMethod->title ?? ''}}</td>
            </tr>
            <tr>
                <td> الحساب المحول اليه</td>
                <td>{{$transfer->CompanyCacheType->title ?? ''}} : {{$transfer->CompanyCacheType->description ?? ''}}</td>
            </tr>
            <tr>
                <td> صورة الحوالة</td>
                <td>
                    <img height="150" width="200" src="{{getImage($transfer->image)}}" /> </td>
            </tr>
            @endif
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
                                        <th>  التاريخ </th>
                                        <th>  المبلغ الاجمالي </th>
                                        <th>  المستحق للشركة </th>
                                        <th> قيمه التوصيل </th>
                                        <th> المدينة</th>
                                        <th> الطلب</th>
                                        <th> العميل</th>
                                        <th> الحاله </th>
                                        <th> رقم الحواله</th>


                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($invoices as $item)

                                    <tr>
                                        <td>

                                            {{$i++}} </td>
                                        <td>{{$item->created_at->todatestring()}} </td>
                                        <td>{{$item->total_price}} ريال </td>
                                        <td>{{$item->company_price}} ريال </td>
                                        <td>{{$item->madar_price}} ريال </td>
                                        <td>{{$item->Order->City->name ?? '' }}</td>
                                        <td>{{$item->Order->serial }}</td>
                                        {{--  <td>#-{{$item->Order->id}}</td> --}}
                                        <td>{{$item->Order->recipent_name ?? '' }}</td>
                                        <td>{{($item->active == '0')? 'لم يتم' : 'تم التحصيل '}}</td>
                                        <td> {{$item->Transfer->id ?? ''}}
                                        </td>

                                    </tr>
                                    @endforeach




                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
