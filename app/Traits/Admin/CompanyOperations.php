<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Admin;
use App\Notifications\GeneralNotification;
use DB;

trait CompanyOperations
{


    /**
     * Register a New .
     *
     * @param $request
     * @return \App\
     */
    public function register ($request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        if($request->has('password')){
            $data['password']=  bcrypt($data['password']);
           }
        DB::beginTransaction();
        $admin = Admin::first();
        $Company = Company::create($data);

          ///////////////// branch or admin
          $message = 'تم اضافة شركه  : '.$Company->name;
          if($admin)
          {
              $admin->notify(new GeneralNotification($message, '/dashboard/companies/'.$Company->id ) );
          }
          if (in_array( auth('admin')->user()->role, ['branch', 'employee']) || (auth('admin')->user()->role == 'employee' && auth()->user()->parent_id != '0' )) {
            //
            if (auth('admin')->user()->role == 'branch') {
                $branch_id = auth('admin')->id();
            } else {
                $branch_id = auth('admin')->user()->parent_id;
            }
            $Company->BranchData()->create(['admin_id' => $branch_id]);
        }
        DB::commit();
        return $Company;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Company $Company,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Company->image));
            //
            $data['image'] = uploadFile($request);

        }
        if(isset($data['password']) && !empty($data['password'])){
            $data['password']=  bcrypt($data['password']);
           } else{
                unset($data['password']);
           }
        $Company->update($data);
        return $Company;
    }

    /**
     * delete Record
     * @param $truck
     * @param $request
     */
    public function DeleteRecord($id)
    {
        //
    }
}
