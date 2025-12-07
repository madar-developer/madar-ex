<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Requests\Api\UpdateDriverRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\DriverOrderFResource;
use App\Mail\SendMail;
use App\Traits\Api\DriverOperations;
use Illuminate\Http\Request;
use App\Models\Driver;
use Carbon\Carbon;
use Auth;
use Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class ProfileController extends Controller
{
    use DriverOperations;
    public function profile(Request $request)
    {
        $driver = Auth::guard('api-driver')->user();
        return Response()->json([
                'data' => [
                    'driver' => $driver
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function delete_account(Request $request)
    {
        $driver = Auth::guard('api-driver')->user();
        return Response()->json([
                'data' => [
                    // 'driver' => $driver
                ],
                'message' => 'deleted',
                'code' => getMsgCode('success')
        ]);
    }
    public function update(UpdateDriverRequest $request)
    {
        $driver = Auth::guard('api-driver')->user();

        $this->UpdateRecords($driver, $request);
        $driver = Driver::find($driver->id);
        $driver->token = auth('api-driver')->tokenById($driver->id);
        return Response()->json([
                'data' => [
                    'driver' => $driver
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }

    public function ForgetPassword(Request $request)
    {
        $email = $request->get('email');
        $driver = Driver::where('email', $email)->first();
        $pass = \Str::random(10);
        $driver->password = bcrypt($pass);
        $driver->save();
        $data = array(
            'msg' => "Please Use This Password To Login",
            'password' => $pass
        );
   			Mail::to($driver)->send(new SendMail($data));
        return Response()->json([
                'data' => new \stdClass,
                'message' => 'Check Your Email Inbox',
                'code' => getMsgCode('success')
        ]);
    }
    public function notifications(Request $request)
    {
        $user = auth('api-driver')->user();
        $notifications  = $user->unreadnotifications;
        // return $notifications;
        return Response()->json([
                'data' => [
                    'notifications' => $notifications
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function markNotificationReaded(Request $request)
    {
        $user = auth('api-driver')->user();
        $user->unreadnotifications->where('id', $request->get('id'))->markAsRead();
        $notifications  = $user->unreadnotifications;
        // return $notifications;
        return Response()->json([
                'data' => [
                    'notifications' => $notifications
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function statistics(Request $request)
    {
        $driver = auth('api-driver')->user();
        $total_orders_count = $driver->Order()->where('status', '<>', 'returned')->where('collected','<>', 1)->count();
        $total_orders_price = $driver->Invoice()->where('orders.status', 'delivered')->sum('orders.price');
        $total_money_network = $driver->Invoice()->where('orders.status', 'delivered')->where('orders.cash_type', 'network')->sum('orders.price');
        $total_money_cash = $driver->Invoice()->where('orders.status', 'delivered')->where('orders.cash_type', 'cash')->sum('orders.price');
        $driver_money = $driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('invoices.driver_cost');
        $company_money = $driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('total_price') - $driver->Invoice()->where('orders.status', 'delivered')->where('orders.collected', 0)->sum('invoices.driver_cost');
        return Response()->json([
                'data' => [
                    'total_orders_count' => $total_orders_count,
                    'total_orders_price' => $total_orders_price,
                    'total_money_network' => $total_money_network,
                    'total_money_cash' => $total_money_cash,
                    'company_money' => $company_money,
                    'driver_money' => $driver_money,
                    'total_money' => $company_money + $driver_money,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function finance(Request $request)
    {
        $driver = auth('api-driver')->user();
        // $finances = $driver->DriverFianance()->paginate(40);
        $orders = $driver->Order();
        if (Request()->has('date_from') && Request()->get('date_from') != '') {
            $date_from = Request()->get('date_from');
            $search['date_from'] = $date_from;
            $date_from = Carbon::parse(Request()->get('date_from'));
            $orders = $orders->whereDate('created_at', '>=', $date_from);
            if (!Request()->has('date_to') || Request()->get('date_to') == '') {
                $orders = $orders->whereDate('created_at', '=', $date_from);
            }
        }
        if (Request()->has('date_to') && Request()->get('date_to') != '') {
            $date_to = Request()->get('date_to');
            $search['date_to'] = $date_to;
            $date_to = Carbon::parse(Request()->get('date_to'));
            $orders = $orders->whereDate('created_at', '<=', $date_to);
        }
        $orders = $orders->paginate(40);
        $orders->data = DriverOrderFResource::collection($orders);

        return Response()->json([
                'data' => [
                    // 'finances' => $finances,
                    'orders' => $orders
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
}
