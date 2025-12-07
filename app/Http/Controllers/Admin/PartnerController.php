<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdatePartnerRequest;
use App\Http\Requests\Admin\StorePartnerRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\PartnerOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Partner;
use Auth;
use Excel;
use App\Exports\GeneralExport;


class PartnerController extends Controller
{
    use PartnerOperations;
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
        $title = 'العملاء';
       
            $partners = Partner::get();
        return view('admin.partners.index', compact('partners', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.partners.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePartnerRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/partners')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Partner $partner)
    {
        $title = 'عرض ';
        return view('admin.partners.show', compact('partner', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $partner = Partner::find($id);
        $title = 'تعديل ';
        return view('admin.partners.edit', compact('partner', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePartnerRequest $request, $id)
    {
      
        $partner = Partner::find($id);
        $this->UpdateRecords($partner, $request);
      
        return redirect('dashboard/partners');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Partner $partner)
    {
        if ($partner->form_image) {
            @unlink(public_path('/cdn/'.$partner->form_image));
        }
        $partner->delete();
        return 'success';
    }
}
