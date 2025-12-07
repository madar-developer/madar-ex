<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCityRequest;
use App\Http\Requests\Admin\StoreCityRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\CityOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\City;
use Auth;

class CityController extends Controller
{
    use CityOperations;
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
        $all = City::latest()->get();
        // foreach ($all as $c) {
        //     $c->update(['name'=> '{"ar":"'.$c->name.'","en":""}']);
        // }
        // return $all;
        $cities = City::latest()->get();
        $title = 'المدن';
        return view('admin.cities.index', compact('cities', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.cities.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCityRequest $request)
    {
        $this->register($request);

        return redirect('/dashboard/cities')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        $title = 'عرض ';
        return view('admin.cities.show', compact('city', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $city = City::find($id);
        $title = 'تعديل ';
        return view('admin.cities.edit', compact('city', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCityRequest $request, $id)
    {

        $city = City::find($id);
        $this->UpdateRecords($city, $request);

        return redirect('/dashboard/cities')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        if ($city->image) {
            @unlink(public_path('/cdn/'.$city->image));
        }
        $city->delete();
        return 'success';
    }

    public function GetStatusByAjex(Request $request){
        $cities = City::where('parent' , $request->city_id)
                  ->pluck('name','id' )->toArray() ;
        return response()->Json($cities) ;
    }

}
