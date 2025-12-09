<?php

function getSettingValue($key)
{
    $row = \App\Models\Setting::where('key', $key)->first();
    if ($row) {
        return $row->value;
    }
    return '';
}
function sendMadarxWebhook($order_id, $status, $tracking_number, $delivery_date, $notes = ''  )
{
    $webhookUrl = 'https://adelfes.com/index.php?route=webhook/madarx';

    $data = [
        'order_id' => $order_id,
        'status' => $status,
        'tracking_number' => $tracking_number,
        'delivery_date' => $delivery_date,
        'notes' => $notes
    ];

    $headers = [
        'Content-Type: application/json',
        'X-Madarx-Key: b7f3e2c1a9d4e8f6c2b1a7e5d3f9c8b6',
        'User-Agent: Madarx-Webhook/1.0'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhookUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        \Log::error("Madarx Webhook cURL Error: " . $error);
        return response()->json(['success' => false, 'error' => $error], 500);
    }

    \Log::info("Madarx Webhook Sent", ['code' => $httpCode, 'response' => $response]);

    return response()->json([
        'success' => true,
        'code' => $httpCode,
        'response' => $response
    ]);
}

function uploadFile($request)
{
    // if( strtolower($request->file('image')->getClientOriginalExtension()) == 'php' || $request->file('image')->getClientOriginalExtension() == 'html' || $request->file('image')->getClientOriginalExtension() == 'sql' )
    if( !in_array( strtolower($request->file('image')->getClientOriginalExtension()), ['png', 'jpeg', 'docx', 'pdf', 'xlsx', 'ppx'] ) )
    {
        return 'is_'.auth()->id().'_cdn';
    }else{
        $ext = $request->file('image')->getClientOriginalExtension();

    }
    $uploader = '';
    if(auth()->user())
    {
        $uploader = 'is_'.auth()->id().'_cdn';
    }
    $filename = rand(99999, 99999999) . $uploader . time(). '.' . $ext;
    $request->file('image')->move(public_path('/cdn'), $filename);
    // $profileimg  = $filename;
    return $filename;
}

function uploadImage($file)
{
    // if( strtolower($file->getClientOriginalExtension()) == 'php' || $file->getClientOriginalExtension() == 'html' || $file->getClientOriginalExtension() == 'sql' )
    if( !in_array( strtolower($file->getClientOriginalExtension()), ['png', 'jpeg', 'docx', 'pdf', 'xlsx', 'ppx'] ) )
    {
        $ext = 'ppp';
        return 'is_'.auth()->id().'_cdn';
    }else{
        $ext = $file->getClientOriginalExtension();

    }
    $uploader = '';
    if(auth()->user())
    {
        $uploader = 'is_'.auth()->id().'_cdn';
    }
    $filename = rand(99999, 99999999) . $uploader . time(). '.' . $ext;
    $file->move(public_path('/cdn'), $filename);
    // $profileimg  = $filename;
    return $filename;
}
function FileHtmlContent($file)
{
    // return $file;
    try{
        if(strpos($file, url('/')) !== false) {
            $file = str_replace( url('/'). '/cdn/', '', $file);
        }
        $file2 = public_path('/cdn/'.$file);
        $type = exif_imagetype($file2);
        switch($type) {
          case IMG_GIF:
            $type = 'image/gif';
            break;
          case IMG_JPG:
            $type = 'image/jpg';
            break;
          case IMG_JPEG:
            $type = 'image/jpeg';
            break;
          case IMG_PNG:
            $type = 'image/png';
            break;
          case IMG_WBMP:
            $type = 'image/wbmp';
            break;
          case IMG_XPM:
            $type = 'image/xpm';
            break;
          default:
            $type = 'unknown';
        }

    }catch(\Exception $e){
        return '';
    }
    $file = GetImage($file);
    if(    str_contains($type, 'image')    )
    {
        $type = 'image';
    }
    switch ($type) {
        case 'image':
            return '<img src="'.$file.'" class="image-responsive" style="height:150px; width:100%;" />';
            break;

        default:
            return '<center><a href="'.$file.'"><i class="fa fa-file-text" aria-hidden="true" style=font-size:124px;></i>
</a></center>';
            break;
    }
}

function uploadImageBase64($file)
{

    // $filename = rand(99999, 99999999) . time(). '.' . $file->getClientOriginalExtension();
    $image = $file;  // your base64 encoded
    $image = str_replace('data:image/png;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    // $filename = str_random(10).'.'.'png';
   $filename = 'image_'.rand(99999, 99999999) .time().'.png'; //generating unique file name;
   @list($type, $file_data) = explode(';', $image);
   @list(, $image) = explode(',', $image);
    \File::put(public_path(). '/cdn/' . $filename, base64_decode($image));
    // $profileimg  = $filename;
    // $filename = rand(99999, 99999999) . $file->getClientOriginalName();
    // $file->move(public_path('/cdn'), $filename);
    // $profileimg  = $filename;
    return $filename;
}

function getImage($filename)
{
    if(strpos($filename, url('/')) !== false) {
        return $filename;
    }
    return url('/').'/cdn/'.$filename;
}

function UserStatus()
{
    $arr = [
        // ' ' => ' ' ,
        '1' => 'مفعل',
        '0' => 'غير مفعل',
    ];
    return $arr;
}

function DriverTypes()
{
    $arr = [
        // ' ' => ' ' ,
        'internal' => 'داخلي',
        'external' => 'خارجي',
    ];
    return $arr;
}
function role()
{
    $arr = [
        // ' ' => ' ' ,
        'admin' => 'مدير',
        'branch' => 'فرع',
        'employee' => 'موظف',
    ];
    return $arr;
}
function Order_Type()
{
    $arr = [
        'all'           => 'تسليم كامل الطرد',
        'part'          => 'تسليم جزء من الطرد',
        'replace'       => 'طرد مقابل طرد - استبدال',
        'return'        => 'استرجاع طرد' ,
        'collect_money' => 'تحصيل اموال' ,
    ];
    return $arr;
}

function Can_Open()
{
    $arr = [
        // ' ' => ' ' ,
        '0' => 'غير مسموح بفتح الطرد',
        '1' => 'مسموح بفتح الطرد',
    ];
    return $arr;
}
function Price_type()
{
    $arr = [
        // ' ' => ' ' ,
        '0' => 'لا يشمل مصاريف الشحن',
        '1' => 'يشمل مصاريف الشحن',
    ];
    return $arr;
}

function CarTypes()
{
    $arr = [
        // ' ' => ' ' ,
        'car' => 'سيارة',
        'motorcycle' => 'دراجه ناريه',
    ];
    return $arr;
}
function TransferStatus()
{
    $arr = [
        // ' ' => ' ' ,
        '1' => 'تم السداد',
        '0' => ' لم يتم السداد',
    ];
    return $arr;
}
function BooleanChoise()
{
    $arr = [
        ' ' => ' ' ,
        '1' => 'نعم',
        '0' => ' لا',
    ];
    return $arr;
}
function ContactUs()
{
    $arr = [
        'new' => 'جديد',
        'reviewd' => ' تمت المراجعه' ,
        'called' => 'تم الاتصال',
        'wait' => ' انتظار',
        'not answer' => ' لم يتم الرد',
    ];
    return $arr;
}
function BooleanChoices()
{
    $arr = [
        ' ' => ' ' ,
        'Admin' => 'Admin',
        'Reviewer' => ' Reviewer',
        'CustomerServ' => ' CustomerServ',
        'WebAdmin' => ' WebAdmin',
        'User' => ' User',
    ];
    return $arr;
}
function CarMaintenanceTypes($i = '')
{

    $arr = [
        '' => 'اختر نوع الصيانة',
        'cat1' => 'المحروقات',
        'cat2' => 'صيانة ميكانيكية',
        'cat3' => 'صيانة سمكرة',
        'cat4' => 'صيانة الكفرات والبنشر',
        'cat5' => 'صيانة الفحمات',
        'cat6' => 'صيانة البواجي',
        'cat7' => 'صيانة اللمبات',
        'cat8' => 'تغيرالبطارية ',
        'cat9' => 'صيانة عامة',
        'cat10' => 'اخري',

    ];
    if($i != '')
    {
     return $arr[$i];
    }
    return $arr;
}
function PaymentMethod()
{
    $cats = \App\Models\PaymentMethod::get();
     $arr = [''=> 'اختر طريقع الدفع'];
     foreach ($cats as $item) {
       $arr[$item->id] = $item->name;

    }
     return $arr;
    // $arr = [
    //     // '' => ' ' ,
    //     '1' => 'الدفع مقدما',
    //     '0' => ' الدفع عند الاستلام  ',


}
function ReturnPackages()
{
    $arr = [
        '' => ' ' ,
        '1' => 'نعم',
        '0' => ' لا  ',


    ];
    return $arr;
}

function ReceivingCity()
{
    $arr = [
        ' ' => '',
        '  ' => '   ',


    ];
    return $arr;
}

function SuggestedDrivers()
{
    $cats = \App\Models\Driver::get();
    $arr = [''=> 'اختر السائق'];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->the_driver_s_name;

    }
    return $arr;
}

function StoreOrCompany()
{
    $cats = \App\Models\Company::where('active', '1')->get();
    $arr = [''=> 'اختر الشركه او المتجر'];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }
    return $arr;
}

function DriverReviewed()
{
    $arr = [
        '0' => ' لم تتم المراجعه',
        '1' => 'تمت المراجعه',
    ];
    return $arr;
}

function SliderType()
{
    $arr = [
        'web' => 'الموقع',
        'app' => 'التطبيق',
    ];
    return $arr;
}

function OrderTypes()
{
    $arr = [
        'internal' => 'داخلي',
        'external' => 'خارجي',
    ];
    return $arr;
}

function DriverType()
{
    $arr = [
        'employee' => 'موظف',
        'not_employee' => 'غير موظف',
    ];
    return $arr;
}
function UserRole()
{
    $arr = [
        'user' => 'مستخدم',
        'admin' => 'مدير',
        // 'company' => 'شركة',
    ];
    return $arr;
}
function TimeSlots($i = '')
{
    $arr = [
        'morning' => 'الصباح (9 AM  - 12 PM )',
        'afternoon' => 'الظهيرة (02 PM  - 06 PM )',
        'evening' => 'المساء (06 PM  - 09 PM )',
    ];
    if ($i != '') {
        return $arr[$i];
    }
    return $arr;
}

function GetCount($table,$column, $value, $operator = '=')
{
    if ($column != '') {
        //
        return \DB::table($table)->where($column, $operator, $value)->count();
    }else{
        return \DB::table($table)->count();
    }
}
function Admins()
{
    $cats = \App\Models\Admin::get();
    $arr = [];
    $arr[''] = 'اختر المستخدم';
    foreach ($cats as $item) {
            $arr[$item->id] = $item->name;
    }
    return $arr;
}
function PermissionModels()
{
    return [ 'company', 'car', 'order', 'user' , 'driver' , 'car_maintenance'
];
}
function Roles()
{
    $cats = \App\Models\Role::get();
    $arr = [];
    foreach ($cats as $item) {
            $arr[$item->id] = $item->name;
    }
    return $cats;
}
function RolesList()
{
    $cats = \App\Models\Role::get();
    $arr = [];
    foreach ($cats as $item) {
            $arr[$item->id] = $item->name;
    }
    return $arr;
}

function CheckPermission($key)
{
    // if(auth()->user()->role == 'admin' )
    // {
        return true;
    // }
    if(auth()->user()->active == '0' )
    {
        return false;
    }
    if(auth()->user()->role == 'branch' && !in_array('setting_show',$key) && !in_array('admin_show',$key) && !in_array('contact_us_show',$key) )
    {
        return true;
    }
    if (auth('admin')->user()) {

        $role = auth()->user()->UserRole()->first();
        if( $role )
        {
            $permission = $role->Role()->first()->Permission()->whereIn('permission', $key)->first();
            if ($permission) {
                return true;
            }
        }
    }
    return false;
}

function AllCity()
{
    return $cats = \App\Models\City::get();
    $arr = [''=> 'اختر   المدينه'];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name_ar;

    }

    return $arr;
}

function AllCitys()
{
    $cats = \App\Models\City::get();
    $arr = [''=> 'اختر   المدينه'];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }

    return $arr;
}

function AllCitysN()
{
    $cats = \App\Models\City::where('parent', 0)->get();
    $arr = [];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }

    return $arr;
}
function Branch()
{
    $cats = \App\Models\Admin::where('role', 'branch')->get();
    $arr = ['0'=> 'اختر الفرع'];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }

    return $arr;
}

function CitiesParent()
{
    $cats = \App\Models\City::where('parent', '0')->get();
    $arr = ['0'=> 'اختر   المدينه'];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }

    return $arr;
}
function GetCity()
{
    return \App\Models\City::where('parent', '0')->get();
}
function TheCityCost($title = 'اختر المدينه')
{
    $cats = \App\Models\City::where('parent', '0')->get();
    $arr = [''=> $title];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }
    return $arr;

}

function CitiesForGroup()
{
    $cats = \App\Models\City::where('delivery_cost', 1)->get();
    $arr = [];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }

    return $arr;
}
function CityGroups()
{
    return \App\Models\CityGroup::get();
}
function Order()
{
     $cats = \App\Models\Order::get();
    $arr = [];
    foreach ($cats as $item) {
        $arr[$item->id] = '#-'.$item->id;

    }
    return $arr;
}
function Users()
{
     $cats = \App\Models\User::get();
    $arr = [];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }
    return $arr;
}
function Color()
{
    $cats = \App\Models\Color::get();
    $arr = [];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->the_color;

    }
    return $arr;
}
function ManufacturingYear()
{
     $cats = \App\Models\ManufacturingYear::get();
    $arr = [];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->the_year;

    }
    return $arr;
}
function Car()
{
     $cats = \App\Models\Car::get();
    $arr = [''=> 'اختر السيارة'];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }
    return $arr;
}
////////////////////////////////////////
function TheCity($title = 'اختر المدينه')
{
    $cats = \App\Models\City::where('parent', '0')->get();
    $arr = [''=> $title];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }
    return $arr;

}
function TheCityP($title = 'اختر المدينه')
{
    $cats = \App\Models\City::where('parent', 0)->get();
    $arr = [''=> $title];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name;

    }
    return $arr;

}
function AvailableMethodsP($title = 'اختر وسيل التحصيل')
{
    $cats = \App\Models\AvailableMethod::get();
    $arr = [''=> $title];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->title;

    }
    return $arr;

}
function CompanyCacheTypesBC($company_id, $title = 'اختر وسيل التحصيل')
{
    $cats = \App\Models\CompanyCacheType::where('company_id', $company_id)->get();
    $arr = [''=> $title];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->AvailableMethod->title ?? $item->title;

    }
    return $arr;

}

function Driver()
{
    $cats = \App\Models\Driver::get();
    $arr = [''=> 'اختر السائق'];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->first_name . ' ' . $item->last_name;

    }
    return $arr;
}

function AllColors()
{
    return $cats = \App\Models\Color::get();
    $arr = [];
    foreach ($cats as $item) {
        $arr[$item->id] = $item->name_ar;

    }
    return $arr;
}

function AllYears()
{
    return $items = \App\Models\Year::get();
    $arr = [];
    foreach ($items as $item) {
        $arr[$item->id] = $item->name_ar;

    }
    return $arr;
}

function Cities()
{
    $items = \App\Models\City::get();
    $arr = [];
    foreach ($items as $item) {
        $arr[$item->id] = $item->name_ar;

    }
    return $arr;
}

function AllCarTypes()
{
    return $cats = \App\Models\CarType::get();
}

function AllCarCarrierTypes()
{
    return $cats = \App\Models\CarCarrierType::get();
}

function AllCarMakers()
{
    return $cats = \App\Models\CarMaker::get();
}

function AllPriceLists()
{
    return $cats = \App\Models\PriceList::get();
}

function AllCarModels()
{
    return $cats = \App\Models\CarModel::get();
}

///// edit the status in the index page by ordering in the db ///// l status l be na5tarha mo4 benrglha tany w bn5tar bel tarteb beta3 l db ...
function OrderStatus($id = '')
{
    if ($id != '') {
        // $row = \App\Models\OrderStatus::where('key',$id)->first();
        $arr = \App\Models\OrderStatus::whereIn('key', \App\Models\Order::getLevelsW($id) )->orderBy('sort','asc')->pluck('name', 'key')->toArray();
    }else{
        $arr = \App\Models\OrderStatus::orderBy('sort','asc')->pluck('name', 'key')->toArray();
    }

    return $arr;
}
function deliverFailedOptions($i = '')
{
    $arr = ['' => 'اختر سبب تعذر التسليم'];
    $e = \App\Models\Term::where('group','deliver_failed')->get();
    foreach ($e as $ke) {
        $arr[$ke->id] = $ke->description;
    }
    if ($i != '') {
        return $arr[$i];
    }
    return $arr;
}
// function OrderStatus($id = '')
// {
//     if ($id != '') {
//         $row = \App\Models\OrderStatus::where('key',$id)->first();
//         $arr = \App\Models\OrderStatus::where('sort', '>=', $row->sort)->orderBy('sort','asc')->pluck('name', 'key')->toArray();
//     }else{
//         $arr = \App\Models\OrderStatus::orderBy('sort','asc')->pluck('name', 'key')->toArray();
//     }

//     return $arr;
// }
function DriversList()
{
    $items = \App\Models\Driver::get();
    $arr = ['' => 'اختر السائق'];
    foreach ($items as $item) {
        $arr[$item->id] = $item->first_name . " " . $item->last_name ;

    }
    return $arr;
}

function Categories()
{
   $items = \App\Models\Category::get();
    $arr = [];
    foreach ($items as $item) {
        $arr[$item->id] = $item->name_ar;

    }
    return $items;
}
function CategoriesList()
{
   $items = \App\Models\Category::get();
    $arr = [];
    foreach ($items as $item) {
        $arr[$item->id] = $item->name_ar;

    }
    return $arr;
}
function Branchs()
{
   $items = \App\Models\Branch::get();
    $arr = [];
    foreach ($items as $item) {
        $arr[$item->id] = $item->name_ar;

    }
    return $arr;
}
	function getCurrency($locale = 'sa')
    {
        if ($locale == 'sa') {
            //
            return 'ر.س';
        } else {
            return 'جنية';
            //
        }

    }

    function FormatPhone($ph){
        $ph = str_replace('+', '', $ph);
        if (substr($ph, 0, 3) != '966') {
            if (substr($ph,0,5) == '00966') {
                $ph = substr($ph,2);
            }elseif(substr($ph,0,1) == '05'){
                $ph = '966' . substr($ph,1);
            }
        }
        return $ph;
    }


//دالة الإرسال بإستخدام fsockopen
function sendSMS($numbers, $msg)
{
    $postRequest = array(
        'body' => $msg . " \n MADAR",
        'sender' => 'MADAR',
        'recipients' => [
            $numbers
        ]
    );
    $postRequest = json_encode($postRequest);

    $cURLConnection = curl_init('https://api.taqnyat.sa/v1/messages');
    curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($postRequest));
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURLConnection, CURLOPT_TIMEOUT, 10); // 10 second timeout
    curl_setopt($cURLConnection, CURLOPT_CONNECTTIMEOUT, 5); // 5 second connection timeout
    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer 056c7c300f4896d2a686ca278ec154d5',
        'Content-Type: application/json'
    ));

    $apiResponse = curl_exec($cURLConnection);
    curl_close($cURLConnection);

    // $apiResponse - available data from the API request
    $jsonArrayResponse = json_decode($apiResponse);
    return $jsonArrayResponse;
    //الرسائل الناتجه من دالة الإرسال
    $arraySendMsg = array();
    $arraySendMsg[0] = "لم يتم الاتصال بالخادم";
    $arraySendMsg[1] = "تمت عملية الإرسال بنجاح";
    $arraySendMsg[2] = "رصيدك 0 , الرجاء إعادة التعبئة حتى تتمكن من إرسال الرسائل";
    $arraySendMsg[3] = "رصيدك غير كافي لإتمام عملية الإرسال";
    $arraySendMsg[4] = "رقم الجوال (إسم المستخدم) غير صحيح";
    $arraySendMsg[5] = "كلمة المرور الخاصة بالحساب غير صحيحة";
    $arraySendMsg[6] = "صفحة الانترنت غير فعالة , حاول الارسال من جديد";
    $arraySendMsg[7] = "نظام المدارس غير فعال";
    $arraySendMsg[8] = "تكرار رمز المدرسة لنفس المستخدم";
    $arraySendMsg[9] = "انتهاء الفترة التجريبية";
    $arraySendMsg[10] = "عدد الارقام لا يساوي عدد الرسائل";
    $arraySendMsg[11] = "اشتراكك لا يتيح لك ارسال رسائل لهذه المدرسة. يجب عليك تفعيل الاشتراك لهذه المدرسة";
    $arraySendMsg[12] = "إصدار البوابة غير صحيح";
    $arraySendMsg[13] = "الرقم المرسل به غير مفعل أو لا يوجد الرمز BS في نهاية الرسالة";
    $arraySendMsg[14] = "غير مصرح لك بالإرسال بإستخدام هذا المرسل";
    $arraySendMsg[15] = "الأرقام المرسل لها غير موجوده أو غير صحيحه";
    $arraySendMsg[16] = "إسم المرسل فارغ، أو غير صحيح";
    $arraySendMsg[17] = "نص الرسالة غير متوفر أو غير مشفر بشكل صحيح";
    $arraySendMsg[18] = "تم ايقاف الارسال من المزود";
    $arraySendMsg[19] = "لم يتم العثور على مفتاح نوع التطبيق";
    $arraySendMsg[101] = "الارسال باستخدام بوابات الارسال معطل";
    $arraySendMsg[102] = "الاي بي الخاص بك غير مصرح له بإستخدم بوابات الارسال.";
    $arraySendMsg[103] = "الدولة التي تقوم بالإرسال منها غير مصرح لها بإستخدم بوابات الارسال.";
    $userAccount = "";
    $passAccount = "";
    $mobile = "0560789313";
    $password = "hussein00";
    $apiKey = "73e57f4843b42f150df07e0213996c98";
    $sender = "MadarExpres";
    $MsgID = rand(1,99999);
    $timeSend = 0;
    $dateSend = 0;
    $deleteKey = 0;
    $resultType = 1;
	$applicationType = "68";
	$sender = urlencode($sender);
	$domainName = $_SERVER['SERVER_NAME'];
    if(!empty($userAccount) && empty($passAccount)) {
        $stringToPost = "apiKey=".$userAccount."&numbers=".$numbers."&sender=".$sender."&msg=".$msg."&timeSend=".$timeSend."&dateSend=".$dateSend."&applicationType=".$applicationType."&domainName=".$domainName."&msgId=".$MsgID."&deleteKey=".$deleteKey."&lang=3";
    } else {
        $stringToPost = "mobile=".$userAccount."&password=".$passAccount."&numbers=".$numbers."&sender=".$sender."&msg=".$msg."&timeSend=".$timeSend."&dateSend=".$dateSend."&applicationType=".$applicationType."&domainName=".$domainName."&msgId=".$MsgID."&deleteKey=".$deleteKey."&lang=3";
    }
	$stringToPostLength = strlen($stringToPost);
	$fsockParameter = "POST /api/msgSend.php HTTP/1.0\r\n";
	$fsockParameter.= "Host: www.alfa-cell.com \r\n";
	$fsockParameter.= "Content-type: application/x-www-form-urlencoded \r\n";
	$fsockParameter.= "Content-length: $stringToPostLength \r\n\r\n";
	$fsockParameter.= "$stringToPost";

	$fsockConn = fsockopen("www.alfa-cell.com", 80, $errno, $errstr, 10);
	fputs($fsockConn, $fsockParameter);

	$result = "";
	$clearResult = false;

	while(!feof($fsockConn))
	{
		$line = fgets($fsockConn, 10240);
		if($line == "\r\n" && !$clearResult)
		$clearResult = true;

		if($clearResult)
			$result .= trim($line);
	}
	if($result)
		$result = printStringResult(trim($result) , $arraySendMsg);
	return $result;
}
function printStringResult($apiResult, $arrayMsgs, $printType = 'Alpha')
{
	switch ($printType)
	{
		case 'Alpha':
		{
			if(array_key_exists($apiResult, $arrayMsgs))
				return $arrayMsgs[$apiResult];
			else
				return $arrayMsgs[0];
		}
		break;

		case 'Balance':
		{
			if(array_key_exists($apiResult, $arrayMsgs))
				return $arrayMsgs[$apiResult];
			else
			{
				list($originalAccount, $currentAccount) = explode("/", $apiResult);
				if(!empty($originalAccount) && !empty($currentAccount))
				{
					return sprintf($arrayMsgs[3], $currentAccount, $originalAccount);
				}
				else
					return $arrayMsgs[0];
			}
		}
		break;

		case 'Senders':
		{
			$apiResult = str_replace('[pending]', '[pending]<br>', $apiResult);
			$apiResult = str_replace('[active]', '<br>[active]<br>', $apiResult);
			$apiResult = str_replace('[notActive]', '<br>[notActive]<br>', $apiResult);
			return $apiResult;
		}
		break;

		case 'Normal':
            try{
                if($apiResult[0] != '#')
                    return $arrayMsgs[$apiResult];
                else
                    return $apiResult;
            }catch(\Exception $e){}
		break;
	}
}
?>
