<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateShopRequest;
use App\Http\Requests\Admin\StoreShopRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\ShopOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Shop;
use Auth;

class ShopController extends Controller
{
    use ShopOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('Permission:shop_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:shop_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:shop_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:shop_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $all = Shop::latest()->get();
        $shops = Shop::latest()->get();
        $title = 'الاقسام';
        return view('admin.shops.index', compact('shops', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة قسم';
        return view('admin.shops.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreShopRequest $request)
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
    public function show(Shop $Shop)
    {
        $title = 'عرض قسم';
        return view('admin.shops.show', compact('Shop', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop = Shop::find($id);
        $title = 'تعديل قسم';
        return view('admin.shops.edit', compact('shop', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateShopRequest $request, $id)
    {
      
        $Shop = Shop::find($id);
        $this->UpdateRecords($Shop, $request);
      
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shop $Shop)
    {
        if ($Shop->image) {
            @unlink(public_path('/cdn/'.$Shop->image));
        }
        $Shop->delete();
        return 'success';
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
