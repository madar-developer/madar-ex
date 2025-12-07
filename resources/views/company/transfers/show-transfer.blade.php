

                <div class="col-lg-12">
                    <div class="box-tebal">

                        <div role="tabpanel" class="tab-pane " style="overflow-x: auto;">
                            <table id="datatable-buttons" class="table table-striped table-bordered">
                                <thead>
                                    <tr>

                                        <th></th>
                                        <th> تاريخ اليوم </th>
                                        <th> قيمه التوصيل </th>
                                        <th> مبلغ الشحن </th>
                                        <th> الرصيد </th>
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
                                            @if (isset($ids) && in_array($item->id,$ids))

                                                <input name="invoices[]" checked="" value="{{$item->id}}" type="checkbox"/>
                                            @else

                                            <input name="invoices[]" value="{{$item->id}}" type="checkbox"/>
                                            @endif
                                        </td>
                                        <td>{{$item->created_at->todatestring()}} </td>
                                        <td>
                                            @if($item->Order->city_id == '1')
                                            {{$item->Order->Company->inside_price ?? ''}} ريال
                                            @else
                                            {{$item->Order->Company->inside_price ?? ''}} ريال
                                            @endif
                                        </td>
                                        <td>{{$item->total_price}} ريال </td>
                                        <td>{{$item->Order->balance}}</td>
                                        <td>{{$item->Order->serial }}</td>
                                        {{--  <td>#-{{$item->Order->id}}</td> --}}
                                        <td>{{$item->Order->recipent_name ?? '' }}</td>
                                        <td>{{($item->Order->active == '1')? 'لم يتم' : 'تم التحصيل '}}</td>
                                        <td> {{($item->Order->Transfer()->first())? $item->Order->Transfer()->first()->transfer_number : ''}}
                                        </td>



                                        {{--  <td>
                                                                    <a href="/dashboard/invoices/{{$item->id}}/edit">
                                        <i class="fa fa-pencil  m-r-10" style="color: #188ae2;">
                                        </i> تعديل</a>
                                        </td>  --}}

                                        {{--  <td>
                                                                     <a href="{{route('invoices.destroy',$item)}}"
                                        id="delete-btn" >
                                        <i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a>
                                        </td> --}}
                                        {{-- <td>
                                                                    <a href="{{route('users.show',$item->id)}}"
                                        class="btn waves-effect btn-default pull-right client-info" > عرض </a>
                                        </td> --}}
                                    </tr>
                                    @endforeach




                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
