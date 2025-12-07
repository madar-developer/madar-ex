<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Controllers\Controller;
use App\Traits\Admin\UserOperations;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\Department;
use Auth;
use Validator;
use App\Exports\GeneralExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    use UserOperations;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('Permission:user_show'    , ['only' => 'index', 'show']);
        $this->middleware('Permission:user_add'     , ['only' => 'create', 'store']);
        $this->middleware('Permission:user_edit'    , ['only' => 'edit', 'update']);
        $this->middleware('Permission:user_delete'  , ['only' => 'destroy']);
    }

    public function index() //get
    {
       
            $users = User::latest();


        $search = array();
        if (Request()->has('name') && Request()->get('name') != '') {
            $name = Request()->get('name');
            $search['name'] = $name;
            $users = $users->where('name'     , 'LIKE', '%'.$name.'%');
        }
        if (Request()->has('phone') && Request()->get('phone') != '') {
            $phone = Request()->get('phone');
            $search['phone'] = $phone;
            $users = $users->where('phone'     , 'LIKE', '%'.$phone.'%');
        }
       
        $users = $users->get();
        
        $title = 'المستخدمين';
        //////////////////// branch or admin
        if (auth('admin')->user()->role == 'branch') {
            // 
            $users = User::whereHas('BranchData', function($q){
                $q->where('admin_id', auth('admin')->id());
            })->get();
        } else {
            $users = User::get();
            
        }
        ///////////////////////////
        return view('admin.users.index', compact('users', 'title', 'search'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'اضافة مستخدم';
        return view('admin.users.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        // return $request->all();
        $this->register($request);
        return redirect('/dashboard/users')->with('success', 'data added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $title = 'عرض مستخدم';
        return view('admin.users.show', compact('user', 'title'));
    }
    public function UserInfo($id)
    {
        $user = User::find($id);
        $title = 'عرض مستخدم';
        return view('admin.users.ajax_data', compact('user', 'title'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $title = 'تعديل مستخدم';
        return view('admin.users.edit', compact('user', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // $user = User::find($id);
        $this->UpdateRecords($user, $request);
        return redirect('/dashboard/users')->with('success', 'data added successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->image) {
            @unlink(public_path('/cdn/'.$user->image));
        }
        $user->delete();
        return 'success';
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
    public function storeClient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|max:255',
            'email'         => 'nullable|email|unique:users|max:255',
            'phone'         => 'required|unique:users|max:255',
            // 'password'      => 'required|max:255|min:6',
            'image'         => 'nullable|image',
        ]);
        if ($validator->fails()) {
            $errors = collect($validator->errors());
            $error  = $errors->unique()->first();
            // return $validator->errors();
            return Response()->json([
            'message' => 'error',
            'data' => $error ,
        ]);
        }
        $User = User::create($request->all());
        return Response()->json([
            'message' => 'success',
            'data' => '<option value="'.$User->id.'" selected="">'.$User->name.'</option>',
        ]);
        // return '<option value="'.$User->id.'">'.$User->name.'</option>';
    }
}
