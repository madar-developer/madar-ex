<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use App\Models\Order;
use App\Models\Company;
use Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function PostForm(Request $request)
    {
        ContactUs::create($request->all());
        $res = [
            'code' => 200,
            'message' => ''
        ];
        $data = $request->all();
        try {
            //code...
        Mail::send('mails.reminder', ['data' => $data], function ($m) use ($data) {
            $m->from('info@madarex.sa', 'طلبات عرض اسعار');

            $m->to($data['email'],$data['name'])->subject('طلبات عرض اسعار  !');
        });
        ///////////// to handle error
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $res;



    }
    public function getOrderStatus($id)
    {
        // if (!Request()->has('store')) {
        //     $res = [
        //         'code' => 404,
        //         'html' => '',
        //         'message' => 'يرجي اختيار المتجر'
        //     ];
        //     return $res;
        // }
        $store = Request()->get('store');
        $id = str_replace('mx-', '', trim($id));
        $order = Order::where(function($q)use($id){
            $q->/*Where('refrence_no',$id)->or*/Where('serial',$id)->orWhere('serial_no',$id);
        });
        // if (Request()->has('store') && Request()->get('store') != '') {
        //     $order = $order->where('company_id', $store);
        // }
        $order = $order->first();
        $html = '';
        $i = 0;
        $class = 'done';
        if(!$order)
        {
            $res = [
                'code' => 200,
                // 'html' => $html,
                'html' => view('front.ajex.order-status', compact( 'class'))->render(),
                'message' => 'الشحنه غير موجوده.'
            ];
            return $res;
        }
        $res = [
            'code' => 200,
            'html' => view('front.ajex.order-status', compact('order', 'class'))->render(),
            'message' => trans('words.'.$order->status)
        ];
        return $res;


    }
    public function getOrderStatusCh(Request $request)
    {
        $id = $request->get('order_id');
        $order = Order::Where('refrence_no',$id)->orWhere('serial',$id)->first();
        $html = '';
        $i = 0;
        $class = 'done';
        if(!$order)
        {
            $res = [
                'code' => 404,
                'html' => $html,
                'message' => 'الشحنه غير موجوده.'
            ];
            return 'الشحنه غير موجوده.';
        }
        $res = [
            'code' => 200,
            'html' => view('front.ajex.order-status', compact('order', 'class'))->render(),
            'message' => trans('words.'.$order->status)
        ];
        return strip_tags($res['html']);


    }

    public function Register(Request $request)
    {
        return view('auth.com-reg');
    }

    public function RegisterPost(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name'          => 'required|max:255',
            'email'         => 'required|email|unique:companies|max:255',
            'phone'         => 'required|unique:companies|max:255',
            'commercial_record'         => 'required|unique:companies|max:255',
            'password'      => 'required|confirmed|max:255|min:6',
            'image'         => 'nullable|image',
        ];
        $this->validate($request, $rules);
        $data['active'] = 0;
        $data['password'] = bcrypt($request->password) ;
        Company::create($data);
        return redirect('/')->with('success' , 'تم انشاء الحساب بنجاح و جاري مراجعه البيانات من الادارة');
    }


}
