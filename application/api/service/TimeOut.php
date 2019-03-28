<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/24
 * Time: 13:10
 */

namespace app\api\service;


class TimeOut
{
    //发单超过24小时无人接取或接取后24小时未正常完成
    public static function orderTimeOut($orders){
        foreach ($orders as $key => $value) {
            $startTime = $orders[$key]->update_time;
            $endTime = date('Y-m-d H:i:s');
            $hour = floor((strtotime($endTime)-strtotime($startTime))/3600);
            $status = $orders[$key]->status;

            if ($hour >= 24){
                if($status == 2000){
                    $orders[$key]->status = 1000;
                    $orders[$key]->save(['status' => 1000]);
                }

                if($status == 4000){
                    $orders[$key]->status = 5001;
                    $orders[$key]->save(['status' => 5001]);
                }

                if($status == 4001){
                    $orders[$key]->status = 5000;
                    $orders[$key]->save(['status' => 5000]);
                }
            }

        }
    }


}