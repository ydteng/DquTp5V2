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
        'code' => 'require|CheckShortMessageCode',
        'reason' => 'require|isNotEmpty',
        'img_1' => 'file|image|fileSize:10485760',
        'img_2' => 'file|image|fileSize:10485760',
    ];
}