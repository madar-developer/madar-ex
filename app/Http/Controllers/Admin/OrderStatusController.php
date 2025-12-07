<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Http\Requests\Admin\StoreOrderStatusRequest;
use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use App\Traits\Admin\OrderStatusOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;

class OrderStatusController extends Controller
{
    use OrderStatusOperations;
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:order_status_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:order_status_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:order_status_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:order_status_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $all = OrderStatus::latest()->get();
        $method = OrderStatus::latest()->get();
        $title = 'حالات الطلب';
        return view('admin.order-status.index', compact('method', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.order-status.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderStatusRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/order-status')->with('success', 'data added successfully');
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
        return view('admin.order-status.show', compact('method', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $status = OrderStatus::find($id);
        $title = 'تعديل ';
        return view('admin.order-status.edit', compact('status', 'title'));
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
        $method = OrderStatus::find($id);
        $this->UpdateRecords($method, $request);
      
        return redirect('/dashboard/order-status')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
