<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/30
 * Time: 17:58
 */

namespace app\lib\exception;


class PlaceOrderException extends BaseException
{
    public $code = 403;
    public $errorCode = 60010;
    public $msg = "不能重复发布相同的订单";
}