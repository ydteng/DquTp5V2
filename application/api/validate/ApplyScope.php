<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/4/13
 * Time: 15:24
 */

namespace app\api\validate;


class ApplyScope extends BaseValidate
{
    protected $rule = [
        'real_name' => 'require|isNotEmpty',
        'mobile' => 'require|isMobile',
        //'code' => 'requireMsgCheck',
        'reason' => 'require|isNotEmpty',
    ];
}