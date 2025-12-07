<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\CategoryOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Category;
use Auth;

class CategoryController extends Controller
{
    use CategoryOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:category_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:category_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:category_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:category_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $all = Category::latest()->get();
        $categories = Category::latest()->get();
        $title = 'الاقسام';
        return view('admin.categories.index', compact('categories', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة قسم';
        return view('admin.categories.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->register($request);
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $title = 'عرض قسم';
        return view('admin.categories.show', compact('Category', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        $title = 'تعديل قسم';
        return view('admin.categories.edit', compact('Category', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // $category = Category::find($id);
        $this->UpdateRecords($category, $request);
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->image) {
            @unlink(public_path('/cdn/'.$category->image));
        }
        $category->delete();
        return 'success';
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
