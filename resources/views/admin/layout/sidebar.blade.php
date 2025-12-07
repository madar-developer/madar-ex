<!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="scroll-control">

                    <!-- User -->
                    <div class="user-box">
                        <div class="user-img">
                            {{--  <img src="{{ asset('/adminto/assets/images/users/avatar-1.jpg')}}" alt="user-img" title="Mat Helme" class="img-circle img-thumbnail img-responsive">  --}}
                            <img src="{{getImage(auth()->User()->image)}}" alt="user-img" title="Mat Helme" class="img-circle img-thumbnail img-responsive" style="height: 88px;
                            width: 97px;">

                            <div class="user-status offline"><i class="zmdi zmdi-dot-circle"></i></div>
                        </div>
                        <h5><a href="{{url('dashboard/settings/admins-edit/'.auth()->id())}}">  {{ auth()->user()->name}} </a> </h5>
                        <ul class="list-inline">

                            {{--  <li>wait......
                                <a href="#" class="text-custom">
                                    <i class="zmdi zmdi-power"></i>
                                    <span> تسجيل خروج </span>
                                </a>
                            </li>  --}}
                            <li>  <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();" class="text-custom">
                                    <i class="zmdi zmdi-power"></i>
                               <span ></span> تسجيل الخروج
                           </a>
                           <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                               {{ csrf_field() }}
                               <input type="hidden" name="dashboard" value="1">
                           </form>
                       </li>
                        </ul>
                    </div>
                    <!-- End User -->

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <ul>

                            <li class="has_sub  {!! (CheckPermission(['show']))? '' : 'hidden'  !!} ">
                                <a href="{{ url('/dashboard/') }}" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> الرئيسيه  </span> </a>
                            </li>
                            <li class="has_sub  {!! (CheckPermission(['company_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> المتاجر والشركات  </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/companies')}}"> عرض  المتاجر والشركات </a></li>
                                    <li class=" {!! (CheckPermission(['company_add']))? '' : 'hidden'  !!}"><a href="{{ url('/dashboard/companies/create') }}">  اضافة متجر او شركة </a></li>
                                </ul>
                            </li>
                            <li class="has_sub  {!! (CheckPermission(['order_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> الطلبات  </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/orders')}}"> عرض  الطلبات </a></li>
                                    <li class=" {!! (CheckPermission(['order_add']))? '' : 'hidden'  !!}"><a href="{{ url('/dashboard/orders/create') }}">  اضافة طلب</a></li>
                                </ul>
                            </li>
                            <li class="has_sub {!! (CheckPermission(['finance_show']))? '' : 'hidden'  !!}">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>  الاداره الماليه </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li class="has_sub   ">
                                        <a href="{{ url('/dashboard/invoices/') }}" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> الفواتير  </span> </a>

                                    </li>
                                    <li class="has_sub  {!! (CheckPermission(['transfer_show']))? '' : 'hidden'  !!} ">
                                        <a href="{{ url('/dashboard/transfers/') }}" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> الحولات  </span> </a>

                                    </li>
                                </ul>
                            </li>
                            <li class="has_sub {!! (CheckPermission(['finance_show']))? '' : 'hidden'  !!}">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>   الحسابات </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li class="has_sub   ">
                                        <a href="{{ url('/dashboard/driver-finances/') }}" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> حسابات السائقين  </span> </a>

                                    </li>
                                    <li class="has_sub   ">
                                        <a href="{{ url('/dashboard/branch-finances-r-t-m') }}" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>  مستحق من الفروع  </span> </a>

                                    </li>
                                    <li class="has_sub  {!! (CheckPermission(['transfer_show']))? '' : 'hidden'  !!} ">
                                        <a href="{{ url('/dashboard/branch-finances/') }}" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>  حسابات الفروع  </span> </a>

                                    </li>
                                </ul>
                            </li>
                            {{-- <li class="has_sub {!! (CheckPermission(['report_show']))? '' : 'hidden'  !!}">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> التقارير  </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/reports/orders')}}">   الطلبات  </a></li>
                                    <li><a href="{{url('/dashboard/reports/companies')}}">   الشركات  </a></li>
                                    <li><a href="{{url('/dashboard/reports/drivers')}}">   السائقين  </a></li>

                                </ul>
                            </li> --}}
                            <li class="has_sub  {!! (CheckPermission(['contact_us_show']))? '' : 'hidden'  !!} ">
                                <a href="{{ url('dashboard/contact_us/') }}" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> استفسارات الزوار  </a>

                            </li>

                            <li class="has_sub  {!! (CheckPermission(['driver_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> ادراة السائقين </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/drivers')}}">   عرض السائقين  </a></li>
                                    <li><a href="{{url('/dashboard/drivers-charts')}}">   تقرير السائقين  </a></li>
                                    <li class=" {!! (CheckPermission(['driver_add']))? '' : 'hidden'  !!}"><a href="{{ url('/dashboard/drivers/create') }}">  اضافة سائق</a></li>
                                </ul>
                            </li>
                            <li class="has_sub  {!! (CheckPermission(['car_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>  السيارات </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/cars')}}">   عرض السيارات  </a></li>
                                    <li class=" {!! (CheckPermission(['car_add']))? '' : 'hidden'  !!}"><a href="{{ url('/dashboard/cars/create') }}">  اضافة سياره</a></li>
                                </ul>
                            </li>

                            <li class="has_sub  {!! (CheckPermission(['car_maintenance_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>  صيانه السيارات </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/carmaintaince')}}">    عرض  </a></li>
                                    <li class=" {!! (CheckPermission(['car_maintenance_add']))? '' : 'hidden'  !!}"><a href="{{ url('/dashboard/carmaintaince/create') }}">  اضافة </a></li>
                                </ul>
                            </li>
                            <li class="has_sub  {!! (CheckPermission(['admin_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> الادارة  </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/admins')}}">   عرض  </a></li>
                                    <li class=" {!! (CheckPermission(['admin_add']))? '' : 'hidden'  !!}"><a href="{{ url('/dashboard/admins') }}">    اضافه</a></li>

                                </ul>
                            </li>

                            <li class="has_sub {!! (CheckPermission(['setting_show']))? '' : 'hidden'  !!}">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> الاعدادت  </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/city-groups')}}">   المجمعات </a></li>
                                    <li><a href="{{url('/dashboard/cities')}}">   المدن </a></li>
                                    <li><a href="{{ url('/dashboard/payment-methods') }}">   وسائل الدفع</a></li>
                                    <li><a href="{{ url('/dashboard/avaliable-methods') }}">    طرق التحصيل</a></li>
                                    <li><a href="{{ url('/dashboard/order-status') }}">    حالات الطلب </a></li>
                                    <li><a href="{{ url('/dashboard/settings/permissions') }}">     الصلاحيات </a></li>
                                    <li><a href="{{ url('/dashboard/settings/site') }}">     اعدادات عامة </a></li>

                                </ul>
                            </li>

                            <li class="has_sub  {!! (CheckPermission(['notifications_show']))? '' : 'hidden'  !!} ">
                                <a href="{{ url('dashboard/notifications') }}" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>  ارسال تنبيهات  </a>

                            </li>

                            <li class="has_sub  {!! (CheckPermission(['car_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>  السلايدر </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/sliders')}}">   عرض   </a></li>
                                    <li class=" {!! (CheckPermission(['car_add']))? '' : 'hidden'  !!}"><a href="{{ url('/dashboard/sliders/create') }}">  اضافة </a></li>
                                </ul>
                            </li>
                            <li class="has_sub  {!! (CheckPermission(['car_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>  عملائنا </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/partners')}}">   عرض   </a></li>
                                    <li class=" {!! (CheckPermission(['car_add']))? '' : 'hidden'  !!}"><a href="{{ url('/dashboard/partners/create') }}">  اضافة </a></li>
                                </ul>
                            </li>
                            <li class="has_sub  {!! (CheckPermission(['car_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span>  أوقات العمل </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/worktimes')}}">   عرض   </a></li>
                                    <li class=" {!! (CheckPermission(['car_add']))? '' : 'hidden'  !!}"><a href="{{ url('/dashboard/worktimes/create') }}">  اضافة </a></li>
                                </ul>
                            </li>
                            {{-- <li class="has_sub  {!! (CheckPermission(['orders-company_show']))? '' : 'hidden'  !!} ">
                                <a href="javascript:void(0);" class="waves-effect"><i class="zmdi zmdi-view-list"></i> <span> الاحصائيات  </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/dashboard/statistics/orders-company')}}">   الطلبات - الشركات </a></li>

                                </ul>
                            </li> --}}


                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Sidebar -->
                    {{-- <div class="clearfix"></div> --}}

                </div>

            </div>
            <!-- Left Sidebar End -->
