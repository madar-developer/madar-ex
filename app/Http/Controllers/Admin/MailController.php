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
        $this->middleware('Permission:mail_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:mail_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:mail_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:mail_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
       
        $contacts = ContactUs::get();
        return view('admin.mails.reminder', compact('contacts'));
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
    public function update(UpdateInvoiceRequest $request, $id)
    {
      
        // $invoice = Invoice::find($id);
        // $this->UpdateRecords($invoice, $request);
      
        // return redirect()->back()->with('success', 'data added successfully');
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
