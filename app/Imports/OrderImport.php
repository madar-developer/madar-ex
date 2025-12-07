<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\OrderStatus;
use Maatwebsite\Excel\Concerns\ToModel;

class OrderImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if($row[0] == '' || trim($row[0]) == 'اسم المستلم')
        {
            return null;
        }
        if (trim($row[4]) == '-') {
            $city = null;
        } else {
            $city = $row[4];
        }
        if (trim($row[8]) == '-') {
            $p_m = null;
        } else {
            $p_m = $row[8];
        }
        // return dd($row[1]);
        $Order =  Order::create([
            'recipent_name' => $row[0],
            'adress_details'  => $row[1],
            'phone' => $row[2],
            'notes'  => $row[3],
            'city_id'  => $city,
            'refrence_no' => $row[5],
            'packages_number'  => $row[6],
            'price'   => $row[7],
            'payment_method_id'  => $p_m,
            'company_id'  => Request()->get('company_id'),
            'status'  => 'new',

        ]);
        $s = str_replace(' ', '',date('Y m').$Order->id);
        $serial = 'mx-'.$s;
        $status_data = OrderStatus::where('key', 'new')->first();
            $log_data = [
                'status' => 'new',
                'details' => $status_data->details
                // 'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
            ];
        $Order->OrderLog()->create($log_data);
        $Order->update(['serial' => $serial, 'serial_no' => (int)$s]);
        return $Order;
    }
}
