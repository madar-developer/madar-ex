@extends('admin.layout.app')
@section('style')
<style>
    .text-muted {
        color: #000 !important;
    }
    .morris-hover.morris-default-style {
    position: absolute;
    }

</style>
@endsection
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-color panel-tabs panel-success">
            <div class="panel-heading panel-heading-custom">
                <ul class="nav nav-pills">
                    <li class="active">
                        <a href="#navpills-1" data-toggle="tab" aria-expanded="true">البيانات الأساسية</a>
                    </li>
                    <li class="">
                        <a href="#navpills-2" data-toggle="tab" aria-expanded="false">الطلبات</a>
                    </li>
                    {{-- <li class="">
                        <a href="#navpills-3" data-toggle="tab" aria-expanded="false">طلبات يجب تحصيلها</a>
                    </li> --}}
                    <li class="">
                        <a href="#navpills-3" data-toggle="tab" aria-expanded="false"> قيد التسوية </a>
                    </li>
                    <li class="">
                        <a href="#navpills-7" data-toggle="tab" aria-expanded="false">كشف حساب </a>
                    </li>
                    <li class="">
                        <a href="#navpills-5" data-toggle="tab" aria-expanded="false">المستندات</a>
                    </li>
                    <li class="">
                        <a href="#navpills-6" data-toggle="tab" aria-expanded="false">تكلفة التوصيل</a>
                    </li>
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div id="navpills-1" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped text-center m-0">
                                    <thead>
                                        {{-- <tr>
                                            <th colspan="2" class="text-center">
                                                <img src=" {{ $driver->image }}" style="width: 100px; height: 100px;border-radius: 50%;border: 2px solid #2c0d0d;" />
                                            </th>
                                        </tr> --}}
                                        <tr>
                                            <th class="text-center" colspan="4">البيانات الأساسية</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>الاسم الاول</td>
                                            <td>{{$driver->first_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>الاسم الاخير</td>
                                            <td>{{$driver->last_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>البريد الالكتروني</td>
                                            <td>{{$driver->email}}</td>
                                        </tr>
                                        <tr>
                                            <td>رقم الجوال</td>
                                            <td>{{$driver->phone}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                 <table class="table table-bordered table-striped text-center m-0">
                                                    <thead >
                                                        <tr>
                                                            <th class="text-center" colspan="4">بيانات أخرى</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td> نوع السيارة</td>
                                                            <td>{{$driver->Car->name ?? ''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td> المدينة </td>
                                                            <td>{{$driver->phone}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td> المنطقة</td>
                                                            <td>{{$driver->address}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td> الجنسية</td>
                                                            <td>{{$driver->nationality}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                            </div>
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <!--<h4 class="header-title m-t-0">التوصيل بالايام</h4>-->
                                    <h4 class="header-title m-t-0">التسليم بالايام</h4>
                                    <div id="morris-bar-example" style="height: 280px;"></div>
                                </div>
                            </div><!-- end col -->
                            <div class="col-lg-6">
                                <div class="card-box">
                                    <h4 class="header-title m-t-0">التسليم بالشهور</h4>
                                    <div id="morris-bar-example2" style="height: 280px;"></div>
                                </div>
                            </div><!-- end col -->
                        </div>
                    </div>
                    <div id="navpills-2" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        @php
                                            $i = 1;
                                        @endphp
                                        <tr>

                                            <th>
                                                #
                                            </th>
                                            {{--  <th>IDs </th>  --}}
                                            <th>  اسم المتجر </th>
                                            <th>   رقم تليفون المتجر </th>
                                            <th>  اسم المستلم </th>
                                            <th>    رقم الجوال</th>
                                            <th>   المدينه	   </th>
                                            <th>   العنوان بالتفصيل	   </th>
                                            <th>    عدد القطع بالطرد	   </th>
                                            <th>  السعر</th>
                                            <th>  طريقه الدفع </th>
                                            {{--  <th>   ملحوظات </th>  --}}
                                            <th>    الحالة </th>
                                            <th>    رقم المرجع </th>
                                            <th>  رقم التسلسل </th>
                                            <th>    تاريخ الانشاء</th>
                                            <th>    العمليات </th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($n_orders as $item)

                                        <tr>
                                            <td>
                                                {{$i++}}
                                            </td>
                                            <td>{{@$item['company']['name']}} </td>
                                            <td>{{@$item['company']['phone']}} </td>
                                            <td>{{$item->recipent_name}} </td>
                                            <td>{{$item->phone}} </td>
                                            <td> {{@$item['City']['name']}}</td>
                                            <td>{{$item->adress_details}} </td>
                                            <td>{{$item->packages_number}} </td>
                                            <td>{{$item->price}}</td>
                                            <td>{{@$item['PaymentMethod']['name']}}</td>
                                               <td>
                                                    {!!Form::model($item , ['url' => ['/dashboard/orders/'.$item->id] , 'method'
                                                    => 'PATCH','files'=>true , 'class'=>'form']) !!}
                                                    {!! Form::select("status",OrderStatus($item->status),null,['class'=>"form-control select2", "autocomplete"=> 'off', "onchange" => "$(this).closest('form').submit()"])!!}
                                                    {!!Form::close() !!}
                                                </td>
                                            <td>{{$item->refrence_no}}</td>
                                            <td>{{$item->serial}}</td>
                                            <td>{{$item->created_at}}</td>


                                            <td class="btns">

                                                    <a href="/dashboard/orders/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> تعديل </a>
                                                    <a href="{{route('orders.destroy',$item)}}" type="button" class="btn btn-danger delete-btn  waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>
                                                    <a href="/dashboard/orders/{{$item->id}}" type="button"
                                                        class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                            class="fa fa-eye"></i> عرض </a>
                                                    <a href="/dashboard/order-bill/{{$item->id}}" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> طباعه بوليصه الشحن </a>

                                                </td>


                                        </tr>
                                        @endforeach




                                    </tbody>
                                </table>
                                {!! $n_orders->links() !!}
                            </div>
                        </div>
                    </div>
                    @if (0)
                        <div id="navpills-3" class="tab-pane fade">
                            <div class="row">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card-box widget-user" style=" background-color: #ccc; ">
                                            <div class="text-center">
                                                <h2 class="text-info" data-plugin="counterup">{{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('total_price')}} ر.س</h2>
                                                <h5> المبلغ الاجمالي</h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card-box widget-user" style=" background-color: #ccc; ">
                                            <div class="text-center">
                                                <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('total_price') - ($driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->count() * $driver->commission)}} ر.س</h2>
                                                <h5>المستحق للفرع   </h5>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card-box widget-user" style=" background-color: #ccc; ">
                                            <div class="text-center">
                                                <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->count() * $driver->commission}} ر.س</h2>
                                                <h5>المستحق للسائق</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            @php
                                                $i = 1;
                                            @endphp
                                            <tr>

                                                <th>
                                                    #
                                                </th>
                                                {{--  <th>IDs </th>  --}}
                                                <th>  اسم المتجر </th>
                                                <th>   رقم تليفون المتجر </th>
                                                <th>  اسم المستلم </th>
                                                <th>    رقم الجوال</th>
                                                <th>   المدينه	   </th>
                                                <th>   العنوان بالتفصيل	   </th>
                                                <th>    عدد القطع بالطرد	   </th>
                                                <th>  السعر</th>
                                                <th>  طريقه الدفع </th>
                                                {{--  <th>   ملحوظات </th>  --}}
                                                <th>    الحالة </th>
                                                <th>    رقم المرجع </th>
                                                <th>  رقم التسلسل </th>
                                                <th>    تاريخ الانشاء</th>
                                                <th>    العمليات </th>


                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($n0_orders as $item)

                                            <tr>
                                                <td>
                                                    {{$i++}}
                                                </td>
                                                <td>{{@$item['company']['name']}} </td>
                                                <td>{{@$item['company']['phone']}} </td>
                                                <td>{{$item->recipent_name}} </td>
                                                <td>{{$item->phone}} </td>
                                                <td> {{@$item['City']['name']}}</td>
                                                <td>{{$item->adress_details}} </td>
                                                <td>{{$item->packages_number}} </td>
                                                <td>{{$item->price}}</td>
                                                <td>{{@$item['PaymentMethod']['name']}}</td>
                                                <td>
                                                    {{__('words.'.$item->status)}}
                                                </td>
                                                <td>{{$item->refrence_no}}</td>
                                                <td>{{$item->serial}}</td>
                                                <td>{{$item->created_at}}</td>


                                                <td class="btns">
                                                        <a href="/dashboard/orders/{{$item->id}}" type="button"
                                                            class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                                class="fa fa-eye"></i> عرض </a>
                                                        <a href="/dashboard/order-bill/{{$item->id}}" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> طباعه بوليصه الشحن </a>
                                                    </td>


                                            </tr>
                                            @endforeach




                                        </tbody>
                                    </table>
                                    {!! $n0_orders->links() !!}
                                    @if ($driver->Order()->where('status', 'delivered')->where('collected', 0)->count() > 0)
                                        <a href="{{ route('drivers.collect-orders', $driver->id) }}" class="btn btn-success">تحصيل من السائق</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div id="navpills-3" class="tab-pane fade">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->count()}} شحنة</h2>
                                            <h5>اجمالي عدد الشحنات</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->sum('orders.price')}} ريال</h2>
                                            <h5>اجمالي مبالغ الشحنات</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.cash_type', 'cash')->sum('orders.price')}} ريال</h2>
                                            <h5>اجمالي التحصيل نقدا</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.cash_type', 'network')->sum('orders.price')}} ريال</h2>
                                            <h5>اجمالي التحصيل شبكة</h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 hidden">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 1)->sum('invoices.driver_cost')}} ريال</h2>
                                            <h5> اجمالي المبالغ التي تم الحصول عليها (السائق)</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 hidden">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 1)->sum('invoices.total_price')}} ريال</h2>
                                            <h5> اجمالي المبالغ التي تم الحصول عليها (البضاعة)</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('invoices.driver_cost')}} ريال</h2>
                                            <h5>المستحق للسائق</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('total_price') - $driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('invoices.driver_cost') }} ريال</h2>
                                            <h5>المستحق للفرع   </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('total_price') }} ريال</h2>
                                            <h5>اجمالي البضاعة     </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Order()->count() }} </h2>
                                            <h5>اجمالى عدد الطلبات </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Order()->whereNotIn('status', ['deliver_failed', 'reschedule'])->where('collected','<>' ,'1')->count() }} </h2>
                                            <h5>طلبات قيد التسويه</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Order()->where('status', 'delivered')->where('collected', 1)->count() }} </h2>
                                            <h5>طلبات تم تسويتها</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card-box widget-user" style=" background-color: #ccc; ">
                                        <div class="text-center">
                                            <h2 class="text-info" data-plugin="counterup"> {{$driver->Order()->whereNotIn('status', ['delivered', 'at_madar', 'returned'])->where('collected', 1)->count() }} </h2>
                                            <h5>طلبات لم تسلم </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="panel-heading panel-heading-custom">
                                    <ul class="nav nav-tabs nav-justified">
                                        <li role="presentation" class="active">
                                            <a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home"aria-expanded="true">طلبات قيد التسوية</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile">طلبات تم تسويتها</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#profile2" role="tab" id="profile2-tab" data-toggle="tab" aria-controls="profile2">طلبات لم تسلم</a>
                                        </li>

                                    </ul>
                                </div>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
                                        <div class="col-md-12">
                                            <h4>طلبات قيد التسوية</h4>
                                            {!! Form::open(['url' => route('drivers.collect-orders', $driver->id), 'method'=>'post']) !!}
                                            @csrf
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    <tr>

                                                        <th>
                                                            #
                                                            {{-- <input type="checkbox" class="ids" id="checkAll"> --}}
                                                        </th>
                                                         {{-- <th>IDs </th>  --}}
                                                        <th>  اسم المتجر </th>
                                                        <th>   رقم تليفون المتجر </th>
                                                        <th>  اسم المستلم </th>
                                                        <th> رقم الهاتف</th>
                                                        <th> المدينه	   </th>
                                                        <th>   العنوان بالتفصيل	   </th>
                                                        <th> عدد القطع بالطرد </th>
                                                        <th>  السعر</th>
                                                        <th>  تكلفة الشحن</th>
                                                        <th>  طريقه الدفع </th>
                                                        {{--  <th>   ملحوظات </th>  --}}
                                                        <th>    الحالة </th>
                                                        <th>    رقم المرجع </th>
                                                        <th>  رقم التسلسل </th>
                                                        <th>    تاريخ الانشاء</th>
                                                        <th>    العمليات </th>


                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($orders_not_col as $item)

                                                    <tr>
                                                        <td>
                                                            {{$i++}}
                                                            {{-- <input type="checkbox" name="ids[]" value="{{$item->id}}" class="ids"/> --}}
                                                        </td>
                                                        <td>{{@$item['company']['name']}} </td>
                                                        <td>{{@$item['company']['phone']}} </td>
                                                        <td>{{$item->recipent_name}} </td>
                                                        <td>{{$item->phone}} </td>
                                                        <td> {{@$item['City']['name']}}</td>
                                                        <td>{{$item->adress_details}} </td>
                                                        <td>{{$item->packages_number}} </td>
                                                        <td>{{$item->price}}</td>
                                                        <td>{{$item->Invoice->madar_price ?? ''}}</td>
                                                        <td>{{@$item['PaymentMethod']['name']}}</td>
                                                        <td>
                                                            {{__('words.'.$item->status)}}
                                                        </td>
                                                        <td>{{$item->refrence_no}}</td>
                                                        <td>{{$item->serial}}</td>
                                                        <td>{{$item->created_at}}</td>


                                                        <td class="btns">
                                                                <a href="/dashboard/orders/{{$item->id}}" type="button"
                                                                    class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                                        class="fa fa-eye"></i>  </a>
                                                                <a href="/dashboard/order-bill/{{$item->id}}" type="button" class="btn btn-primary   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-print"></i>    </a>
                                                            </td>


                                                    </tr>
                                                    @endforeach




                                                </tbody>
                                            </table>
                                            {!! $orders_not_col->links() !!}
                                            @if ($driver->Order()->where('status', 'delivered')->where('collected', 0)->count() > 0)
                                                {{-- <button type="submit" href="{{ route('drivers.collect-orders', $driver->id) }}" class="btn btn-success">تسويةالطلبات من السائق</button> --}}
                                            @endif
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="profile" aria-labelledby="profile-tab">
                                        <div class="col-md-12">
                                            <h4>طلبات تم تسويتها</h4>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    <tr>

                                                        <th>
                                                            #
                                                        </th>
                                                        {{--  <th>IDs </th>  --}}
                                                        <th>  اسم المتجر </th>
                                                        <th>   رقم تليفون المتجر </th>
                                                        <th>  اسم المستلم </th>
                                                        <th>    رقم الهاتف</th>
                                                        <th>   المدينه	   </th>
                                                        <th>   العنوان بالتفصيل	   </th>
                                                        <th>    عدد القطع بالطرد	   </th>
                                                        <th>  السعر</th>
                                                        <th>  طريقه الدفع </th>
                                                        {{--  <th>   ملحوظات </th>  --}}
                                                        <th>    الحالة </th>
                                                        <th>    رقم المرجع </th>
                                                        <th>  رقم التسلسل </th>
                                                        <th>    تاريخ الانشاء</th>
                                                        <th>    العمليات </th>


                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($orders_col as $item)

                                                    <tr>
                                                        <td>
                                                            {{$i++}}
                                                        </td>
                                                        <td>{{@$item['company']['name']}} </td>
                                                        <td>{{@$item['company']['phone']}} </td>
                                                        <td>{{$item->recipent_name}} </td>
                                                        <td>{{$item->phone}} </td>
                                                        <td> {{@$item['City']['name']}}</td>
                                                        <td>{{$item->adress_details}} </td>
                                                        <td>{{$item->packages_number}} </td>
                                                        <td>{{$item->price}}</td>
                                                        <td>{{@$item['PaymentMethod']['name']}}</td>
                                                        <td>
                                                            {{__('words.'.$item->status)}}
                                                        </td>
                                                        <td>{{$item->refrence_no}}</td>
                                                        <td>{{$item->serial}}</td>
                                                        <td>{{$item->created_at}}</td>


                                                        <td class="btns">
                                                                <a href="/dashboard/orders/{{$item->id}}" type="button"
                                                                    class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                                        class="fa fa-eye"></i>  </a>
                                                                <a href="/dashboard/order-bill/{{$item->id}}" type="button" class="btn btn-primary   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-print"></i> </a>
                                                            </td>


                                                    </tr>
                                                    @endforeach




                                                </tbody>
                                            </table>
                                            {!! $orders_col->links() !!}
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="profile2" aria-labelledby="profile2-tab">
                                        <div class="col-md-12">
                                            <h4>طلبات لم تسلم</h4>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    <tr>

                                                        <th>
                                                            #
                                                        </th>
                                                        {{--  <th>IDs </th>  --}}
                                                        <th>  اسم المتجر </th>
                                                        <th>   رقم تليفون المتجر </th>
                                                        <th>  اسم المستلم </th>
                                                        <th>    رقم الهاتف</th>
                                                        <th>   المدينه	   </th>
                                                        <th>   العنوان بالتفصيل	   </th>
                                                        <th>    عدد القطع بالطرد	   </th>
                                                        <th>  السعر</th>
                                                        <th>  طريقه الدفع </th>
                                                        {{--  <th>   ملحوظات </th>  --}}
                                                        <th>    الحالة </th>
                                                        <th>    رقم المرجع </th>
                                                        <th>  رقم التسلسل </th>
                                                        <th>    تاريخ الانشاء</th>
                                                        <th>    العمليات </th>


                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($orders_not_delivered as $item)

                                                    <tr>
                                                        <td>
                                                            {{$i++}}
                                                        </td>
                                                        <td>{{@$item['company']['name']}} </td>
                                                        <td>{{@$item['company']['phone']}} </td>
                                                        <td>{{$item->recipent_name}} </td>
                                                        <td>{{$item->phone}} </td>
                                                        <td> {{@$item['City']['name']}}</td>
                                                        <td>{{$item->adress_details}} </td>
                                                        <td>{{$item->packages_number}} </td>
                                                        <td>{{$item->price}}</td>
                                                        <td>{{@$item['PaymentMethod']['name']}}</td>
                                                        <td>
                                                            {{__('words.'.$item->status)}}
                                                        </td>
                                                        <td>{{$item->refrence_no}}</td>
                                                        <td>{{$item->serial}}</td>
                                                        <td>{{$item->created_at}}</td>


                                                        <td class="btns">
                                                                <a href="/dashboard/orders/{{$item->id}}" type="button"
                                                                    class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                                        class="fa fa-eye"></i>  </a>
                                                                <a href="/dashboard/order-bill/{{$item->id}}" type="button" class="btn btn-primary   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-print"></i> </a>
                                                            </td>


                                                    </tr>
                                                    @endforeach




                                                </tbody>
                                            </table>
                                            {!! $orders_col->links() !!}
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                    <div id="navpills-5" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                {!!Form::open( ['url' => route('drivers-files', $driver->id) ,'method' => 'Post','files' => true]) !!}
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">  الملف :</label>
                                        <div class=" col-md-6">
                                            {!! Form::file("file",['class'=>"form-control", "autocomplete"=> 'off'])!!}
                                        </div>
                                        <div class=" col-md-3">
                                            <button type="submit" class="btn blue">
                                                <i class="fa fa-check"></i>
                                                حفظ
                                            </button>
                                        </div>
                                    </div>
                                {!!Form::close() !!}
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered text-right">
                                    <thead>
                                        @php
                                            $i = 1;
                                        @endphp
                                        <tr>

                                            <th>
                                                #
                                            </th>
                                            <th>المحتوي</th>
                                            <th>    العمليات </th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($driver->Files()->latest()->get() as $item)

                                            <tr>
                                                <td>
                                                    {{$i++}}
                                                </td>
                                                <td>{!! FileHtmlContent($item->name) !!} </td>
                                                <td class="btns">
                                                    <a href="{{route('files.destroy',$item->id)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>

                                                </td>


                                            </tr>
                                        @endforeach




                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="navpills-6" class="tab-pane fade">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-4">
                                <button type="button" data-route="{{route('driver-prices.create')}}?driver_id={{$driver->id}}" class="load-ajax btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">اضافة جديد</button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        @php
                                            $i = 1;
                                        @endphp
                                        <tr>
                                            <th>
                                                #
                                            </th>
                                            <th>المدينة</th>
                                            <th>تكلفة التوصيل</th>
                                            <th>    العمليات </th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($driver->DriverCityPrice()->with('City')->get() as $item)

                                        <tr>
                                            <td>
                                                {{$i++}}
                                            </td>
                                            <td>{{@$item['City']['name']}}</td>
                                            <td>{{$item->delivery_cost}} </td>
                                            <td class="btns">
                                                <button type="button" data-route="{{route('driver-prices.edit', $item->id)}}?driver_id={{$driver->id}}" class="load-ajax btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg">تعديل</button>

                                                <a href="{{route('driver-prices.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>

                                            </td>


                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="navpills-7" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-4 ">
                            <div class="card-box widget-user" style=" background-color: #ccc; ">
                                <div class="text-center">
                                    <h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('invoices.driver_paied', 1)->sum('invoices.driver_cost')}} ريال</h2>
                                    <h5> اجمالي المبالغ التي حصل عليها السائق</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="card-box widget-user" style=" background-color: #ccc; ">
                                <div class="text-center">
                                    <h2 class="text-info" data-plugin="counterup"> {{$driver->DriverFianance()->where('collected_from_driver', 0)->sum('total_amount')}} ريال</h2>
                                    <!--<h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('invoices.driver_paied', 1)->sum('invoices.total_price')}} ريال</h2>-->
                                    <h5> اجمالي المبالغ التي  لم يتم تحصيلها من السائق</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 ">
                            <div class="card-box widget-user" style=" background-color: #ccc; ">
                                <div class="text-center">
                                    <h2 class="text-info" data-plugin="counterup"> {{$driver->DriverFianance()->where('collected_from_driver', 1)->sum('total_amount')}} ريال</h2>
                                    <!--<h2 class="text-info" data-plugin="counterup"> {{$driver->Invoice()->where('orders.status', 'delivered')->where('invoices.driver_paied', 1)->sum('invoices.total_price')}} ريال</h2>-->
                                    <h5> اجمالي المبالغ التي تم تحصيلها من السائق</h5>
                                </div>
                            </div>
                        </div>
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>

                                            <th>#</th>
                                            <th> الفرع	</th>
                                            <th> السائق</th>
                                            <th>الحساب الكلي</th>
                                            <th>   عدد الشحنات</th>
                                            <th>  صافي الربح</th>
                                            <th>   الحاله</th>
                                            <th> التحصيل من السائق </th>
                                            <th>تاريخ الانشاء</th>
                                            <th> عرض الطلبات </th>


                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $i=1;
                                        @endphp
                                        @foreach($driver_finances as $item)

                                        <tr>
                                        <td>{{$i++}}</td>
                                              <td>{{@$item['Admin']['name']}} </td>
                                              <td>{{@$item['Driver']['first_name']}} {{@$item['Driver']['last_name']}} </td>
                                              <td>{{$item->total_amount}} </td>
                                              <td>{{\App\Models\Order::whereIn('id', explode(',', $item->orders))->count() }} </td>
                                              <!--<td>{{$item->driver_amount}} </td>-->
                                              <td>{{$item->OrdersNetProfit()}} </td>
                                              <td>
                                                {!!Form::model($item , ['url' => ['/dashboard/driver-finances/'.$item->id] , 'method' => 'PATCH', 'class'=>'form']) !!}
                                                    {!! Form::hidden('update_row', '1', []) !!}
                                                    {!! Form::select("status",\App\Models\DriverFianance::getLevels($item->status),null,['class'=>"form-control
                                                    select2", "autocomplete"=> 'off', "onchange" =>
                                                    "$(this).closest('form').submit()"]) !!}
                                                {!!Form::close() !!}
                                              </td>
                                              <td>
                                                {!!Form::model($item , ['url' => ['/dashboard/driver-finances/'.$item->id] , 'method' => 'PATCH', 'class'=>'form']) !!}
                                                    {!! Form::hidden('update_row', '1', []) !!}
                                                    {!! Form::select("collected_from_driver",\App\Models\DriverFianance::getDriverLevels($item->collected_from_driver),null,['class'=>"form-control
                                                    select2", "autocomplete"=> 'off', "onchange" =>
                                                    "$(this).closest('form').submit()"]) !!}
                                                {!!Form::close() !!}
                                              </td>
                                              <td>{{$item->created_at->toDateString()}} </td>
                                              <td>
                                                <button type="button" data-url="{{url('/dashboard/driver-finance-orders/'.$item->id)}}" class="btn btn-primary btn-lg invoice transfer-info" data-toggle="modal" data-target="#myModal">
                                                    عرض الفواتير
                                                  </button>
                                                  <a href="{{route('driver-finance-collect.pdf', $item->id)}}" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs" title="ExportPDF"> <i class="fa fa-download"></i>  </a>
                                                  <a href="{{route('driver-finance-collect.excel', $item->id)}}" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs" title="Export Excel"> <i class="fa fa-file-excel-o"></i>  </a>
                                              </td>
                                            {{-- <td class="btns">

                                                    <a href="/dashboard/driver-finances/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> تعديل </a>

                                                </td> --}}


                                        </tr>
                                        @endforeach




                                    </tbody>
                                </table>
                                {!! $driver_finances->links() !!}
                            </div>
                            <div class="col-md-12">
                                <div class="panel-heading panel-heading-custom">
                                    <ul class="nav nav-tabs nav-justified">
                                        <li role="presentation" class="active">
                                            <a href="#home2" id="home2-tab" role="tab" data-toggle="tab" aria-controls="home2" aria-expanded="true">طلبات لم يتم تصفيتها</a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#profile2" role="tab" id="profile2-tab" data-toggle="tab" aria-controls="profile2">طلبات تم تصفيتها</a>
                                        </li>

                                    </ul>
                                </div>

                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active" id="home2" aria-labelledby="home2-tab">
                                        <div class="col-md-12">
                                            <h4>طلبات لم يتم تصفيتها</h4>
                                            {!! Form::open(['url' => route('drivers.cashed-orders', $driver->id), 'method'=>'post']) !!}
                                            @csrf
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    <tr>

                                                        <th>
                                                            #
                                                            <input type="checkbox" class="ids" id="checkAll">
                                                        </th>
                                                         {{-- <th>IDs </th>  --}}
                                                        <th>المستحق للسائق</th>
                                                        <th>  اسم المتجر </th>
                                                        <th>   رقم تليفون المتجر </th>
                                                        <th>  اسم المستلم </th>
                                                        <th> رقم الهاتف</th>
                                                        <th> المدينه	   </th>
                                                        <th>   العنوان بالتفصيل	   </th>
                                                        <th> عدد القطع بالطرد </th>
                                                        <th>  السعر</th>
                                                        <th>  تكلفة الشحن</th>
                                                        <th>  طريقه الدفع </th>
                                                        {{--  <th>   ملحوظات </th>  --}}
                                                        <th>    الحالة </th>
                                                        <th>    رقم المرجع </th>
                                                        <th>  رقم التسلسل </th>
                                                        <th>    تاريخ االتسليم</th>
                                                        <th>    العمليات </th>


                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($orders_drivers_not_get_paid as $item)

                                                    <tr>
                                                        <td>
                                                            {{$i++}}
                                                            <input type="checkbox" name="ids[]" value="{{$item->id}}" class="ids"/>
                                                        </td>
                                                        <td>{{@$item['Invoice']['driver_cost']}} </td>
                                                        <td>{{@$item['company']['name']}} </td>
                                                        <td>{{@$item['company']['phone']}} </td>
                                                        <td>{{$item->recipent_name}} </td>
                                                        <td>{{$item->phone}} </td>
                                                        <td> {{@$item['City']['name']}}</td>
                                                        <td>{{$item->adress_details}} </td>
                                                        <td>{{$item->packages_number}} </td>
                                                        <td>{{$item->price}}</td>
                                                        <td>{{$item->Invoice->madar_price ?? ''}}</td>
                                                        <td>{{@$item['PaymentMethod']['name']}}</td>
                                                        <td>
                                                            {{__('words.'.$item->status)}}
                                                        </td>
                                                        <td>{{$item->refrence_no}}</td>
                                                        <td>{{$item->serial}}</td>
                                                        <td>{{$item->delivery_date}}</td>


                                                        <td class="btns">
                                                                <a href="/dashboard/orders/{{$item->id}}" type="button"
                                                                    class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                                        class="fa fa-eye"></i>  </a>
                                                                <a href="/dashboard/order-bill/{{$item->id}}" type="button" class="btn btn-primary   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-print"></i>    </a>
                                                            </td>


                                                    </tr>
                                                    @endforeach




                                                </tbody>
                                            </table>
                                            {!! $orders_drivers_not_get_paid->links() !!}
                                            @if ($driver->Order()->where('status', 'delivered')->where('collected', 0)->count() > 0)
                                                <button type="submit" href="{{ route('drivers.collect-orders', $driver->id) }}" class="btn btn-success">تصفية الطلبات من السائق</button>
                                            @endif
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="profile2" aria-labelledby="profile2-tab">
                                        <div class="col-md-12">
                                            <h4>طلبات تم تصفيتها</h4>
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    <tr>

                                                        <th>
                                                            #
                                                        </th>
                                                        <th>المستحق للسائق</th>
                                                        {{--  <th>IDs </th>  --}}
                                                        <th>  اسم المتجر </th>
                                                        <th>   رقم تليفون المتجر </th>
                                                        <th>  اسم المستلم </th>
                                                        <th>    رقم الهاتف</th>
                                                        <th>   المدينه	   </th>
                                                        <th>   العنوان بالتفصيل	   </th>
                                                        <th>    عدد القطع بالطرد	   </th>
                                                        <th>  السعر</th>
                                                        <th>  طريقه الدفع </th>
                                                        {{--  <th>   ملحوظات </th>  --}}
                                                        <th>    الحالة </th>
                                                        <th>    رقم المرجع </th>
                                                        <th>  رقم التسلسل </th>
                                                        <th>    تاريخ التسليم</th>
                                                        <th>    العمليات </th>


                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach($orders_drivers_get_paid as $item)

                                                    <tr>
                                                        <td>
                                                            {{$i++}}
                                                        </td>
                                                        <td>{{@$item['Invoice']['driver_cost']}} </td>
                                                        <td>{{@$item['company']['name']}} </td>
                                                        <td>{{@$item['company']['phone']}} </td>
                                                        <td>{{$item->recipent_name}} </td>
                                                        <td>{{$item->phone}} </td>
                                                        <td> {{@$item['City']['name']}}</td>
                                                        <td>{{$item->adress_details}} </td>
                                                        <td>{{$item->packages_number}} </td>
                                                        <td>{{$item->price}}</td>
                                                        <td>{{@$item['PaymentMethod']['name']}}</td>
                                                        <td>
                                                            {{__('words.'.$item->status)}}
                                                        </td>
                                                        <td>{{$item->refrence_no}}</td>
                                                        <td>{{$item->serial}}</td>
                                                        <td>{{$item->delivery_date}}</td>


                                                        <td class="btns">
                                                                <a href="/dashboard/orders/{{$item->id}}" type="button"
                                                                    class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i
                                                                        class="fa fa-eye"></i>  </a>
                                                                <a href="/dashboard/order-bill/{{$item->id}}" type="button" class="btn btn-primary   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-print"></i> </a>
                                                            </td>


                                                    </tr>
                                                    @endforeach




                                                </tbody>
                                            </table>
                                            {!! $orders_col->links() !!}
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end col -->


</div>
<!-- end row -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="modal-content">
        <i class="fa fa-snipper"></i>
      </div>
    </div>
  </div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">عرض  الطلبات  </h4>
        </div>
        <div class="modal-body" id="ajax-content">
            {{--  here table by ajax  --}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">اغلاق</button>
          {{--  <button type="button" class="btn btn-primary"> </button>  --}}
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')

<script>
    $('body').on('click', '.load-ajax', function(){
        $.get($(this).attr('data-route'))
        .done(function(data){
            $('#modal-content').html(data);
            initialize();
        })
        .fail(function(){});
    });
</script>


<!--Morris Chart-->
<script src="/adminto/assets/plugins/morris/morris.min.js"></script>
<script src="/adminto/assets/plugins/raphael/raphael-min.js"></script>
<script type="text/javascript">

    $(document).on('click', '.client-info', function(){
        $.get( "{{url('/dashboard/user-info')}}" + "/" + $(this).attr('data-id'), function( data ) {
            $('#client-info-box').html(data);
        });
    });
    $(document).on('click', '.delete-btn', function(e){
        e.preventDefault();
        var link = $(this);
        swal({
            title: "هل أنت متأكد؟",
            text: "أنك تريد حذف هذه المنطقه ؟",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then(function(isConfirm){
            if(isConfirm){
                var cr = $('meta[name="csrf-token"]').attr('content');
                var o = link;
                // alert(cr);
                $(o).append('<i class="fa fa-spin fa-spinner"></i>');
                $.post($(o).attr('href'),{
                  _token:cr,
                  _method:'DELETE'
                },function(data){
                  $(o).find('i').remove();
                  $(o).append('<i class="fa fa-check"></i>');
                  setTimeout(function(){
                    $(o).parent().parent().remove();
                    if(typeof cb  == 'function'){
                      cb();
                    }
                  },1000);
                });
            }
            else{
                swal("تم الالغاء", "الحذف  الغي بنجاح");
            }
        });
    });
//////////////////////////////////////// edit number of orders
    $("#merge_button").click(function(event){
        event.preventDefault();
        confirm('هل تريد تحديث المحدد؟');
        var status = $('#update-all').find('select[name=status]').val();
        var arr = [];
        var searchIDs = $(".ids:checkbox:checked").each(function(i){
            arr.push($(this).val() );
          return $(this).val();
        });
        console.log(arr);
        //post _token, arr
        // var jqxhr = $.post( "example.php", function() {
            // alert( "success" );
            // })
        $.post( "{{route('orders-ajax')}}", { _token: "{{csrf_token()}}", ids: arr, status: status } )
            .done(function() {
                window.location.href = "{{route('orders.index')}}";
              alert( "تم التحديث بنجاح." );
            })
            .fail(function() {
              alert( "error" );
            })
            .always(function() {
            //   alert( "finished" );
            });
      });
         ///////////////////////////////////////////////////////////////

         $(".form").submit(function(e){
             // stop submit .. stop reloading page
             e.preventDefault();
            alert("هل تريد التحديث ؟");
            var data = $(this).serializeArray();
            console.log(data);
            $.post( $(this).attr('action'), data )
            .done(function() {
              alert( "تم التحديث بنجاح." );
              $(e.currentTarget).children('td, th').css('background-color','#000');
            })
            .fail(function() {
              alert( "error" );
            })
            .always(function() {
            });


          });
          //////////////////////////////////////////////////

          $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });


        $(document).on('click', '.transfer-info', function(){
                var url = $(this).attr('data-url');
                $.get(url )
                .done(function(res){
                    console.log(res);
                    $('#ajax-content').html(res);

                })
                .fail(function(error){});
            });

</script>

<script>

    /**
    * Theme: Adminto Admin Template
    * Author: Coderthemes
    * Dashboard
    */

    !function($) {
        "use strict";

        var Dashboard1 = function() {
            this.$realData = []
        };

        //creates Bar chart
        Dashboard1.prototype.createBarChart  = function(element, data, xkey, ykeys, labels, lineColors) {
            Morris.Bar({
                element: element,
                data: data,
                xkey: xkey,
                ykeys: ykeys,
                labels: labels,
                hideHover: 'auto',
                resize: true, //defaulted to true
                gridLineColor: '#eeeeee',
                barSizeRatio: 0.2,
                barColors: lineColors
            });
        },

        //creates line chart
        Dashboard1.prototype.createLineChart = function(element, data, xkey, ykeys, labels, opacity, Pfillcolor, Pstockcolor, lineColors) {
            Morris.Line({
              element: element,
              data: data,
              xkey: xkey,
              ykeys: ykeys,
              labels: labels,
              fillOpacity: opacity,
              pointFillColors: Pfillcolor,
              pointStrokeColors: Pstockcolor,
              behaveLikeLine: true,
              gridLineColor: '#eef0f2',
              hideHover: 'auto',
              resize: true, //defaulted to true
              pointSize: 0,
              lineColors: lineColors
            });
        },

        //creates Donut chart
        Dashboard1.prototype.createDonutChart = function(element, data, colors) {
            Morris.Donut({
                element: element,
                data: data,
                resize: true, //defaulted to true
                colors: colors
            });
        },


        Dashboard1.prototype.init = function() {

            //creating bar chart
            var $barData  = JSON.parse('{!! $days_chart !!}');
            this.createBarChart('morris-bar-example', $barData, 'y', ['a'], ['العدد'], ['#188ae2']);
            //creating bar chart
            var $barData2  = JSON.parse('{!! $months_chart !!}');
            this.createBarChart('morris-bar-example2', $barData2, 'y', ['a'], ['عدد الطلبات'], ['#188ae2']);

        },
        //init
        $.Dashboard1 = new Dashboard1, $.Dashboard1.Constructor = Dashboard1
    }(window.jQuery),

    //initializing
    function($) {
        "use strict";
        $.Dashboard1.init();
    }(window.jQuery);
    </script>
@endsection
