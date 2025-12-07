@extends('admin.layout.app')
@section('style')
@endsection
@section('header')
<!-- Page title -->

                            
                          
                                <li> 
                                    <div class="add-btn">
                                        <a href="{{ url('/dashboard/company-adress/create') }}" type="button" class="btn btn-custom btn-rounded waves-effect waves-light w-md m-b-5"> <i class="fa fa-check"></i> اضافة </a>
                                    </div> 
                                </li>
                           
@endsection
@section('content')

<div class="row">
                    
    <div class="col-sm-12">
        <div class="card-box">
            <form action="" method="get">
                <div class="row">
                    <div class="col-md-12 part-top">
                        <div class="row">
                         
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            

                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            {!! Form::select("id",StoreOrCompany(),(array_key_exists('id', $search))?
                                            $search['id'] : null,['class'=>"form-control select2 "])!!}
                    
                                        </div>
                                    </div>
                    
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <input type="text" name="phone" value="{{(array_key_exists('phone', $search))? $search['phone'] : ''}}" class="form-control" placeholder="رقم تليفون المتجر">
                                    
                                        </div>
                                    </div>

                                </div>
                            </div>
                            

                            <div class="col-md-2">
                                <div class="form-horizontal m-b-15">
                                    <button type="button" onclick="$(this).closest('form').find('#excel').remove(); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-search"></i> بحث</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <button type="button" target="_blank" onclick="$(this).closest('form').prepend(`<input name='excel' id='excel' type='hidden' value='1' />`); $(this).closest('form').submit();" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10">تصدير لExcel</button>
                             
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-horizontal">
                                    <a href="{{url('/dashboard/orders')}}" class="btn btn-block btn-sm btn-success waves-effect waves-light b-t-10 b-b-10"><i class="fa fa-trash"></i> مسح خيارات البحث</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div> 


<div class="row">
                    
                    <div class="col-sm-12">
                            <div class="card-box text-left">
                                    <div class="row">
                                     
                                        <div class="col-lg-12">
                                            <div class="box-tebal">
                                                <div class="title">
                                                    <h4><i class="fa fa-list" aria-hidden="true"></i>قائمه المتاجر     </h4>
                                                </div>
                                                <div role="tabpanel" class="tab-pane " style="overflow: hidden;">
                                                    <table class="table table-striped table-bordered">
                                                        <thead>
                                                            <tr>
                                                                {{--  'address', 'nick_name' , 'street_name' , 'building' , 'floor' , 'flat'         --}}
                                                                <th> # </th>
                                                                <th>  العنوان </th>
                                                                <th>الاسم المستعار   </th>
                                                                <th>اسم الشارع    </th>
                                                                <th>رقم المبنى     </th>
                                                                <th>رقم الطابق     </th>
                                                                <th>رقم الشقه     </th>
                                                                <th>الشركه      </th>
                                                                <th>المدينه      </th>
                                                                <th>العمليات</th>
                                                              
        
                                                            </tr>
                                                        </thead>
        
                                                        <tbody>
                                                            @foreach($company_adress as $item)
                                                            
                                                            <tr>
                                                                <td>{{$item->id}} </td>
                                                                <td> {{$item->address}} </td>
                                                                <td>{{$item->nick_name}} </td>
                                                                <td>{{$item->street_name}}</td>
                                                                <td>{{$item->building}}</td>
                                                                <td>{{$item->floor}}</td>
                                                                <td> {{$item->flat}} </td>
                                                                <td>{{$item->Company()->name ?? ''}}</td>
                                                                <td>{{$item->City()->name ?? ''}}</td>
                                                                <td class="btns">
                                                                
                                                                    <a href="/dashboard/company-adress/{{$item->id}}" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-eye"></i> عرض </a>
                                                                    <a href="/dashboard/company-adress/{{$item->id}}/edit" type="button" class="btn btn-info   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pencil"></i> تعديل </a>
                                                                    <a href="{{route('company-adress.destroy',$item)}}" id="delete-btn" type="button" class="btn btn-danger   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-times"></i> حذف </a>
                                                                    <a href="{{route('company.pdf',$item->id)}}" type="button" class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pdf"></i> ExportPDF </a>
                                                                    <a href="{{route('company-finance.pdf',$item->id)}}" type="button" class="btn btn-success   waves-effect waves-light m-b-5 btn-xs"> <i class="fa fa-pdf"></i> تقرير مالي </a>
                                                               
                                                                </td>
                                                               
        
                                                                {{--  <td>
                                                                    <a href="/dashboard/companies/{{$item->id}}/edit">
                                                                        <i class="fa fa-pencil  m-r-10" style="color: #188ae2;"> 
                                                                        </i> تعديل</a>
                                                                </td>  --}}
                                                        
                                                                {{--  <td>
                                                                     <a href="{{route('companies.destroy',$item)}}" id="delete-btn"  >
                                                                         <i class="fa fa-trash-o m-r-10" style="color: #5b69bc;"></i> حذف</a>
                                                                </td>  --}}
                                                                {{-- <td>
                                                                    <a href="{{route('users.show',$item->id)}}" class="btn waves-effect btn-default pull-right client-info" > عرض </a>
                                                                </td> --}}
                                                            </tr>
                                                            @endforeach
                                                        
        
                                                            
                                                            
                                                        </tbody>
                                                    </table>
                                                    {!! $companies->appends($search)->links() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        
                    </div><!-- end col -->
                </div>
                <!-- end row -->
                <div class="col-sm-12">
                      
                    </div>

                <div id="modal-delete" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width:55%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="custom-width-modalLabel">هل تريد الحذف </h4>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">إلغاء الامر</button>
                                <button type="button" class="btn btn-primary buunton-notofication waves-effect waves-light" data-type="success"  data-message="تم الحذف">حذف</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>

                <div id="info-modal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true">
                    <div class="modal-dialog" style="width:55%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title" id="custom-width-modalLabel">بيانات العميل </h4>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12" id="client-info-box">
                                        {{--  --}}
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div>
                                                    
                                            
@endsection
@section('script')


        <script type="text/javascript">
            // $('#datatable-0, #datatable-1, #datatable-2, #datatable-3, #datatable-4').DataTable( {
            //     "bLengthChange" : false, //thought this line could hide the LengthMenu
            //     "bInfo":false,  
            // } );
            
            // $('.buunton-notofication').on('click',function(){
            //     var type = $(this).data('type');     
            //     var message = $(this).data('message');     
            //     switch(type){
            //         case 'error' : toastr.error(message);  break;
            //         case 'success' : toastr.success(message);  break;
            //         case 'info' : toastr.info(message);  break;
            //         case 'warning' : toastr.warning(message);  break; 
            //     }
            //     return false;
            // });
            // TableManageButtons.init();

            $(document).on('click', '.client-info', function(){
                $.get( "{{url('/dashboard/user-info')}}" + "/" + $(this).attr('data-id'), function( data ) {
                    $('#client-info-box').html(data);
                });
            });
        </script>
@endsection