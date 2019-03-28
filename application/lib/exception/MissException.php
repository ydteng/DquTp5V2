<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/15
 * Time: 16:27
 */

namespace app\lib\exception;

/**
 * 404时抛出此异常
 */
class MissException extends BaseException
{
    public $code = 404;
    public $msg = '请求的内容不存在';
    public $errorCode = 10001;
}