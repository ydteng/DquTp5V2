<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/4/13
 * Time: 11:03
 */

namespace app\api\model;


class PackerInfo extends BaseModel
{
    protected $hidden = ['id','user_id','status','create_time','update_time','delete_time'];
}