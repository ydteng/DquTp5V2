<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/11
 * Time: 10:07
 */

namespace app\api\validate;

class OrderPlace extends BaseValidate
{
    protected $rule = [
        'cost' => 'require|isNotEmpty',
        'start_point' => 'require|isNotEmpty',
        'item_type' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];
}