<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/15
 * Time: 19:20
 */

namespace app\api\validate;

use app\api\model\User as UserModel;
use app\api\service\ShortMessage;
use app\lib\exception\MissException;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;
use app\lib\exception\ShortMessageException;
use think\Cache;
use think\Request;
use think\Validate;

/**
 * Class BaseValidate
 * 验证类的基类
 */
class BaseValidate extends Validate
{
    /**
     * 检测所有客户端发来的参数是否符合验证类规则
     * 基类定义了很多自定义验证方法
     * 这些自定义验证方法其实，也可以直接调用
     * @throws ParameterException
     * @return true
     */
    public function goCheck()
    {
        $request = Request::instance();
        $params = $request->param();

        if (!$this->batch()->check($params)) {
            $exception = new ParameterException([
                // $this->error有一个问题，并不是一定返回数组，需要判断
                'msg' => is_array($this->error) ?
                    implode(';', $this->error) : $this->error
            ]);
            throw $exception;
        }
        return true;
    }

    /**
     * @param array $arrays 通常传入request.post变量数组
     * @return array 按照规则key过滤后的变量数组
     * @throws ParameterException
     */
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }

    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) {
            return $field . '不允许为空';
        }
        else{
            return true;
        }
    }

    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    protected function requireCheckShortMessageCode($value, $rule = '', $data = '', $field = ''){
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        $userAddress = $user->address;
        if (!$userAddress && empty($value)){
            return '用户地址不存在情况下,' . $field . '不允许为空';
        }
        else if(!$userAddress){
            $result = ShortMessage::checkCode($uid,$value);
            return $result;
        }
        else if($userAddress){
            $mobile = $userAddress->mobile;
            $newMobile = Request::instance()->param('mobile');

            if (!$mobile) {
                throw new MissException(['msg' => '非法错误，手机号码不存在']);
            }else{
                if ($mobile == $newMobile){
                    return true;
                }else{
                    $result = ShortMessage::checkCode($uid,$value);
                    return $result;
                }
            }
        }

    }

    protected function requireMsgCheck($value, $rule = '', $data = '', $field = ''){
        $uid = TokenService::getCurrentUid();
        $result = ShortMessage::checkCode($uid,$value);
        return $result;
    }

    public function getDataByRule($arrays){
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            if ($key == 'img_1'||$key == 'img_2'){
                continue;
            }
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }
}