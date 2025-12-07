<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateWorkTimeRequest;
use App\Http\Requests\Admin\StoreWorkTimeRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\WorkTimeOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\WorkTime;
use Auth;
use Excel;
use App\Exports\GeneralExport;


class WorkTimeController extends Controller
{
    use WorkTimeOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:partner_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:partner_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:partner_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:partner_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $title = 'اوقات العمل';
       
            $work_times = WorkTime::get();
        return view('admin.work-times.index', compact('work_times', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.work-times.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkTimeRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/worktimes')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(WorkTime $worktimes)
    {
        $title = 'عرض ';
        return view('admin.work-times.show', compact('worktimes', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $work_times = WorkTime::find($id);
        $title = 'تعديل ';
        return view('admin.work-times.edit', compact('work_times', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkTimeRequest $request, $id)
    {
      
        $work_times = WorkTime::find($id);
        $this->UpdateRecords($work_times, $request);
      
        return redirect('dashboard/worktimes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkTime $worktimes)
    {
        if ($worktimes->form_image) {
            @unlink(public_path('/cdn/'.$worktimes->form_image));
        }
        $worktimes->delete();
        return 'success';
    }
}
