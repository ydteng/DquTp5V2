<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/26
 * Time: 18:09
 */

namespace app\lib\exception;


class TimeOutException extends BaseException
{
    public $code = 401;
    public $errorCode = 10088;
    public $msg = "不能取消超过24小时的订单";
}