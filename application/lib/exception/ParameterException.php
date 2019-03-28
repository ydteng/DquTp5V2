<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/15
 * Time: 19:33
 */

namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $errorCode = 10000;
    public $msg = "参数为空";
}