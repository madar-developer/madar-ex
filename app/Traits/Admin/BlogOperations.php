<?php
namespace App\Traits\Admin;

use App\Http\Controllers\Api\FCMController;
use Illuminate\Http\Request;
use App\Models\DeliveryBlog;
use App\Models\Blog;
use Carbon\Carbon;
use Mail;
use DB;

trait BlogOperations
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
        // $api_token      = str_random(60);
        // while ( Blog::where('api_token',$api_token)->count() > 0 ) {                    
        //     $$api_token = str_random(60);
        // }
        // $data['api_token'] = $api_token;
        if ($request->hasFile('image')) {
            $data['image'] = uploadFile($request);
        }
        if ($request->has('from')) {
            $data['from'] = Carbon::parse($request->get('from'));
        }
        if ($request->has('to_me')) {
            $data['to_me'] = Carbon::parse($request->get('to_me'));
        }
        DB::beginTransaction();
        $Blog = Blog::create($data);
        DB::commit();
        return $Blog;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Blog $Blog,$request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            @unlink(public_path('/cdn/'.$Blog->image));
            // 
            $data['image'] = uploadFile($request);
        }
        if ($request->has('password') && $data['password'] != '') {
            $data['password'] = bcrypt($data['password']);
        }else{
            unset($data['password']);
        }
        // if ($request->hasFile('license_image')) {
        //     $data['license_image'] = uploadImage($request->file('license_image'));
        // }
        // if ($request->hasFile('identity_image')) {
        //     $data['identity_image'] = uploadImage($request->file('identity_image'));
        // }
        if ($request->hasFile('form_image')) {
            $data['form_image'] = uploadImage($request->file('form_image'));
        }
        // Blog info
        $data2 = $request->only(['car_type_id', 'car_model_id', 'car_year_id', 'car_color_id', 'car_plate_no', 'car_chasis', 'car_form_image', 'owner_name', 'owner_phone', 'owner_identity_no', 'identity_image', 'family_identity_image', 'license_image' ]);
        if ($request->hasFile('car_form_image')) {
            $data2['car_form_image'] = uploadImage($request->file('car_form_image'));
        }
        if ($request->hasFile('identity_image')) {
            $data2['identity_image'] = uploadImage($request->file('identity_image'));
        }
        if ($request->hasFile('family_identity_image')) {
            $data2['family_identity_image'] = uploadImage($request->file('family_identity_image'));
        }
        if ($request->hasFile('license_image')) {
            $data2['license_image'] = uploadImage($request->file('license_image'));
        }
        if ($Blog->BlogInfo()->first()) {
            $Blog->BlogInfo()->update($data2);
        } else {
            $Blog->BlogInfo()->create($data2);
        }
        // notifications start
        $old_reviewed = $Blog->reviewed;
        if ($old_reviewed != '1' && $request->get('reviewed') == '1') {
                if( $Blog->lang == 'ar')
                {
                    $title = "تم الموافقة علي الحساب الخاص بك";
                    $content = "تم الموافقة علي الحساب الخاص بك";

                }else{
                    $title = "Your Account has Been Reviewed";
                    $content = "Your Account has Been Reviewed";
                }
                $type = "account_reviewed";
                
                $title_ar = "تم الموافقة علي الحساب الخاص بك";
                $title_en = "Your Account has Been Reviewed";
                $content_ar = "تم الموافقة علي الحساب الخاص بك" ;
                $content_en = "Your Account has Been Reviewed";
                $activity = "account_reviewed";
                $data2 = [
                    'title_ar' => $title_ar,
                    'title_en' => $title_en,
                    'content_ar' => $content_ar,
                    'content_en' => $content_en,
                    'type' => $type,
                ];
                $notifiable = $Blog->first();
                $token = $notifiable->PlayerId()->pluck('player_id')->toArray();
                FCMController::Push($title, $content,$token,$data2);
        }
        // notifications end
        $Blog->update($data);
        return $Blog;
    }

    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function StorOrUpdateCost(Blog $Blog,$request)
    {
        $data = $request->all();
        DeliveryBlog::updateOrcreate(['Blog_id' => $Blog->id], $data);
        return $Blog;
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