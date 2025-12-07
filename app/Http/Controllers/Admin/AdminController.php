<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateAdminRequest;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\AdminOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;
use Excel;

class AdminController extends Controller
{
    use AdminOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:admin_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:admin_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:admin_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:admin_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        // $all = Admin::latest()->get();
        $admins = Admin::get();
        $title = ' الادارة';
        return view('admin.admins.index', compact('admins', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.admins.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/admins')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        $title = 'عرض ';
        return view('admin.admins.show', compact('admin', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = Admin::find($id);
        $title = 'تعديل ';
        return view('admin.admins.edit', compact('admin', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminRequest $request, $id)
    {

        $admin = Admin::find($id);
        $this->UpdateRecords($admin, $request);

        return redirect('dashboard/admins');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        if ($admin->image) {
            @unlink(public_path('/cdn/'.$admin->image));
        }
        $admin->delete();
        return 'success';
    }
    public function notiCount()
    {
        return Admin::first()->unreadnotifications->count();
    }
}
