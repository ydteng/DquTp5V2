<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/26
 * Time: 20:30
 */

namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效的Token或未传入Token';
    public $errorCode = 10001;
}