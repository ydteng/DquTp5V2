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

            $hour = subTime($orders[$key],'H');
            $status = $orders[$key]->status;

            if ($hour >= 24){
                if($status == 1001){
                    $orders[$key]->status = 1004;
                    $orders[$key]->save(['status' => 1004]);
                }
                if($status == 2000){
                    $orders[$key]->status = 1003;
                    $orders[$key]->save(['status' => 1003]);
                }

                if($status == 3000){
                    $orders[$key]->status = 1002;
                    $orders[$key]->save(['status' => 1002]);
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