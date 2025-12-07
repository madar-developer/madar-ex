<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AMResource;
use App\Http\Resources\Api\CityResource;
use App\Http\Resources\Api\GuestOrderLogResource;
use Validator;
use App\Models\City;
use App\Models\PaymentMethod;
use App\Models\AvailableMethod;
use App\Models\Company;
use App\Models\Order;
use Auth;
use PDF;
use App\Http\Resources\Api\GuestOrderResource;
use App\Http\Resources\Api\OrderV2Resource as OrderResource;
use App\Http\Resources\Api\PMResource;
use App\Http\Resources\Api\StatusResource;
use App\Models\OrderStatus;

class ServiceController extends Controller
{
    public function StatusList()
    {
        $statuses = OrderStatus::orderBy('sort', 'asc')->get();
        $statuses = StatusResource::collection($statuses);
        return response()->json([
            'data' => [
                'statuses' => $statuses,
            ],
            'message' => 'allowed statuses',
            'code' => 200
        ], 200);
    }
    public function info()
    {
        //
        $cities = City::get();
        $cities = CityResource::collection($cities);
        $payment_methods = PaymentMethod::get();
        $payment_methods = PMResource::collection($payment_methods);
        $acceptable_payment_methods = AvailableMethod::get();
        $acceptable_payment_methods = AMResource::collection($acceptable_payment_methods);
        return response()->json([
            'data' => [
                'cities' => $cities,
                'payment_methods' => $payment_methods,
                'acceptable_payment_methods' => $acceptable_payment_methods,
            ],
            'message' => 'allowed cities',
            'code' => 200
        ], 200);
    }
    public function addOrder(Request $request)
    {
        $rest_token = Request()->get('rest_token');
        $com = Company::where('rest_token', $rest_token)->first();
        if (!$com) {
            return response()->json([
                'data' => [],
                'errors' => ['token error'],
                'message' => 'token error',
                'code' => 103
            ], 200);
        }
        $data = $request->all();
        $rules = [
            'recipient_name' => 'required|max:255' ,
            'recipient_phone' => 'required|max:255' ,
            'city_code' => 'required|exists:cities,city_code' ,
            'adress_details' => 'required|max:255' ,
            'packages_number' => 'required|max:11' ,
            'price' => 'required|max:11' ,
            'notes' => 'nullable' ,
            // 'refrence_no' => 'required|unique:orders' ,
            'refrence_no' => 'required' ,

        ];
        $messages = [
            'recipient_name.required' => 'اسم المستلم مطلوب' ,
            'recipient_phone.required' => 'رقم جوال المستلم مطلوب' ,
            'city_code.required' => 'المدينة مطلوبة' ,
            'city_code.exists' => 'كود المدينة غير صحيح' ,
            'adress_details.required' => 'تفاصيل العنوان مطلوبة' ,
            'packages_number.required' => 'عدد الطرود مطلوب' ,
            'price.required' => 'السعر مطلوب' ,
            'refrence_no.required' => 'الرقم المرجعي مطلوب' 

        ];
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'errors' => $validator->errors(),
                'message' => implode(' , ', $validator->errors()->all()),
                'code' => 103
            ], 200);
        }
        if($request->has('price')){
            $data['price'] = str_replace(',', '', $request->get('price') );
        }
        if($count = Order::where('refrence_no',$request->refrence_no)->count())
        {
            // $data['refrence_no'] = $request->refrence_no . '' .$count;
            $data['refrence_no'] = $request->refrence_no;
        }
        $data['recipent_name'] = $request->recipient_name;
        $data['phone'] = $request->recipient_phone;
        $data['city_id'] = City::where('city_code', $request->city_code)->first()->id;
        if ($request->price > 0) {
            $data['payment_method_id'] = 1;

        }else{
            $data['payment_method_id'] = 4;

        }
        $data['status'] = 'new';
        $Order = $com->Order()->create($data);
        $s = str_replace(' ', '',date('Y m').$Order->id);
        $serial = 'mx-'.$s;
        $Order->update(['serial' => $serial, 'serial_no' => (int)$s]);
        //////////////////////// the date of creation order
        $status_data = OrderStatus::where('key', 'new')->first();
            $log_data = [
                'status' => 'new',
                'details' => $status_data->details
                // 'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
            ];
        $Order->OrderLog()->create($log_data);
        $Order->qr_code = 'data:image/png;base64,'.\DNS1D::getBarcodePNG($Order->refrence_no.'', 'C39+');

        $Order = new OrderResource($Order);
        return response()->json([
            'data' => [
                'order' => $Order
            ],
            'message' => 'created',
            'code' => 200
        ], 200);
    }
    public function getHistory()
    {
        $rest_token = Request()->get('rest_token');
        $id = Request()->get('order_id');
        $serial = Request()->get('serial');
        $serial = str_replace('mx-', '', $serial);

        $com = Company::where('rest_token', $rest_token)->first();
        $order = $com->Order()->Where('refrence_no',$id)->orWhere('serial',$id)->orWhere('id',$id)->first();
        if(Request()->has('serial') && Request()->get('serial') != '')
        {
            $order = $com->Order()->Where('serial_no',$serial)->first();
        }

        if (!$order) {
            return response()->json([
                'data' => [],
                'errors' => ['not found'],
                'message' => 'order not found',
                'code' => 404
            ], 200);
        }
        if (in_array($order->status, ['init', 'at_madar'])) {
            $step = 1;
        // } else if (in_array($order->status, [ 'deliver_failed', 'reschedule'])) {
        } else if (in_array($order->status, [ 'reschedule'])) {
            $step = 2;
        } else if (in_array($order->status, ['at_office'])) {
            $step = 3;
        } else if (in_array($order->status, ['delivered'])) {
            $step = 4;
        } else if (in_array($order->status, ['deliver_failed'])) {
            $step = 5;
        } else {
            $step = 0;
        }
        $statuses = OrderStatus::whereIn('key', ['new', 'at_madar', 'at_office', 'delivered'])->get();
        $statuses = StatusResource::collection($statuses);

        $history = $order->OrderLog()->get();
        $history = GuestOrderLogResource::collection($history);
        $order = new GuestOrderResource($order);
        return response()->json([
            'data' => [
                'logs' => $history,
                'order' => $order,
                'statuses' => $statuses,
                'step' => $step,
            ],
            'message' => 'order history',
            'code' => 200
        ], 200);
    }
    public function getToken(Request $request)
    {

        $email = $request->get('email');
        $password = $request->get('password');
        $field = (filter_var($email, FILTER_SANITIZE_NUMBER_INT))? 'phone' : 'email';
            $credentials = [$field => $email, 'password' => $password];
            $company = Company::where($field, $email)->first();
            // if($token = auth('api-company')->attempt($credentials))
            if($company && $token = auth('api-company')->login($company))
            {
                $company = auth('api-company')->user();
                if (!$company->rest_token) {

                    $company->rest_token = md5(time());
                    $company->save();
                }
                // $company->update(['verify_code' => null]);
                if(Request()->has('player_id') && !$company->PlayerId()->where('player_id', '=', $request->get('player_id'))->first() )
                {
                    $company->PlayerId()->create(['player_id' => $request->player_id]);
                }
                // $company->token = $token;
                return Response()->json([
                        'data'          => [
                            'company'  => $company,
                        ],
                        'message'       => 'success',
                        'code'          => getMsgCode('success'),
                    ]);
            }
        return Response()->json([
                    'data'   => new \stdClass,
                    'errors'       => [' '],
                    'message'       => 'authFailed',
                    'code'          => getMsgCode('authFailed'),
                ]);
    }
    public function printPdf(){
        $id = Request()->get('order_id');
        $serial = Request()->get('serial');
        $serial = str_replace('mx-', '', $serial);
        $rest_token = Request()->get('rest_token');
        $com = Company::where('rest_token', $rest_token)->first();
        $order = $com->Order()->Where('refrence_no',$id)/*->orWhere('serial',$id)->orWhere('id',$id)*/->first();
        if(Request()->has('serial') && Request()->get('serial') != '')
        {
            $order = $com->Order()->Where('serial_no',$serial)->first();
        }
        if (!$order) {
            return response()->json([
                'data' => [],
                'errors' => ['not found'],
                'message' => 'order not found',
                'code' => 404
            ], 200);
        }
        // return view('admin.orders.pdf', compact('order'));
        // $pdf = PDF::loadView('admin.orders.pdf', compact('order'));
        $pdf = PDF::loadView('admin.reports.pdf.order', compact('order')  );
        
        
       // return $pdf->download('invoice-'.$id.'.pdf');
       return $pdf->stream('invoice-'.$id.'.pdf');

    }
    public function cancelOrder(){
        $id = Request()->get('order_id');
        $serial = Request()->get('serial');
        $serial = str_replace('mx-', '', $serial);
        $rest_token = Request()->get('rest_token');
        $com = Company::where('rest_token', $rest_token)->first();
        $order = $com->Order()->Where('refrence_no',$id)->orWhere('serial',$id)->orWhere('id',$id)->first();
        if(Request()->has('serial') && Request()->get('serial') != '')
        {
            $order = $com->Order()->Where('serial_no',$serial)->first();
        }
        if (!$order) {
            return response()->json([
                'data' => [],
                'errors' => ['not found'],
                'message' => 'order not found',
                'code' => 404
            ], 200);
        }
        $order->update(['status' => 'cancelled']);
        $status_data = OrderStatus::where('key', 'cancelled')->first();
            $log_data = [
                'status' => 'cancelled',
                'details' => $status_data->details
                // 'details' =>  trans('words.'.$request->get('status')) . ' , ' . $request->get('notes')
            ];
        $order->OrderLog()->create($log_data);
        $history = $order->OrderLog()->get();
        $history = GuestOrderLogResource::collection($history);
        $order = new GuestOrderResource($order);
        return response()->json([
            'data' => [
                'logs' => $history,
                'order' => $order
            ],
            'message' => 'order history',
            'code' => 200
        ], 200);
    }
}
