<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateEventDiscountRequest;
use App\Http\Requests\Admin\StoreEventDiscountRequest;
use App\Http\Controllers\Controller;
use App\Models\EventDiscount;
use App\Traits\Admin\EventDiscountOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;

class EventDiscountController extends Controller
{
    use EventDiscountOperations;
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:event_discount_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:event_discount_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:event_discount_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:event_discount_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $all = EventDiscount::latest()->get();
        $discount = EventDiscount::latest()->get();
        $title = 'خصومات المناسبات';
        return view('admin.event-discounts.index', compact('discount', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.event-discounts.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventDiscountRequest $request)
    {
        $this->register($request);
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        $title = 'عرض ';
        return view('admin.event-discounts.show', compact('discount', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discount = EventDiscount::find($id);
        $title = 'تعديل ';
        return view('admin.event-discounts.edit', compact('discount', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventDiscountRequest $request, $id)
    {
      
        $discount = EventDiscount::find($id);
        $this->UpdateRecords($discount, $request);
      
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        if ($discount->image) {
            @unlink(public_path('/cdn/'.$discount->image));
        }
        $discount->delete();
        return 'success';
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
