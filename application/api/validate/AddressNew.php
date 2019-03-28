<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/3
 * Time: 13:22
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    // 为防止欺骗重写user_id外键
    // rule中严禁使用user_id
    // 获取post参数时过滤掉user_id
    // 所有数据库和user关联的外键统一使用user_id，而不要使用uid
    protected $rule = [
        'nickname' => 'require|isNotEmpty',
        'real_name' => 'require|isNotEmpty',
        'mobile' => 'require|isMobile',
        'province_id' => 'require|isNotEmpty',
        'school_id' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];
}