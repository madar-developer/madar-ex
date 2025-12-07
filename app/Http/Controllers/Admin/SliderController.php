<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateSliderRequest;
use App\Http\Requests\Admin\StoreSliderRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\SliderOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Slider;
use Auth;
use Excel;
use App\Exports\GeneralExport;
use sliderbon\sliderbon;

class SliderController extends Controller
{
    use SliderOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:slider_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:slider_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:slider_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:slider_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $title = 'السلايدر';
       
            $sliders = Slider::get();
        return view('admin.sliders.index', compact('sliders', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة ';
        return view('admin.sliders.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSliderRequest $request)
    {
        $this->register($request);
        return redirect('/dashboard/sliders')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        $title = 'عرض ';
        return view('admin.sliders.show', compact('slider', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::find($id);
        $title = 'تعديل ';
        return view('admin.sliders.edit', compact('slider', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSliderRequest $request, $id)
    {
      
        $slider = Slider::find($id);
        $this->UpdateRecords($slider, $request);
      
        return redirect('dashboard/sliders');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        if ($slider->form_image) {
            @unlink(public_path('/cdn/'.$slider->form_image));
        }
        $slider->delete();
        return 'success';
    }
}
