<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use FCMGroup;


class FCMController extends Controller
{
    public static function Push($title, $content,$token,$data, $activity = '')
    {
        // $data2 = [
        //     'notification' => [
        //         'click_action' => $activity,
        //         'body' => $content,
        //         'title' => $title,
        //         'icon' => ''
        //     ],
        //     'data' =>$data
        // ];
        try{
            $notificationBuilder = new PayloadNotificationBuilder();
            $notificationBuilder->setTitle($title)
                                ->setBody($content)
                                ->setSound('sound')
                                ->setChannelId('com.madar_al_reyadah.algeri_client')
                                // ->setChannelName('busstecc_driver_channel')
                                ->setClickAction($activity);
                                // ->setBadge('badge');

            $notification = $notificationBuilder->build();
            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData($data);

            $data = $dataBuilder->build();
            $downstreamResponse = FCM::sendTo($token, null, $notification, $data);
            return $downstreamResponse;
        }catch(\Exception $e){
            return true;
        }
    }
}
