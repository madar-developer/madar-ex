<?php

$li = \App\Models\OrderStatus::orderBy('sort','asc')->get();
$arr = [
    'authFailed' => 'authentication Failed',
    'no result' => 'No Result Found',
    'account under review' => 'Account Under Review',
    'all'           => 'Full Delivery',
    'part'          => 'Part Delivery',
    'replace'       => 'change Packages',
    'return'        => 'return Packages' ,
    'collect_money' => 'Collect Money' ,
];
foreach ($li as $k) {
    $arr[$k->key] = $k->name;
}
return $arr;
