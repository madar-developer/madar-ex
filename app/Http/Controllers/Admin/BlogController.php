<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateBlogRequest;
use App\Http\Requests\Admin\StoreBlogRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\BlogOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Blog;
use Auth;

class BlogController extends Controller
{
    use BlogOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:blog_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:blog_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:blog_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:blog_delete'  , ['only' => 'destroy']);
    }
    public function index()
    {
        $all = Blog::latest()->get();
        $blogs = Blog::latest()->get();
        $title = 'الاقسام';
        return view('admin.blogs.index', compact('blogs', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة قسم';
        return view('admin.blogs.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request)
    {
       
        $this->register($request);
        
        // Carbon::parse(date);
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        $title = 'عرض قسم';
        return view('admin.blogs.show', compact('Blog', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::find($id);
        $title = 'تعديل قسم';
        return view('admin.blogs.edit', compact('blog', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBlogRequest $request, $id)
    {
      
        $blog = Blog::find($id);
        $this->UpdateRecords($blog, $request);
      
        return redirect()->back()->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        if ($blog->image) {
            @unlink(public_path('/cdn/'.$blog->image));
        }
        $blog->delete();
        return 'success';
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
