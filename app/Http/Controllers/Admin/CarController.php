<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCarRequest;
use App\Http\Requests\Admin\StoreCarRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\CarOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Car;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use Carbon\Carbon;

class CarController extends Controller
{
    use CarOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:car_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:car_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:car_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:car_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $title = 'السيارات';
        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            //
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
                $cars = Car::whereHas('BranchData', function($q) use( $branch_id ){
                    $q->where('admin_id', $branch_id);
                })->get();
            } else {
                $cars = Car::latest();

            }
            $search = array();
            if (Request()->has('id') && Request()->get('id') != '') {
                $id = Request()->get('id');
                $search['id'] = $id;
                $cars = $cars->where('id',$id);
            }
            if (Request()->has('name') && Request()->get('name') != '') {
                $name = Request()->get('name');
                $search['name'] = $name;
                $cars = $cars->where('structure_no'     , 'LIKE', '%'.$name.'%')->orWhere('name'     , 'LIKE', '%'.$name.'%');
            }
            if (Request()->has('excel') && Request()->get('excel') != '') {
                $cars = $cars->get();
                return Excel::download(new GeneralExport('admin.reports.cars-excel', $cars), 'cars-'.Carbon::now()->toDateString().'.xlsx');
            }
            $cars = $cars->paginate(24);
        return view('admin.cars.index', compact('cars', 'title' , 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.cars.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/cars')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        $title = 'تفاصيل السيارة ';
        return view('admin.cars.show', compact('car', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $car = Car::find($id);
        $title = 'تعديل ';
        return view('admin.cars.edit', compact('car', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCarRequest $request, $id)
    {

        $car = Car::find($id);
        $this->UpdateRecords($car, $request);

        return redirect('dashboard/cars');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        if ($car->form_image) {
            @unlink(public_path('/cdn/'.$car->form_image));
        }
        $car->delete();
        return 'success';
    }
}
