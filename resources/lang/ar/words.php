<?php

$li = \App\Models\OrderStatus::orderBy('sort','asc')->get();
$arr = [
    'authFailed' => 'فشل التحقق يرجي تسجيل الدخول',
    'no result' => 'لا توجد نتائج',
    'account under review' => 'تم انشاء الحساب بنجاح و جاري مراجعه البيانات من الادارة',
    'all'           => 'تسليم كامل الطرد',
    'part'          => 'تسليم جزء من الطرد',
    'replace'       => 'طرد مقابل طرد - استبدال',
    'return'        => 'استرجاع طرد' ,
    'collect_money' => 'تحصيل اموال' ,
];
foreach ($li as $k) {
    $arr[$k->key] = $k->name;
}
return $arr;
