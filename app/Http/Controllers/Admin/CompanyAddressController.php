<?php

namespace App\Http\Controllers\Admin;

// use App\Http\Requests\Admin\UpdateCompanyAddressRequest;
// use App\Http\Requests\Admin\StoreCompanyAddressRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\CompanyAddressOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\CompanyAddress;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use Carbon\Carbon;


class CompanyAddressController extends Controller
{
    use CompanyAddressOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public $route = route();
    public function __construct()
    {
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
        return view('admin.company-adress.add', compact('title', 'company_id'))->render();;
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
    public function show(CompanyAddress $row)
    {
        $title = 'عرض ';
        return view('admin.company-adress.show', compact('row', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = CompanyAddress::find($id);
        $title = 'تعديل ';
        return view('admin.company-adress.edit', compact('row', 'title'));
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

        $row = CompanyAddress::find($id);
        $this->UpdateRecords($row, $request);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyAddress $row)
    {
        $row->delete();
        return 'success';
    }
}
