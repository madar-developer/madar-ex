<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Requests\Api\UpdateDriverRequest;
use App\Http\Controllers\Controller;
use App\Traits\Api\DriverOperations;
use Illuminate\Http\Request;
use App\Models\Driver;
use Carbon\Carbon;
use Auth;
use Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {

            $email = $request->get('email');
            $password = $request->get('password');
            $field = (filter_var($email, FILTER_SANITIZE_NUMBER_INT))? 'phone' : 'email';
                $credentials = [$field => $email, 'password' => $password];
                $driver = Driver::where($field, $email)->first();
                // if($token = auth('api-driver')->attempt($credentials))
                if($driver && $token = auth('api-driver')->login($driver))
                {
                    $driver = auth('api-driver')->user();
                    // $driver->update(['verify_code' => null]);
                    if(Request()->has('player_id') && !$driver->PlayerId()->where('player_id', '=', $request->get('player_id'))->first() )
                    {
                        $driver->PlayerId()->create(['player_id' => $request->player_id]);
                    }
                    $driver->token = $token;
                    return Response()->json([
                            'data'          => [
                                'driver'  => $driver,
                            ],
                            'message'       => 'success',
                            'code'          => getMsgCode('success'),
                        ]);
                }
            return Response()->json([
                        'data'   => new \stdClass,
                        'errors'       => [' '],
                        'message'       => trans('words.authFailed'),
                        'code'          => getMsgCode('authFailed'),
                    ]);
    }
    public function logout()
    {
        $user = auth('api-driver')->user();
            $user->PlayerId()->delete();
            auth('api-driver')->logout();
        return Response()->json([
            'data'          => new \stdClass,
            'message'       => 'success',
            'code'          => getMsgCode('success'),
        ]);
    }
}
