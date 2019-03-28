<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/25
 * Time: 19:24
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code' =>'require|isNotEmpty'
    ];
    protected $message = [

    ];

}