<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdatePaymentMethodRequest;
use App\Http\Requests\Admin\StorePaymentMethodRequest;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Traits\Admin\PaymentMethodOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;

class PaymentMethodController extends Controller
{
    use PaymentMethodOperations;
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:payment_method_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:payment_method_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:payment_method_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:payment_method_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $all = PaymentMethod::latest()->get();
        $method = PaymentMethod::latest()->get();
        $title = 'وسائل الدفع';
        return view('admin.payment-methods.index', compact('method', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.payment-methods.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/payment-methods')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Method $method)
    {
        $title = 'عرض ';
        return view('admin.payment-methods.show', compact('method', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $method = PaymentMethod::find($id);
        $title = 'تعديل ';
        return view('admin.payment-methods.edit', compact('method', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    //   return $request->all();
        $method = PaymentMethod::find($id);
        $this->UpdateRecords($method, $request);
      
        return redirect('/dashboard/payment-methods')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $method = PaymentMethod::find($id);
        $method->delete();
        return 'success';
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
