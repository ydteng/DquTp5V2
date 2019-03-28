<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/3
 * Time: 13:50
 */

namespace app\api\model;


use app\lib\exception\MissException;

class UserAddress extends BaseModel
{
    protected $hidden = ['user_id','real_name','province_id','school_id','create_time','update_time','delete_time'];
    public function province()
    {
        return $this->hasOne('Provinces','id','province_id');
    }

    public function school()
    {
        return $this->hasOne('School','id','school_id');
    }


    public static function getUserAddress($uid){

        $address = self::with('province,school')->where(['user_id'=>$uid])->select();
        if(!$address){
            throw new MissException();
        }
        //$address = $address['0']->visible(['address']);
        $address = $address['0']->hidden(['school.provinceId','school.level','school.city']);
        return $address;
    }


}