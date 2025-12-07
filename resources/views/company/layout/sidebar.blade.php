<!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="scroll-control">

                    <!-- User -->
                    <div class="user-box">
                        <div class="user-img">
                            <img src="{{getImage(auth()->User()->image)}}" alt="user-img" title="Mat Helme" class="img-circle img-thumbnail img-responsive" style="    height: 88px;
                            width: 97px;">
                            <div class="user-status offline"><i class="fa fa-dot-circle"></i></div>
                        </div>
                        <h5><a href="{{url('company/settings/admins-edit')}}">  {{ auth()->user()->name }} </a> </h5>
                        <ul class="list-inline">

                            {{--  <li>
                                <a href="#" class="text-custom">
                                    <i class="fa fa-power"></i>
                                    <span> تسجيل خروج </span>
                                </a>
                            </li>  --}}
                            <li>  <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();" class="text-custom">
                                    <i class="fa fa-power"></i>
                               <span ></span> تسجيل الخروج
                           </a>
                           <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                               {{ csrf_field() }}
                               <input type="hidden" name="company" value="1">
                           </form>
                       </li>
                        </ul>
                    </div>
                    <!-- End User -->

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <ul>
                            <li class="has_sub">
                                <a href="{{ url('/company') }}" class="waves-effect"><i class="fa fa-home"></i> <span> الرئيسيه  </span> </a>
                            </li>
                            <li class="has_sub">
                                <a href="{{ route('company-profile') }}" class="waves-effect"><i class="fa fa-user"></i> <span> الملف الشخصي  </span></a>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-archive"></i> <span> الطلبات  </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/company/company-orders')}}"> عرض  الطلبات </a></li>
                                    <li><a href="{{ url('/company/company-orders/create') }}">  اضافة طلب</a></li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-truck"></i> <span> طلب مندوبين  </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/company/request-drivers')}}"> عرض  الكل  </a></li>
                                    <li><a href="{{ url('/company/request-drivers/create') }}">  اضافة جديد</a></li>
                                </ul>
                            </li>
                            <li class="has_sub">
                                <a href="{{ url('/company/company-invoices/') }}" class="waves-effect"><i class="fa fa-files-o"></i> <span> الفواتير  </span> </a>

                            </li>
                            <li class="has_sub">
                                <a href="{{ url('/company/company-transfers/') }}" class="waves-effect"><i class="fa fa-usd"></i> <span> الحولات  </span> </a>

                            </li>
                            <li class="has_sub">
                                <a href="{{route('company.company-finance.pdf')}}" class="waves-effect"><i class="fa fa-download"></i> <span> تحميل تقرير مالي   </span> </a>

                            </li>
                            <li class="has_sub">
                                <a href="javascript:void(0);" class="waves-effect"><i class="fa fa-code"></i> <span> المطورون </span> <span class="menu-arrow"></span></a>
                                <ul class="list-unstyled">
                                    <li><a href="{{url('/docs')}}" target="_blank"> Integration Api   </a></li>
                                </ul>
                            </li>

                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <!-- Sidebar -->
                    {{-- <div class="clearfix"></div> --}}

                </div>

            </div>
            <!-- Left Sidebar End -->
