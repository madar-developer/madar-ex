<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateDiscountRequest;
use App\Http\Requests\Admin\StoreDiscountRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\DiscountOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Discount;
use Auth;

class DiscountController extends Controller
{
    use DiscountOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:discount_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:discount_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:discount_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:discount_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $all = Discount::latest()->get();
        $discounts = Discount::latest()->get();
        $title = 'الخصومات';
        return view('admin.discounts.index', compact('discounts', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.discounts.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDiscountRequest $request)
    {
       
        $this->register($request);
        
        // Carbon::parse(date);
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
        return view('admin.discounts.show', compact('discount', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discount = Discount::find($id);
        $title = 'تعديل ';
        return view('admin.discounts.edit', compact('discount', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDiscountRequest $request, $id)
    {
      
        $discount = Discount::find($id);
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
