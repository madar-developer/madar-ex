<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{


    public function __construct()
    {
        // $this->middleware('Permission:settings_show'    , ['only' => 'index', 'store']);
    }
     public function index()
    {
        $setting = Setting::pluck('value', 'key');
        // return $setting;
        $title = 'الاعدادات';
        return view('admin.settings.create', compact('setting', 'title'));
    }
    public function reports()
    {
        $setting = Setting::pluck('value', 'key');
        // return $setting;
        $title = 'التقارير';
        return view('admin.settings.reports', compact('setting', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $setting = $request->get('setting');
        // return $setting;
        foreach ($setting as $key => $value) {
            if(Setting::where('key' , $key)->first()){
                Setting::where('key' , $key)->update(['value' => $value]);
            }else{
                $id = Setting::max('id') + 1;
                Setting::create(['key' => $key, 'value' => $value, 'id' => $id]);
            }
            // Setting::updateOrcreate(['key' => $key],['value' => $value]);
        }
        return redirect()->back()->with('success', 'data added successfully');
    }
}
