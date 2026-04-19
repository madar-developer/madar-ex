<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCircularRequest;
use App\Http\Requests\Admin\UpdateCircularRequest;
use App\Models\Circular;

class CircularController extends Controller
{
    public function __construct()
    {
        $this->middleware('Permission:setting_show');
    }

    public function index()
    {
        $circulars = Circular::latest()->get();
        $title = 'التعاميم';

        return view('admin.circulars.index', compact('circulars', 'title'));
    }

    public function create()
    {
        $title = 'إضافة تعميم';

        return view('admin.circulars.add', compact('title'));
    }

    public function store(StoreCircularRequest $request)
    {
        Circular::create($request->validated());

        return redirect('/dashboard/circulars')->with('success', 'تمت الإضافة بنجاح');
    }

    public function show(Circular $circular)
    {
        $title = 'عرض تعميم';

        return view('admin.circulars.show', compact('circular', 'title'));
    }

    public function edit($id)
    {
        $circular = Circular::findOrFail($id);
        $title = 'تعديل تعميم';

        return view('admin.circulars.edit', compact('circular', 'title'));
    }

    public function update(UpdateCircularRequest $request, $id)
    {
        $circular = Circular::findOrFail($id);
        $circular->update($request->validated());

        return redirect('/dashboard/circulars')->with('success', 'تم التعديل بنجاح');
    }

    public function destroy(Circular $circular)
    {
        $circular->delete();

        return 'success';
    }
}
