<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateAvailableMethodRequest;
use App\Http\Requests\Admin\StoreAvailableMethodRequest;
use App\Http\Controllers\Controller;
use App\Models\AvailableMethod;
use App\Traits\Admin\AvailableMethodOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;

class AvailableMethodController extends Controller
{
    use AvailableMethodOperations;
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:avaliable_method_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:avaliable_method_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:avaliable_method_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:avaliable_method_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $all = AvailableMethod::latest()->get();
        $method = AvailableMethod::latest()->get();
        $title = 'وسائل الدفع';
        return view('admin.avaliable-methods.index', compact('method', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.avaliable-methods.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAvailableMethodRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/avaliable-methods')->with('success', 'data added successfully');
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
        return view('admin.avaliable-methods.show', compact('method', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $method = AvailableMethod::find($id);
        $title = 'تعديل ';
        return view('admin.avaliable-methods.edit', compact('method', 'title'));
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
        $method = AvailableMethod::find($id);
        $this->UpdateRecords($method, $request);
      
        return redirect('/dashboard/avaliable-methods')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $method = AvailableMethod::find($id);
        $method->delete();
        return 'success';
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
