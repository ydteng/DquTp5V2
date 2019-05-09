<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/5/9
 * Time: 9:43
 */

namespace app\api\service;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use app\lib\exception\ShortMessageException;
use app\lib\SuccessMessage;
use think\Cache;

class ShortMessage
{
    public static function sendShortMessage($uid){

        $code = self::getRandNum();

        AlibabaCloud::accessKeyClient('LTAIaBNiiBZUDMdE', 'Z8o690upMPj1WqjLj0XlKYJZzY2u07')
            ->regionId('cn-hangzhou')
            ->asGlobalClient();

        try {
            $result = AlibabaCloud::rpcRequest()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'RegionId' => 'cn-hangzhou',
                        'PhoneNumbers' => '15864031612',
                        'SignName' => '一校派',
                        'TemplateCode' => 'SMS_165040044',
                        'TemplateParam' => '{"code":"'.$code.'"}',
                    ],
                ])
                ->request();
            if ($result['Code'] == 'OK'){
                $key = $uid . 'ShortMessage';
                cache($key,['code' => $code],300);
                return new SuccessMessage();
            }
            else{
                throw new ShortMessageException(['msg' => $result['Message']]);
            }
        } catch (ClientException $e) {
            throw new ShortMessageException(['errorCode' => 16020,'msg' => $e->getErrorMessage()]);
        } catch (ServerException $e) {
            throw new ShortMessageException(['errorCode' => 16030,'msg' => $e->getErrorMessage()]);
        }
    }

    public static function getRandNum(){
        $randNum = rand(1111,9999);
        return $randNum;
    }
}