<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/5/10
 * Time: 10:51
 */

namespace app\api\validate;


class isMobile extends BaseValidate
{
    protected $rule =[
        'mobile' => 'require|isMobile'
    ];

}