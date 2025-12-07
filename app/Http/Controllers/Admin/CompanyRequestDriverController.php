<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\Admin\RequestDriverOperations;
use Illuminate\Http\Request;
use App\Models\RequestDriver;

class CompanyRequestDriverController extends Controller
{
    use RequestDriverOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        // $this->middleware('Permission:company_request_driver_show'    , ['only' => 'index', 'show']);
        // $this->middleware('Permission:company_request_driver_add'     , ['only' => 'create', 'store']);
        // $this->middleware('Permission:company_request_driver_edit'    , ['only' => 'edit', 'update']);
        // $this->middleware('Permission:company_request_driver_delete'  , ['only' => 'destroy']);
    }
    public function index(Request $request)
    {
        $company_id = auth('company')->id();
        $request_drivers =  RequestDriver::where('company_id', $company_id)->latest();
        $search = array();

        if (Request()->has('s') && Request()->get('s') != '') {
            $s = Request()->get('s');
            $search['s'] = $s;
            $request_drivers = $request_drivers->where('name' , 'LIKE' ,"%$s%");
        }
        if ($request->has('date') && $request->get('date') != '') {
            $date = Carbon::parse($request->get('date') );
            $search['date'] = $request->get('date');
            $request_drivers = $request_drivers->whereDate('pickup_date',  $date);
        }
        $request_drivers = $request_drivers->paginate(40);
        $title = 'طلب مندوبين';
        return view('admin.request-drivers.index', compact('request_drivers', 'title', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافه بيك اب جديد';
        return view('admin.request-drivers.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->register($request);
        return redirect(route('request-drivers.index'))->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RequestDriver $request_driver)
    {
        $title = 'عرض ';
        return view('admin.request-drivers.show', compact('request_driver', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request_driver = RequestDriver::find($id);
        $title = 'تعديل ';
        return view('admin.request-drivers.edit', compact('request_driver', 'title'));
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

        $request_driver = RequestDriver::find($id);
        $this->UpdateRecords($request_driver, $request);

        return redirect(route('request-drivers.index'))->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $request_driver = RequestDriver::find($id);
        $request_driver->delete();
        return 'success';
    }
}
