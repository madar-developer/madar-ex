<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateContactUsRequest;
use App\Http\Requests\Admin\StoreContactUsRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\ContactUsOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\ContactUs;
use Auth;

class ContactUsController extends Controller
{
    use ContactUsOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:contact_us_show'    , ['except' => 'destroy']);
        // $this->middleware('Permission:contact_us_show'    , ['only' => 'index', 'show']);
        // $this->middleware('Permission:contact_us_add'     , ['only' => 'create', 'store']);
        // $this->middleware('Permission:contact_us_edit'    , ['only' => 'edit', 'update']);
        // $this->middleware('Permission:contact_us_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $title = 'استفسارات الزوار ';
        $contacts = ContactUs::latest()->paginate(40);
        return view('admin.contact-us.index', compact('contacts' , 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $title = 'اضافة قسم';
        // return view('admin.invoices.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreInvoiceRequest $request)
    {
        // $this->register($request);
        // return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ContactUs $contacts)
    {
        // return view('admin.contact-us.show', compact('contacts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $invoice = Invoice::find($id);
        // $title = 'تعديل قسم';
        // return view('admin.invoices.edit', compact('invoice', 'title'));
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
      
        $contactus = ContactUs::find($id);
        $contactus->update(['status' => $request->get('status')]);
        // $this->UpdateRecords($invoice, $request);
      
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if ($invoice->image) {
        //     @unlink(public_path('/cdn/'.$invoice->image));
        // }
        $contact = ContactUs::find($id);
        $contact->delete();
        return 'success';
    }
 
}
