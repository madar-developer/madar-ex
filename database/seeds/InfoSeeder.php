<?php

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\TheCity;
use App\Models\Shop;
use App\Models\PaymentMethod;
use App\Models\Driver;
use App\Models\Color;
use App\Models\ManufacturingYear;
use App\Models\TypeOfCar;
use App\Models\User;
use App\Models\Company;

class InfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country=[
            'name_of_the_country' => 'السعودية' . ' - '. time(),
            'code' => 'SA',
            'currency' => 'ريال',
            'currency_code' => 'SAR',
            'phone_code'  => '966',
            'phone_number'  => '10',
        ];

        $city=[
            'the_city_name'=>'لرياض' . ' - '. time(),
             'code'=>'RUH',
             'type'=>'3'
        ];

        $shop=[
            'the_name_of_the_store_or_company'=>'shop' . ' - '. time(),
             'first_name'=>'Mohamed' . ' - '. time(),
             'last_name'=>'Ahmed' . ' - '. time(),
             'phone_code'=>'2',
             'mobile_number' =>'01' . time(),
             'e_mail' =>'mody.king565@gmail.com'.time(),
            
        'country' =>'1',
         'city' =>'1',
         'district' =>'174 D Giza' . ' - '. time(),
         'commercial_record' =>'45564654',
         'password' =>bcrypt('12345678'),
         'image' =>'349',
         'case' =>'0',
         'ilnk_store' =>'https://www.google.com.eg/?gfe_rd=cr&amp;dcr=0&amp;ei=1u3jWaPkDbT_8AfUiKPgDw&amp;gws_rd=ssl',
        
        'secret' =>'587a2e9edb760469ed1',
         'rel' =>'0',
         'activeCode' =>'328269',
         'activated' =>'1',
         'google_uid' =>'',
         'fb_uid' =>'',
         'tw_uid' =>'',
         'discount' =>'20',
        
        'shop_type' =>'1',
         'size' =>'1',
         'car_type' =>'1',
         'payment_method' =>'1',
         'return_goods' =>'0',
         'same_zone_discount' =>'0',
         'private_car'=>'0'
        ];

        $payment=[
            'the_name_of_the_payment_method'=>'	 الدفع مقدماً' . ' - '. time(),
             'the_description'=>'يتم دفع قيمه التوصيل مقدما' . ' - '. time(),
        ];

        $driver=[
            'the_driver_s_name'=>'driver' . ' - '. time(),
             'the_driver_last_name'=>' n ' . ' - '. time(),
            //  'image'=>'',
             'mobile_number'=>'00201'.time(),
             'id_number' =>time(),
             'email' =>'shimaa@gmail.com'.time(),

            'password' =>bcrypt('12345678'),
             'license_number' =>'5465465456',
             'the_expiration_date_of_the_license' =>'2017-10-12',
             'end_date_of_identity' =>'2017-10-20',

            'nationality' =>'مصر' . ' - '. time(),
             'counrty' =>'1',
             'city' =>'1',
             'district' =>'Giza',
             'the_car' =>'1',
             'date_of_receipt_of_the_vehicle' =>'2017-10-12 23:57:00',

            'secret' =>'5d2cafe70e587e0b943',
             'status' =>'0',
             'rel' =>'0',
             'activated' =>'1',
             'active_code'=>'1111'
        ];
        
        $color=[
            'the_color'=>'موف' . ' - '. time(),
        ];
        
        $years=[
            'the_year'=>'2020' . ' - '. time() ,
        ];

        $carTypes=[
            'type_of_car'=>'مميزة'  . ' - '. time()
             
        ];

        $user=[
            'user_name'=>'shimaa' . ' - '. time(),
             'e_mail'=>'shimaa@admin.com'.time(),
             'password'=>bcrypt('12345678'),
             'user_type'=>'Admin',
             'mobile_number' =>'01'.time(),
             'city' =>'1',
             'country' =>'1',
             'district' =>'زهراء مدينه نصر' . ' - '. time(),
             'profile_image' =>'1',
        ];

        $admin=[
            'user_name'=>'shimaa',
             'e_mail'=>'shimaa@admin.com',
             'password'=>bcrypt('12345678'),
             'user_type'=>'Admin',
             'mobile_number' =>'1019699363',
             'city' =>'1',
             'country' =>'1',
             'district' =>'زهراء مدينه نصر',
             'profile_image' =>'1',
        ];
        $a=1;
        while ($a <= 2) {
            //
            Country::create($country); 
            TheCity::create($city); 
            Color::create($color); 
            ManufacturingYear::create($years); 
            TypeOfCar::create($carTypes); 
            PaymentMethod::create($payment); 
            Shop::create($shop); 
            Driver::create($driver); 
            User::create($user); 
            $a++;
        }
        User::create($admin); 
    }
}
