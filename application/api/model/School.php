<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/19
 * Time: 13:58
 */

namespace app\api\model;


class School extends BaseModel
{
    protected $hidden = ['website','abbreviation','create_time','update_time','delete_time','url'];
    public static function getSchoolByProID($id)
    {
        $school = self::where('provinceId',$id)->select([2734,2677,2707,2757]);
        return $school;
    }
}