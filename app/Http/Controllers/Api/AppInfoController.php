<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SliderResource;
use App\Http\Resources\Api\StatusResource;
use App\Models\Setting;
use App\Models\OrderStatus;
use App\Models\FeedBack;
use App\Models\Slider;
use App\Models\Term;
use App\Models\WorkTime;

class AppInfoController extends Controller
{
    public function index()
    {

        $info = Setting::pluck('value', 'key')->toArray();

        return Response()->json([
                'data' => [
                    'info' => $info,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }

    public function about()
    {
        return Response()->json([
            'data' => [
                'about' => $this->localizedSetting('about'),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    public function privacy()
    {
        return Response()->json([
            'data' => [
                'privacy' => $this->localizedSetting('privacy'),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    public function contactUs()
    {
        $info = Setting::whereIn('key', ['email', 'phone', 'facebook', 'twitter', 'instagram'])
            ->pluck('value', 'key')
            ->toArray();

        return Response()->json([
            'data' => [
                'contact_info' => $this->localizedSetting('contact_info'),
            ],
            'message' => 'success',
            'code' => getMsgCode('success'),
        ]);
    }

    protected function appLang(): string
    {
        $locale = strtolower((string) (request()->header('app-lang') ?: request()->header('lang') ?: app()->getLocale()));
        $locale = substr($locale, 0, 2);

        return in_array($locale, ['ar', 'en'], true) ? $locale : 'ar';
    }

    protected function localizedSetting(string $key): string
    {
        $locale = $this->appLang();
        $localized = Setting::where('key', $key.'_'.$locale)->value('value');
        if ($localized !== null && $localized !== '') {
            return $localized;
        }

        return Setting::where('key', $key)->value('value') ?? '';
    }
    public function times()
    {

        $times = WorkTime::get();

        return Response()->json([
                'data' => [
                    'times' => $times,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function OrderType()
    {

        $res = [];
        foreach (Order_Type() as $key => $value) {
            $i = new \stdClass;
            $i->key = $key;
            $i->name = trans('words.'.$key);
            $res[] = $i;
        }
        return Response()->json([
                'data' => [
                    'order_types' => $res,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function slider()
    {
        $slider = Slider::where('type', 'app')->get();
        $slider = SliderResource::collection($slider);
        return Response()->json([
                'data' => [
                    'slider' => $slider,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function getStatuses()
    {
        if (request()->has('screen') && request()->get('screen') == 'scan') {
            $statuses = OrderStatus::whereIn('id', [11,2])->orderBy('sort', 'asc')->get();
        } else {
            $statuses = OrderStatus::orderBy('sort', 'asc')->get();
        }
        // $statuses = OrderStatus::orderBy('sort', 'asc')->get();
        $statuses = StatusResource::collection($statuses);
        return Response()->json([
                'data' => [
                    'statuses' => $statuses,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function UploadFile(Request $request)
    {
        if(Request()->has('file_name'))
        {
            @unlink(public_path('/cdn/'.Request()->get('file_name')));
        }
        $image = '';
        if ($request->hasFile('image') ) {
                $image = uploadImage($request->file('image'));
        }
        // base64
        if ($request->has('os') && $request->get('os') == 'android' && $request->has('image')) {
                $image = uploadImageBase64($request->get('image'));
        }
        // base64
        return Response()->json([
                'data' => [
                    'image' => $image
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
    public function FailDeliverOption()
    {

        $terms = Term::where('group', 'deliver_failed')->select('id','description')->get();

        return Response()->json([
                'data' => [
                    'terms' => $terms,
                ],
                'message' => 'success',
                'code' => getMsgCode('success')
        ]);
    }
}
