<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/19
 * Time: 13:57
 */

namespace app\api\model;


class Provinces extends BaseModel
{
    protected $hidden=['create_time','update_time','delete_time','url'];
    public  static function getProvinces()
    {
        $provinces = self::where(['id' => '15'])->select();
        return $provinces;
    }
}