@extends('admin.layout.app')
@section('style')
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12 m-b-15">
        <div class="btn-group pull-right m-t-15">
            <a href="/dashboard/users/{{$user->id}}/edit" class="btn btn-info waves-effect w-md waves-light m-b-5">
                <i class="fa fa-pencil m-r-5"></i>
                <span>تعديل</span>
            </a>

            <a href="{{route('users.destroy',$user)}}" id="delete-btn"
                class="btn btn-info btn-danger waves-effect w-md waves-light m-b-5 m-l-5">
                <i class="fa fa-remove"></i>
                <span>حذف</span>
            </a>
        </div>
        <h4 class="page-title">
            <i class="fa fa-home" ></i>
             {{ $user->name }} 
            
             @if ($user->vip==1)
             <i class="fa fa-star" aria-hidden="true"  style="color: gold"></i>  
             @else
             {{-- <i class="fa fa-star" aria-hidden="true"></i> --}}
             @endif
              
        </h4>
    </div>
</div>
<!-- end row -->

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
                    <li class="">
                        <a href="#navpills-3" data-toggle="tab" aria-expanded="false">المعدات المفضله</a>
                    </li>
                    @if ($user->vip==1)
    
                    <li class="">
                        <a href="#navpills-4" data-toggle="tab" aria-expanded="false"> بيانات حساب بيزنس</a>
                    </li>
                    <li class="">
                        <a href="#navpills-5" data-toggle="tab" aria-expanded="false"> طلبات الشركات</a>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div id="navpills-1" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped text-center m-0">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">
                                                <img src=" {{ $user->image }}" style="width: 100px; height: 100px;border-radius: 50%;border: 2px solid #2c0d0d;" />
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-center" colspan="4">البيانات الأساسية</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>الاسم</td>
                                            <td>{{$user->name}}</td>
                                        </tr>
                                        <tr>
                                            <td>البريد الالكتروني</td>
                                            <td>{{$user->email}}</td>
                                        </tr>
                                        <tr>
                                            <td>رقم الهاتف</td>
                                            <td>{{$user->phone}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                {{--  <table class="table table-bordered table-striped text-center m-0">
                                                    <thead >
                                                        <tr>
                                                            <th class="text-center" colspan="4">بيانات أخرى</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>الطابق</td>
                                                            <td>1</td>
                                                        </tr>
                                                        <tr>
                                                            <td>عدد المداخل</td>
                                                            <td>1</td>
                                                        </tr>
                                                        <tr>
                                                            <td>وصف الوحدة</td>
                                                            <td>سكني عائلات</td>
                                                        </tr>
                                                        <tr>
                                                            <td>الغرف/الفتحات عدد</td>
                                                            <td>4</td>
                                                        </tr>
                                                        <tr>
                                                            <td>عدد دورات المياه</td>
                                                            <td>2</td>
                                                        </tr>
                                                        <tr>
                                                            <td>نموذج الوحدة</td>
                                                            <td>امامى</td>
                                                        </tr>
                                                    </tbody>
                                                </table>  --}}
                            </div>
                        </div>
                    </div>
                    <div id="navpills-2" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="datatable-buttons table tab-pane-custom table-striped table-bordered"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>رقم الطلب </th>
                                            <th>اسم العميل </th>
                                            <th> رقم الهاتف </th>
                                            <th>تاريخ الطلب</th>
                                            <th>الحالة </th>
                                            <th>عرض </th>
                                            <th>تعديل</th>
                                            <th>حذف</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($user->Order()->get() as $item)
                                        <tr>
                                            <td>{{$item->id}}</td>
                                            <td> {{ $item->User->name ?? '' }} </td>
                                            <td> {{ $item->User->phone ?? '' }} </td>




                                            <td> {{ $item->created_at ?? '' }} </td>
                                            <td> {{__('dashboard.'.$item->status)}} </td>

                                            <td><a href="{{route('orders.show',$item->id)}}"><i
                                                        class="fa fa-eye  m-r-10" style="color: #188ae2;"></i> عرض</a>
                                            </td>
                                            <td><a href="{{route('orders.edit',$item->id)}}"><i
                                                        class="fa fa-pencil  m-r-10" style="color: #188ae2;"></i>
                                                    تعديل</a></td>
                                            <td> <a href="{{route('orders.destroy',$item)}}" id="delete-btn"><i
                                                        class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i>
                                                    حذف</a></td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="navpills-5" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                  <table class="datatable-buttons table tab-pane-custom table-striped table-bordered"  style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>رقم الطلب  </th>
                                                <th>اسم  الشركه </th>
                                                <th> رقم جوال الشركه </th>
                                                <th>الاسم المسجل</th>
                                                <th>اسم المسئول   </th>
                                                <th>رقم جوال المسئول   </th>
                                                <th> ايميل المسئول </th>
                                                <th> من </th>
                                                <th> الى</th>
                                                <th>الحالة </th>
                                                <th>عرض </th>
                                                <th>تعديل</th>
                                                <th>حذف</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($user->CompanyOrder()->get() as $item)
                                                
                                            
                                            <tr>
                                                <td>{{$item->id}} </td>
                                                <td>{{$item->company_name}} </td>
                                                <td> {{$item->company_phone}} </td>
                                                <td>{{$item->responsible_name}} </td>
                                                <td>{{$item->responsible_phone}} </td>
                                                <td> {{$item->responsible_phone}} </td>
                                                <td> {{$item->responsible_mail}} </td>
                                                <td> {{$item->date_from}} </td>
                                                <td>  {{$item->date_to}} </td>
                                                <td> {{__('words.'.$item->status)}} </td>
                                                
                                                <td><a href="{{route('company-orders.show',$item->id)}}" ><i class="fa fa-eye  m-r-10" style="color: #188ae2;"></i> عرض</a></td>
                                                <td><a href="{{route('company-orders.edit',$item->id)}}"><i class="fa fa-pencil  m-r-10" style="color: #188ae2;"></i> تعديل</a></td>
                                                <td> <a href="{{route('company-orders.destroy',$item)}}" id="delete-btn" ><i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a></td>
                                            </tr>
                                            @endforeach
                                            

                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>


                    <div id="navpills-4" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped text-center m-0">
                                    <thead>
                                        <th colspan="2" class="text-center">
                                            <img src="{{$user->BusinessAccount->image ?? ''}}" style="width: 100px; height: 100px;border-radius: 50%;border: 2px solid #e7cd10;" />
                                            <br>
                                            <i class="fa fa-star" aria-hidden="true"  style="color: gold"></i> 
                                            <i class="fa fa-star" aria-hidden="true"  style="color: gold"></i> 
                                            <i class="fa fa-star" aria-hidden="true"  style="color: gold"></i> 
                                        </th>
                                        <tr>
                                            <th class="text-center" colspan="4"> بيانات حساب بيزنس</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>البريد الالكتروني</td>
                                            <td>{{$user->BusinessAccount->email ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <td>اسم الشركة </td>
                                            <td>{{$user->BusinessAccount->company_name ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <td> السجل التجاري</td>
                                            <td>{{$user->BusinessAccount->registration_number ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <td> رقم الهاتف </td>
                                            <td>{{$user->BusinessAccount->phone ?? ''}}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td> الصوره </td>
                                            <td> 
                                                <img src="{{$user->BusinessAccount->image ?? ''}}" width="300px"
                                                    height="300px"> 
                                                </td>


                                        </tr> --}}
                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                            </div>
                        </div>
                    </div>
                    <div id="navpills-3" class="tab-pane fade">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="datatable-buttons table tab-pane-custom table-striped table-bordered"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>اسم المعده </th>
                                            <th>نوع المعده</th>
                                            <th>المهمه </th>
                                            <th>صوره المعده </th>
                                            <th> عرض </th>
                                        </tr>


                                    </thead>
                                    {{--  ///////////////////////////////////////////////////////////////////  --}}
                                    <tbody>
                                        @foreach ($user->Favourite()->get() as $item)
                                        {{--  $item = $user->Favourite()->first()  --}}

                                        <td> {{$item->Product->title}} </td>
                                        <td> {{$item->Product->Category->name_ar}} </td>
                                        <td> {{$item->Product->Mission->name_ar}} </td>
                                        <td> <img src=" {{$item->Product->image}}" width="70px" height="70px"> </td>
                                        <td> <a href="{{route('products.show',$item->product_id)}}" target="_blank">عرض
                                            </a> </td>

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
    </div><!-- end col -->

</div>
<!-- end row -->

@endsection
@section('script')
@endsection
