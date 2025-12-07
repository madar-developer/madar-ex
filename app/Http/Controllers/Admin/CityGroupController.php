<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCityGroupRequest;
use App\Http\Requests\Admin\StoreCityGroupRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\CityGroupOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\CityGroup;
use Auth;

class CityGroupController extends Controller
{
    use CityGroupOperations;
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
        $rows = CityGroup::latest()->get();
        $title = 'المجمعات';
        return view('admin.city-groups.index', compact('rows', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.city-groups.add', compact('title'));
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

        return redirect('/dashboard/city-groups')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $row)
    {
        $title = 'عرض ';
        return view('admin.city-groups.show', compact('row', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = CityGroup::find($id);
        $title = 'تعديل ';
        return view('admin.city-groups.edit', compact('row', 'title'));
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

        $row = CityGroup::find($id);
        $this->UpdateRecords($row, $request);

        return redirect('/dashboard/city-groups')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CityGroup $row)
    {
        if ($row->image) {
            @unlink(public_path('/cdn/'.$row->image));
        }
        $row->delete();
        return 'success';
    }


}
