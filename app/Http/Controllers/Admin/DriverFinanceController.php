<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateDriverFiananceRequest;
use App\Http\Requests\Admin\StoreDriverFiananceRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\DriverFiananceOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\DriverFianance;
use Auth;

class DriverFinanceController extends Controller
{
    use DriverFiananceOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:city_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:city_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:city_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:city_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $search = [];
        $driver_finances = DriverFianance::latest();
        if (Request()->has('driver_id') && Request()->get('driver_id') != '') {
            $driver_id = Request()->get('driver_id');
            $search['driver_id'] = $driver_id;
            $driver_finances = $driver_finances->where('driver_id'     ,$driver_id);
        }
        $driver_finances = $driver_finances->paginate(40);
        $title = 'حسابات السائقين';
        return view('admin.driver-finances.index', compact('driver_finances', 'title', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.cities.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDriverFiananceRequest $request)
    {
        $this->register($request);

        return redirect('/dashboard/driver-finances')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DriverFianance $driver_finances)
    {
        $title = 'عرض ';
        return view('admin.cities.show', compact('driver_finances', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $driver_finances = DriverFianance::find($id);
        $title = 'تعديل ';
        return view('admin.driver-finances.edit', compact('driver_finances', 'title'));
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

        $driver_finances = DriverFianance::find($id);
        if ($request->has('status') && $request->get('status') == 'collected_by_branch') {
            $driver_finances->status = $request->get('status');
            $driver_finances->save();
        }
        if ($request->has('collected_from_driver') && $request->get('collected_from_driver') == '1') {
            $driver_finances->collected_from_driver = $request->get('collected_from_driver');
            $driver_finances->save();
        }

        return redirect('/dashboard/driver-finances')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DriverFianance $driver_finances)
    {
        if ($driver_finances->image) {
            @unlink(public_path('/cdn/'.$driver_finances->image));
        }
        $driver_finances->delete();
        return 'success';
    }



}
