<!--Navigation Menu-->
@php
$user = auth()->user();
@endphp
<ul class="navigation-menu">
    <li>
        <a href="{{url('/dashboard')}}"><i class="zmdi zmdi-home"></i> <span> الرئيسية </span> </a>
    </li>
    
    <li class="has-submenu {!! (CheckPermission('user_show'))? '' : 'hidden'  !!} ">
        <a href="#"><i class="fa fa-users" aria-hidden="true"></i><span> مستخدمي الموقع</span> </a>
        <ul class="submenu">
            <li><a href="{{ url('/dashboard/users') }}">عرض الكل</a></li>
            <li><a href="{{ url('/dashboard/users/create') }}">اضافة جديد</a></li>
        </ul>
    </li>
    <li class="has-submenu {!! (CheckPermission('product_show'))? '' : 'hidden'  !!} ">
        <a href="#"><i class="fa fa-list" aria-hidden="true"></i><span> المتاجر والشركات</span> </a>
        <ul class="submenu">
            <li><a href="{{url('/dashboard/shops')}}">عرض الكل</a></li>
            <li><a href="{{ url('/dashboard/shops/create') }}">اضافة جديد</a></li>
        </ul>
        {{-- <ul class="submenu">
         
            <li><a href="{{ url('/dashboard/products?type=rent') }}"> للايجار</a></li>
            <li><a href="{{ url('/dashboard/products?type=sale') }}"> للبيع</a></li>
        </ul> --}}
    </li>
    <li class="has-submenu {!! (CheckPermission('product_show'))? '' : 'hidden'  !!} ">
        <a href="#"><i class="fa fa-list" aria-hidden="true"></i><span>  الطلبات</span> </a>
        <ul class="submenu">
            <li><a href="{{url('/dashboard/orders')}}">عرض الكل</a></li>
            <li><a href="{{ url('/dashboard/orders/create') }}">اضافة جديد</a></li>
        </ul>
    </li>



    <li class="has-submenu  {!! (CheckPermission('transaction_show'))? '' : 'hidden'  !!} ">
            <a href="{{ url('/dashboard/transactions') }}"><i class="fa fa-dollar color1"></i><span> الفواتير </span> </a>
            <ul class="submenu">
                <li><a href="{{ url('/dashboard/invoices/') }}"> عرض الكل</a></li>
                <li><a href="{{url('/dashboard/invoices/create')}}">اضافه جديد </a></li>
            </ul>
            
    </li>
    <li class="has-submenu  {!! (CheckPermission('transaction_show'))? '' : 'hidden'  !!} ">
            <a href="{{ url('/dashboard/transactions') }}"><i class="fa fa-dollar color1"></i><span> الحولات </span> </a>
            <ul class="submenu">
                <li><a href="{{ url('/dashboard/remittances/') }}"> عرض الكل</a></li>
                <li><a href="{{url('/dashboard/remittances/create')}}">اضافه جديد </a></li>
            </ul>
            
    </li>

    <li class="has-submenu">
        <a href="#"><i class="fa fa-pie-chart color1"></i> <span> اداره السيارات </span> </a>
        <ul class="submenu">
            <li><a href="{{url('/dashboard/cars')}}"> عرض الكل </a></li>
            <li><a href="{{ url('/dashboard/cars/create') }}"> اضافه جديد</a></li>
        </ul>
    </li>
    <li class="has-submenu">
        <a href="#"><i class="fa fa-pie-chart color1"></i> <span> اداره السائقين </span> </a>
        <ul class="submenu">
            <li><a href="{{url('/dashboard/drivers')}}"> عرض الكل </a></li>
            <li><a href="{{ url('/dashboard/drivers/create') }}"> اضافه جديد</a></li>
        </ul>
    </li>
    <li class="has-submenu">
        <a href="#"><i class="fa fa-pie-chart color1"></i> <span>  الخصومات </span> </a>
        <ul class="submenu">
            <li><a href="{{url('/dashboard/discounts')}}"> عرض الكل </a></li>
            <li><a href="{{ url('/dashboard/discounts/create') }}"> اضافه جديد</a></li>
        </ul>
    </li>
    <li class="has-submenu">
        <a href="#"><i class="fa fa-pie-chart color1"></i> <span>  خصومات المناسبات </span> </a>
        <ul class="submenu">
            <li><a href="{{url('/dashboard/event-discounts')}}"> عرض الكل </a></li>
            <li><a href="{{ url('/dashboard/event-discounts/create') }}"> اضافه جديد</a></li>
        </ul>
    </li>
    <li class="has-submenu">
        <a href="#"><i class="fa fa-pie-chart color1"></i> <span>   المدونه </span> </a>
        <ul class="submenu">
            <li><a href="{{url('/dashboard/blogs')}}"> عرض الكل </a></li>
            <li><a href="{{ url('/dashboard/blogs/create') }}"> اضافه جديد</a></li>
        </ul>
    </li>


    {{-- <li class=" {!! (CheckPermission('notification_show'))? '' : 'hidden'  !!} ">
        <a href="{{url('/dashboard/notifications')}}"><i class="fa fa-list" aria-hidden="true"></i> <span> ارسال تنبيهات </span> </a>
    </li> --}}
    {{-- <li class=" {!! (CheckPermission('slider_show'))? '' : 'hidden'  !!} ">
        <a href="{{url('/dashboard/sliders')}}"><i class="fa fa-list" aria-hidden="true"></i> <span>  سليدر </span> </a>
    </li> --}}

</ul>
<!-- End navigation menu -->