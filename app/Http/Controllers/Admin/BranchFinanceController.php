<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateBranchFinanceRequest;
use App\Http\Requests\Admin\StoreBranchFinanceRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\BranchFinanceOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Fianance;
use Auth;

class BranchFinanceController extends Controller
{
    use BranchFinanceOperations;
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
        $branch_finances = Fianance::get();
        $title = 'حسابات الفروع';
        return view('admin.branch-finances.index', compact('branch_finances', 'title'));
    }
    public function RTM()
    {
        $branches = Admin::where('role','branch')->get();
        $title = 'مستحق من الفروع';
        return view('admin.branch-finances.r-t-m', compact('branches', 'title'));
    }
    public function RTMPost(Request $request)
    {
        $branch = Admin::findOrfail($request->branch_id);
        $branch->Fianance()->create([
            'branch_id' => $branch->id,
            'added_by_id' => auth('admin')->id(),
            'verified_by_id' => auth('admin')->id(),
            'amount'  => $branch->DriverFianance()->where('status', 'collected_by_branch')->sum('net_profit'),
            'in_out'  => 'in',
            'type'  => 'collect_money',
            'verified' => 1,
            ]);
            $branch->DriverFianance()->where('status', 'collected_by_branch')->update(['verified'=> 1]);
        return back()->with('success', 'data added successfully');
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
    public function store(StoreBranchFiananceRequest $request)
    {
        $this->register($request);
      
        return redirect('/dashboard/branch-finances')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BranchFianance $branch_finances)
    {
        $title = 'عرض ';
        return view('admin.cities.show', compact('branch_finances', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch_finances = BranchFianance::find($id);
        $title = 'تعديل ';
        return view('admin.cities.edit', compact('branch_finances', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBranchFiananceRequest $request, $id)
    {
      
        $branch_finances = BranchFianance::find($id);
        $this->UpdateRecords($branch_finances, $request);
      
        return redirect('/dashboard/cities')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchFianance $branch_finances)
    {
        if ($branch_finances->image) {
            @unlink(public_path('/cdn/'.$branch_finances->image));
        }
        $branch_finances->delete();
        return 'success';
    }

   
    
}
