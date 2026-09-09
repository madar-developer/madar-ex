<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRegionAreaRequest;
use App\Http\Requests\Admin\UpdateRegionAreaRequest;
use App\Models\RegionArea;
use App\Services\RegionAreaService;

class RegionAreaController extends Controller
{
    public function __construct()
    {
        $this->middleware('Permission:order_show', ['only' => 'index']);
        $this->middleware('Permission:order_add', ['only' => ['create', 'store']]);
        $this->middleware('Permission:order_edit', ['only' => ['edit', 'update']]);
        $this->middleware('Permission:order_delete', ['only' => 'destroy']);
    }

    public function index()
    {
        $title = 'مناطق الخريطة';
        $areas = RegionArea::latest('id')->get();

        return view('admin.region-areas.index', compact('areas', 'title'));
    }

    public function create()
    {
        $title = 'إضافة منطقة';

        return view('admin.region-areas.add', compact('title'));
    }

    public function store(StoreRegionAreaRequest $request, RegionAreaService $service)
    {
        $area = RegionArea::create([
            'title' => $request->title,
            'code' => $request->code,
            'coordinates' => $request->coordinates,
            'active' => $request->has('active') ? 1 : 0,
        ]);

        $service->syncOrdersForArea($area);

        return redirect('/dashboard/region-areas')->with('success', 'تمت الإضافة بنجاح');
    }

    public function edit($id)
    {
        $area = RegionArea::findOrFail($id);
        $title = 'تعديل منطقة';

        return view('admin.region-areas.edit', compact('area', 'title'));
    }

    public function update(UpdateRegionAreaRequest $request, $id, RegionAreaService $service)
    {
        $area = RegionArea::findOrFail($id);
        $oldCode = $area->code;

        $area->update([
            'title' => $request->title,
            'code' => $request->code,
            'coordinates' => $request->coordinates,
            'active' => $request->has('active') ? 1 : 0,
        ]);

        if ($oldCode !== $area->code) {
            $service->reassignOrdersWithCode($oldCode);
        }
        $service->syncOrdersForArea($area->fresh());

        return redirect('/dashboard/region-areas')->with('success', 'تم التعديل بنجاح');
    }

    public function destroy(RegionArea $region_area, RegionAreaService $service)
    {
        $code = $region_area->code;
        $region_area->delete();
        $service->reassignOrdersWithCode($code);

        return 'success';
    }
}
