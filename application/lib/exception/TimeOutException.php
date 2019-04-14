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
    public $code = 403;
    public $errorCode = 70010;
    public $msg = "不能操作超过24小时的订单";
}