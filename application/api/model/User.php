<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/25
 * Time: 19:34
 */

namespace app\api\model;


use app\lib\exception\MissException;

class User extends BaseModel
{
    protected $hidden = ['create_time','update_time','delete_time'];
    public function address()
    {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    public function order()
    {
        return $this->hasMany('Order', 'user_id', 'id');
    }

    public static function getByOpenID($openid)
    {
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }

}