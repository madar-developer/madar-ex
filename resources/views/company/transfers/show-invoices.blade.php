

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
