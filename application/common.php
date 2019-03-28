<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}



function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}



function fromArrayToModel($m , $array)
{
    foreach ($array as $key => $value)
    {
        $m[$key] = $value;
    }
    return $m;
}

//hidden 对象数组的操作

function myHidden($objectArray = [],$field=[])
{
    foreach ($objectArray as $key => $value) {
        $objectArray[$key] = $value->hidden($field);
    }
}

function myVisible($objectArray = [],$field=[])
{
    foreach ($objectArray as $key => $value) {
        $objectArray[$key] = $value->visible($field);
    }
}

//计算时间差
function subTime($order,$time){
    /**
     * @order是订单
     * @time是选择获取的时间差，是小时数，天数还是小时数
    */

    $startTime = $order->update_time;
    $endTime = date('Y-m-d H:i:s');

    $H = 3600;
    $D = 86400;

    if ($time == 'H'){
        $hour = floor((strtotime($endTime)-strtotime($startTime))/$H);
        return $hour;
    }
    else if($time == 'D'){
        $hour = floor((strtotime($endTime)-strtotime($startTime))/$D);
        return $hour;
    }

}