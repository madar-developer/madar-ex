<?php

namespace App\Http\Controllers\Api\Company;

use App\Http\Requests\Api\StoreCompanyRequest;
use App\Http\Requests\Api\UpdateCompanyRequest;
use App\Http\Controllers\Controller;
use App\Traits\Api\CompanyOperations;
use Illuminate\Http\Request;
use App\Models\Company;
use Carbon\Carbon;
use Auth;
use Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class AuthController extends Controller
{
    public function signup(StoreCompanyRequest $request)
    {
        $data = $request->all();
        $data['active'] = 0;
        $data['password'] = bcrypt($request->password) ;
        Company::create($data);
        return Response()->json([
                        'data'   => new \stdClass,
                        'message'       => trans('words.account under review'),
                        'code'          => getMsgCode('success'),
                    ]);
    }
    public function login(Request $request)
    {

            $email = $request->get('email');
            $password = $request->get('password');
            $field = 'phone';
            if (strpos($email, '@') !== false) {
                $field = 'email';
            }
            // $field = (filter_var($email, FILTER_SANITIZE_NUMBER_INT))? 'phone' : 'email';
                $credentials = [$field => $email, 'password' => $password];
                $company = Company::where($field, $email)->first();
                // if($token = auth('api-company')->attempt($credentials))
                if($company && $token = auth('api-company')->login($company))
                {
                    $company = auth('api-company')->user();
                    // $company->update(['verify_code' => null]);
                    if(Request()->has('player_id') && !$company->PlayerId()->where('player_id', '=', $request->get('player_id'))->first() )
                    {
                        $company->PlayerId()->create(['player_id' => $request->player_id]);
                    }
                    $company->token = $token;
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
                        'message'       => trans('words.authFailed'),
                        'code'          => getMsgCode('authFailed'),
                    ]);
    }
    public function logout()
    {
        $user = auth('api-company')->user();
            // $user->PlayerId()->delete();
            auth('api-company')->logout();
        return Response()->json([
            'data'          => new \stdClass,
            'message'       => 'success',
            'code'          => getMsgCode('success'),
        ]);
    }
}
