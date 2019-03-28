<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/15
 * Time: 15:17
 */

namespace app\lib\exception;

use think\Exception;

/**
 * Class BaseException
 * 自定义异常类的基类
 */
class BaseException extends Exception
{
    public $code = 400;
    public $msg = 'invalid parameters';
    public $errorCode = 999;

    /**
     * 构造函数
     */
    public function __construct($params = [])
    {
        if (!is_array($params)) {
            return;
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg'];
        }
        if (array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode'];
        }
    }
}