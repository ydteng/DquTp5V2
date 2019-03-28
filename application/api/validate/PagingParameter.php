<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/16
 * Time: 17:17
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page' => 'require|isPositiveInteger'
    ];
}