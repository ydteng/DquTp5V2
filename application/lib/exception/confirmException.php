<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/24
 * Time: 22:26
 */

namespace app\lib\exception;


class confirmException extends BaseException
{
    public $code = 400;
    public $msg = '请不要确认已经完成的订单或失效的订单';
    public $errorCode = 10091;
}