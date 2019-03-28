<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/24
 * Time: 18:22
 */

namespace app\lib\exception;


class pickException extends BaseException
{
    public $code = 400;
    public $errorCode = 10099;
    public $msg = "不能接取自己的订单";
}