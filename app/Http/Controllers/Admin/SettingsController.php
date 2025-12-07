<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PriceList;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\City;
use App\Models\CarType;
use App\Models\CarCarrierType;
use App\Models\CarMaker;
use App\Models\Models;
use App\Models\Color;
use App\Models\Year;
use App\Models\Setting;
use App\Models\Mission;
use App\Models\Category;

class SettingsController extends Controller
{


    public function __construct()
    {
        // $this->middleware('Permission:settings_show'    , ['only' => 'index', 'store']);
    }
    

    public function PermissionGet(Request $request)
    {
        $admin_roles = UserRole::latest()->get();
        return view('admin.settings.permissions', compact('admin_roles'));
    }
    public function PermissionPost(Request $request)
    {
        $data = $request->all();
        $role = Role::create([ 'name' => $data['role']]);
        unset($data['_token']);
        unset($data['role']);
        foreach ($data as $key => $value) {
            Permission::create([ 'role_id' => $role->id, 'permission' => $key]);
        }
        
        return redirect()->back()->with('success', 'data added');
    }
    public function PermissionUpdate(Request $request, $id)
    {
        $data = $request->all();
        $role = Role::findOrfail($id);
        $role->update([ 'name' => $data['role']]);
        $role->Permission()->delete();
        unset($data['_token']);
        unset($data['role']);
        foreach ($data as $key => $value) {
            Permission::create([ 'role_id' => $role->id, 'permission' => $key]);
        }
        return redirect()->back()->with('success', 'data added');
    }
    public function PermissionDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = Role::findOrfail($id);
        $item->Permission()->delete();
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }
    public function PermissionDeleteUser(Request $request, $id)
    {
        $data = $request->all();
        $item = UserRole::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }
    public function UserRole(Request $request)
    {
        $data = $request->all();
        if ( UserRole::where('admin_id', $data['admin_id'])->first() ) {
            UserRole::where('admin_id', $data['admin_id'])->update(['role_id' => $data['role_id'] ]);
        }else{

            UserRole::create($data);
        }
        return redirect()->back()->with('success', 'data added');
    }



    public function AdminGet(Request $request)
    {
        return view('admin.settings.admins');
    }

    public function AdminEdit(Request $request, $id)
    {
        $admin = Admin::find($id);
        return view('admin.settings.admins-edit', compact('admin'));
    }
    public function AdminPost(Request $request)
    {
        $data = $request->all();
        $this->validate($request, [
            'email' => 'unique:admins|max:255',
            'phone' => 'unique:admins|max:255',
        ]);
        $data['password'] = bcrypt($data['password']);
        Admin::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function AdminUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = Admin::findOrfail($id);
        $this->validate($request, [
            'email' => 'max:255|unique:admins,email,'.$item->id,
            'phone' => 'max:255|unique:admins,phone,'.$item->id,
        ]);
        $data['password'] = bcrypt($data['password']);
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function AdminDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = Admin::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }


    public function RegionGet(Request $request)
    {
        return view('admin.settings.regions');
    }
    public function RegionPost(Request $request)
    {
        $data = (array)$request->except('_token');
        City::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function RegionUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = City::findOrfail($id);
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function RegionDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = City::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }


    public function CarTypeGet(Request $request)
    {
        return view('admin.settings.car_types');
    }
    public function CarTypePost(Request $request)
    {
        $data = (array)$request->except('_token');
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'));
        }
        CarType::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function CarTypeUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = CarType::findOrfail($id);
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'));
        }
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function CarTypeDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = CarType::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }

    public function CarCarrierTypeGet(Request $request)
    {
        return view('admin.settings.car_carrier_types');
    }
    public function CarCarrierTypePost(Request $request)
    {
        $data = (array)$request->except('_token');
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'));
        }
        CarCarrierType::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function CarCarrierTypeUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = CarCarrierType::findOrfail($id);
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'));
        }
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function CarCarrierTypeDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = CarCarrierType::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }

    public function CarMakerGet(Request $request)
    {
        return view('admin.settings.car_makers');
    }
    public function CarMakerPost(Request $request)
    {
        $data = (array)$request->except('_token');
        CarMaker::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function CarMakerUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = CarMaker::findOrfail($id);
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function CarMakerDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = CarMaker::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }
// **************************************
    public function ModelsGet(Request $request)
    {
        return view('admin.settings.models');
    }
    public function ModelsPost(Request $request)
    {
        $data = (array)$request->except('_token');
        Models::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function ModelsUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = Models::findOrfail($id);
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function ModelsDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = Models::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }
    // ******************************************************************
    public function MissionsGet(Request $request)
    {
        return view('admin.settings.missions');
    }
    public function MissionsPost(Request $request)
    {
        $data = (array)$request->except('_token');
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'));
        }
        Mission::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function MissionsUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = Mission::findOrfail($id);
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'));
        }
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function MissionsDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = Mission::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }
    // ********************************************************************
    public function CategoriesGet(Request $request)
    {
        return view('admin.settings.categories');
    }
    public function CategoriesPost(Request $request)
    {
        $data = (array)$request->except('_token');
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'));
        }
        Category::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function CategoriesUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = Category::findOrfail($id);
        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->file('image'));
        }
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function CategoriesDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = Category::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }

    // ****************************************************




    public function PriceListGet(Request $request)
    {
        return view('admin.settings.price_lists');
    }
    public function PriceListPost(Request $request)
    {
        $data = $request->all();
        PriceList::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function PriceListUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = PriceList::findOrfail($id);
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function PriceListDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = EducationalLevel::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }



    public function YearGet(Request $request)
    {
        return view('admin.settings.years');
    }
    public function YearPost(Request $request)
    {
        $data = $request->all();
        Year::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function YearUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = Year::findOrfail($id);
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function YearDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = Year::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }



    public function ColorGet(Request $request)
    {
        return view('admin.settings.colors');
    }
    public function ColorPost(Request $request)
    {
        $data = $request->all();
        Color::create($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function ColorUpdate(Request $request, $id)
    {
        $data = $request->all();
        $item = Color::findOrfail($id);
        $item->update($data);
        return redirect()->back()->with('success', 'data added');
    }
    public function ColorDelete(Request $request, $id)
    {
        $data = $request->all();
        $item = Color::findOrfail($id);
        $item->delete();
        return redirect()->back()->with('success', 'data deleted');
    }


}
