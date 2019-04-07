<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/26
 * Time: 21:02
 */

namespace app\api\controller\v1;


use app\api\model\User;
use app\api\model\Img;
use app\api\model\UserAddress;
use think\Cache;
use think\Db;

class TimeTest
{
    public function test(){
        $user = new Img();
        $user = $user->get(1)->find();
        return $user;
    }
}