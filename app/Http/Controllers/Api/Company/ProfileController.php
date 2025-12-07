<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Requests\Api\UpdateCompanyRequest;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Traits\Api\CompanyOperations;
use Illuminate\Http\Request;
use App\Models\Company;
use Carbon\Carbon;
use Auth;
use Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class ProfileController extends Controller
{
    use CompanyOperations;
    public function profile(Request $request)
    {
        $company = Auth::guard('api-company')->user();
        return Response()->json([
                'data' => [
                    'company' => $company
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function update(Request $request)
    {
        $company = Auth::guard('api-company')->user();

        $this->UpdateRecords($company, $request);
        $company = Company::find($company->id);
        $company->token = auth('api-company')->tokenById($company->id);
        return Response()->json([
                'data' => [
                    'company' => $company
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function notifications(Request $request)
    {
        $user = auth('api-company')->user();
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
        $user = auth('api-company')->user();
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
    public function ForgetPassword(Request $request)
    {
        $email = $request->get('email');
        $driver = Company::where('email', $email)->first();
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
}
