<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/3/30
 * Time: 16:43
 */

namespace app\lib\exception;


class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = '成功';
    public $errorCode = 0;
}