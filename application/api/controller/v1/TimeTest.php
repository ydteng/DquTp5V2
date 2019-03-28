<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/26
 * Time: 21:02
 */

namespace app\api\controller\v1;


use app\api\model\BannerInfo;
use think\Db;

class TimeTest
{
    public function test(){
        $data=Db::table('order')->where(['id'=>1])->find();
//        $startTime = $data['create_time'];
//        $endTime = date('Y-m-d H:i:s', strtotime ("+2 day", strtotime($startTime)));
        $startTime = '2019-03-02 00:00:00';
        $endTime = '2019-03-03 00:00:00';
        //$time = $endTime - $startTime;
        $date=floor((strtotime($endTime)-strtotime($startTime))/3600);
        //$days = intval($time);
        return $date;
    }
}