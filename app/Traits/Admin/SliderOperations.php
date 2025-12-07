<?php
namespace App\Traits\Admin;

use Illuminate\Http\Request;
use App\Models\Slider;
use DB;

trait SliderOperations
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
        if ($request->has('image') && isset($request->image['en'])) {
            $filename = time() . rand(1000,9999). '.png';
            $request->image['en']->move(public_path('/cdn'), $filename);
            $data['image']['en'] = $filename;
        }
        if ($request->has('image') && isset($request->image['ar'])) {
            $filarame = time() . rand(1000,9999). '.png';
            $request->image['ar']->move(public_path('/cdn'), $filarame);
            $data['image']['ar'] = $filarame;
        }
        DB::beginTransaction();
        $Slider = Slider::create($data);
        DB::commit();
        return $Slider;
    }


    /**
     * Update Record
     * @param $truck
     * @param $request
     */
    public function UpdateRecords(Slider $Slider,$request)
    {
        $data = $request->all();

        if ($request->has('image') && isset($request->image['en'])) {
            @unlink(public_path('/cdn/'.$Slider->getTranslation('title', 'en')));
            $filename = time() . rand(1000,9999). '.png';
            $request->image['en']->move(public_path('/cdn'), $filename);
            $data['image']['en'] = $filename;
        }
        if ($request->has('image') && isset($request->image['ar'])) {
            @unlink(public_path('/cdn/'.$Slider->getTranslation('title', 'ar')));
            $filarame = time() . rand(1000,9999). '.png';
            $request->image['ar']->move(public_path('/cdn'), $filarame);
            $data['image']['ar'] = $filarame;
        }
        $Slider->update($data);
        return $Slider;
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
