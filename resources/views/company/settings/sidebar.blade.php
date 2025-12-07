<li role="presentation" class="{{( Request()->segment(3) == 'site')? 'active' : ''}}">
    <a href="{{url('/dashboard/settings/site')}}" aria-controls="site"><span>إعدادات  الموقع</span>
        <i class="fa fa-angle-left pull-left"></i><i class="fa fa-angle-right pull-left"></i></a>
</li>

<li role="presentation" class="{{( Request()->segment(3) == 'admins')? 'active' : ''}} ">
    <a href="{{url('/dashboard/settings/admins')}}" aria-controls="users"><span>المستخدمون</span>
    <i class="fa fa-angle-left pull-left"></i></a>
</li>
<li role="presentation" class="{{( Request()->segment(3) == 'permissions')? 'active' : ''}} ">
    <a  class="with-border-bottom" aria-controls=""><span>الصلاحيات</span> <i class="fa fa-angle-left pull-left"></i></a>
    <li role="presentation" class="{{( Request()->segment(3) == 'permissions')? 'active' : ''}} nested"><a href="{{url('/dashboard/settings/permissions')}}" aria-controls="auth"><span>صلاحيات النظام</span>
        <i class="fa fa-angle-left pull-left"></i><i class="fa fa-angle-right pull-left"></i> </a>
    </li>
</li>
<li role="presentation" class="{{( Request()->segment(3) == 'regions')? 'active' : ''}} ">
    <a href="{{url('/dashboard/settings/regions')}}" class="with-border-bottom" aria-controls=""><span>المدن و المناطق</span> <i class="fa fa-angle-left pull-left"></i></a>
</li>
<li role="presentation" class="{{( Request()->segment(3) == 'missions')? 'active' : ''}} ">
    <a href="{{url('/dashboard/settings/missions')}}" class="with-border-bottom" aria-controls=""><span>المهمه</span> <i class="fa fa-angle-left pull-left"></i></a>
</li>
<li role="presentation" class="{{( Request()->segment(3) == 'categories')? 'active' : ''}} ">
    <a href="{{url('/dashboard/settings/categories')}}" class="with-border-bottom" aria-controls=""><span>الاقسام</span> <i class="fa fa-angle-left pull-left"></i></a>
</li>
<li role="presentation" class="{{( Request()->segment(3) == 'models')? 'active' : ''}} ">
    <a href="{{url('/dashboard/settings/models')}}" class="with-border-bottom" aria-controls=""><span>الموديلات</span> <i class="fa fa-angle-left pull-left"></i></a>
</li>
{{-- <li role="presentation" class="{{( Request()->segment(3) == 'colors')? 'active' : ''}} ">
    <a href="{{url('/dashboard/settings/colors')}}" class="with-border-bottom" aria-controls=""><span>الوان السيارات</span> <i class="fa fa-angle-left pull-left"></i></a>
</li> --}}
<li role="presentation" class="{{( Request()->segment(3) == 'years')? 'active' : ''}} ">
    <a href="{{url('/dashboard/settings/years')}}" class="with-border-bottom" aria-controls=""><span>سنة الاصدار</span> <i class="fa fa-angle-left pull-left"></i></a>
</li>
{{-- <li role="presentation" class="{{( Request()->segment(3) == 'car-carrier-types')? 'active' : ''}} ">
    <a href="{{url('/dashboard/settings/car-carrier-types')}}" class="with-border-bottom" aria-controls=""><span>انواع الساطحات</span> <i class="fa fa-angle-left pull-left"></i></a>
</li> --}}
<li role="presentation" class="">
    <a href="{{url('/dashboard/branches')}}" class="with-border-bottom" aria-controls=""><span>الفروع </span> <i class="fa fa-angle-left pull-left"></i></a>
</li>
{{-- <li role="presentation" class="">
    <a href="{{url('/dashboard/settings/price-lists')}}" class="with-border-bottom" aria-controls=""><span>قائمة الاسعار </span> <i class="fa fa-angle-left pull-left"></i></a>
</li> --}}
