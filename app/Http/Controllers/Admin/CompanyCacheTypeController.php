<?php

namespace App\Http\Controllers\Admin;

// use App\Http\Requests\Admin\UpdateCompanyCacheTypeRequest;
// use App\Http\Requests\Admin\StoreCompanyCacheTypeRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\CompanyCacheTypeOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\CompanyCacheType;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use Carbon\Carbon;


class CompanyCacheTypeController extends Controller
{
    use CompanyCacheTypeOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public $route = route();
    public function __construct()
    {
        $this->middleware('Permission:company_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:company_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:company_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:company_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة عنوان شركه';
        $company_id = Request()->get('company_id');
        return view('admin.company-cache-types.add', compact('title', 'company_id'))->render();;
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
        return back()->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyCacheType $row)
    {
        $title = 'عرض ';
        return view('admin.company-cache-types.show', compact('row', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = CompanyCacheType::find($id);
        $title = 'تعديل ';
        return view('admin.company-cache-types.edit', compact('row', 'title'));
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

        $row = CompanyCacheType::find($id);
        $this->UpdateRecords($row, $request);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $row = CompanyCacheType::find($id);
        $row->delete();
        return 'success';
    }
}
