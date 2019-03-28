<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/15
 * Time: 19:50
 */

namespace app\api\validate;


class IDMustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger'
    ];
}