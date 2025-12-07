<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCarMaintenanceRequest;
use App\Http\Requests\Admin\StoreCarMaintenanceRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\CarMaintenanceOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\CarMaintenance;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use Carbon\Carbon;

class CarMaintenanceController extends Controller
{
    use CarMaintenanceOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:car_maintenance_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:car_maintenance_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:car_maintenance_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:car_maintenance_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        // $all = Car::latest()->get();
        $title = 'ًصيانه السيارات';
          //////////////////// branch or admin
          if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            //
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
            $carmaintaince = CarMaintenance::whereHas('BranchData', function($q) use( $branch_id ){
                $q->where('admin_id', $branch_id);
            })->latest();
        } else {
            $carmaintaince = CarMaintenance::latest();

            }
            $search = array();
            if (Request()->has('car_id') && Request()->get('car_id') != '') {
                $car_id = Request()->get('car_id');
                $search['car_id'] = $car_id;
                $carmaintaince = $carmaintaince->where('car_id'     ,$car_id);
            }
            if (Request()->has('type') && Request()->get('type') != '') {
                $type = Request()->get('type');
                $search['type'] = $type;
                $carmaintaince = $carmaintaince->where('type'     ,$type);
            }
            if (Request()->has('excel') && Request()->get('excel') != '') {
                $carmaintaince = $carmaintaince->get();
                return Excel::download(new GeneralExport('admin.reports.carmaintainance-excel', $carmaintaince), 'carmaintaince-'.Carbon::now()->toDateString().'.xlsx');
            }
            $carmaintaince = $carmaintaince->paginate(40);
        return view('admin.carmaintaince.index', compact('carmaintaince', 'title','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.carmaintaince.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarMaintenanceRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/carmaintaince')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CarMaintenance $carmaintaince)
    {
        $title = 'عرض ';
        return view('admin.carmaintaince.show', compact('carmaintaince', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $carmaintaince = CarMaintenance::find($id);
        $title = 'تعديل ';
        return view('admin.carmaintaince.edit', compact('carmaintaince', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarMaintenanceRequest $request, $id)
    {

        $carmaintaince = CarMaintenance::find($id);
        $this->UpdateRecords($carmaintaince, $request);

        return redirect('dashboard/carmaintaince');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarMaintenance $carmaintaince)
    {
        if ($carmaintaince->image) {
            @unlink(public_path('/cdn/'.$carmaintaince->image));
        }
        $carmaintaince->delete();
        return 'success';
    }
}
