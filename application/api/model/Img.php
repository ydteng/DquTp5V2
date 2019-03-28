<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/19
 * Time: 13:19
 */

namespace app\api\model;


class Img extends BaseModel
{
    protected $hidden = ['id','from','create_time','update_time','delete_time'];
    public function getUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }
}