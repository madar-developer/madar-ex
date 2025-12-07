<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\CompanyOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Company;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use App\Models\Invoice;
use Carbon\Carbon;


class CompanyController extends Controller
{
    use CompanyOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:company_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:company_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:company_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:company_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {

        if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            //
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
                $companies = Company::whereHas('BranchData', function($q) use( $branch_id ){
                    $q->where('admin_id', $branch_id);
                })->latest();
            } else {
                $companies = Company::latest();

            }

        $search = array();
        if (Request()->has('name') && Request()->get('name') != '') {
            $name = Request()->get('name');
            $search['name'] = $name;
            $companies = $companies->where('name'     , 'LIKE', '%'.$name.'%');
        }
        if (Request()->has('id') && Request()->get('id') != '') {
            $id = Request()->get('id');
            $search['id'] = $id;
            $companies = $companies->where('id'     , $id);
        }
        if (Request()->has('phone') && Request()->get('phone') != '') {
            $phone = Request()->get('phone');
            $search['phone'] = $phone;
            $companies = $companies->where('phone'     , 'LIKE', '%'.$phone.'%');
        }
        $title = 'المتاجر والشركات';

        if (Request()->has('excel') && Request()->get('excel') != '') {
            $companies = $companies->get();
        return Excel::download(new GeneralExport('admin.reports.companies-excel', $companies), 'companies-'.Carbon::now()->toDateString().'.xlsx');
        }
            $companies = $companies->paginate(40);
        return view('admin.companies.index', compact('companies', 'title' ,'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة شركه';
        return view('admin.companies.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompanyRequest $request)
    {

        $this->register($request);
        return redirect('/dashboard/companies')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show(Company $company)
    // {
    //     $title = 'عرض ';
    //     return view('admin.companies.show', compact('company', 'title'));
    // }
    public function show(Company $company)
    {
        $title = 'عرض ';
        $orders  = $company->Order()->paginate(20);
        $addresses  = $company->CompanyAddress()->paginate(20);
        $transfers  = $company->Transfer()->paginate(20);
        $transfers_get  = $company->Transfer()->get();
        $invoices = Invoice::latest()->whereHas('Order', function($q) use($company){
            $q->where('company_id', $company->id);
        })->where('active', '0')->paginate(20);
        $invoices_company_price = Invoice::latest()->whereHas('Order', function($q) use($company){
            $q->where('company_id', $company->id);
        })->where('active', '0')->sum('company_price');
        $invoices_madar_price = Invoice::latest()->whereHas('Order', function($q) use($company){
            $q->where('company_id', $company->id);
        })->where('active', '0')->sum('madar_price');
        return view('admin.companies.show', compact('addresses', 'company', 'title', 'orders', 'invoices', 'transfers', 'invoices_company_price', 'invoices_madar_price', 'transfers_get'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find($id);
        $title = 'تعديل ';
        return view('admin.companies.edit', compact('company', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, $id)
    {

        $company = Company::find($id);
        $this->UpdateRecords($company, $request);

        return redirect('dashboard/companies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        if ($company->image) {
            @unlink(public_path('/cdn/'.$company->image));
        }
        $company->delete();
        return 'success';
    }
    public function files(Request $request, $id)
    {
        $company = Company::find($id);
        if ($request->hasFile('file')) {
            $name = uploadImage($request->file('file'));
            $company->Files()->create(['name' => $name]);
        }
        return back()->with('success', 'data added successfully');;
    }
    public function pricelist(Request $request, $id)
    {
        $company = Company::find($id);
        if ($request->has('prices')) {
            foreach ($request->get('prices') as $key => $value) {
                $company->CompanyCityGroup()->updateOrcreate(['city_group_id' => $key],[ 'delivery_cost' => $value]);
            }
        }
        return back()->with('success', 'data added successfully');;
    }
}
