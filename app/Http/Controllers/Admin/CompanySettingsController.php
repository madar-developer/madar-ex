<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanySettingsController extends Controller
{


    public function __construct()
    {
        // $this->middleware('Permission:settings_show'    , ['only' => 'index', 'store']);
    }



    public function AdminGet(Request $request)
    {
        return view('company.settings.admins');
    }

    public function AdminEdit(Request $request)
    {
        $admin = auth()->user();
        $title = "تعديل الملف الشخصي";
        return view('company.settings.admins-edit', compact('admin', 'title'));
    }
    public function AdminUpdate(Request $request)
    {
        $data = $request->all();
        $item = auth()->user();

        $this->validate($request, [
            'email' => 'max:255|unique:companies,email,'.$item->id,
            'phone' => 'max:255|unique:companies,phone,'.$item->id,
        ]);
        $data['password'] = bcrypt($data['password']);
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        $item->update($data);
        $com = auth('company')->user();
        if($request->has('notify_url') && $request->notify_url != '')
        {
            $com->notify_url = $request->notify_url;
            $com->save();
        }
        return redirect()->back()->with('success', 'data added');
    }
}
