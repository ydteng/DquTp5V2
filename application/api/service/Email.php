<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/4/13
 * Time: 11:12
 */

namespace app\api\service;
use PHPMailer\PHPMailer\PHPMailer;
class Email
{
    public static function send($str,$dataArr,$fileOn,$files = []){
// 实例化PHPMailer核心类
        $mail = new PHPMailer();
// 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        //$mail->SMTPDebug = 1;
// 使用smtp鉴权方式发送邮件
        $mail->isSMTP();
// smtp需要鉴权 这个必须是true
        $mail->SMTPAuth = true;
// 链接qq域名邮箱的服务器地址
        $mail->Host = 'yours';
// 设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';
// 设置ssl连接smtp服务器的远程服务器端口号
        $mail->Port = 465;
// 设置发送的邮件的编码
        $mail->CharSet = 'UTF-8';
// 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = '一校派服务器';
// smtp登录的账号 QQ邮箱即可
        $mail->Username = 'yours';
// smtp登录的密码 使用生成的授权码
        $mail->Password = 'yours';
// 设置发件人邮箱地址 同登录账号
        $mail->From = 'yours';
// 邮件正文是否为html编码 注意此处是一个方法
        $mail->isHTML(true);
// 设置收件人邮箱地址
        $mail->addAddress('yours');
// 添加多个收件人 则多次调用方法即可
        $mail->addAddress('yours');
// 添加该邮件的主题
        $mail->Subject = $str;
// 添加邮件正文
        $mail->Body = self::msg($dataArr);
// 为该邮件添加附件,写死传入两个文件
        if($fileOn){
            foreach ($files as $file)
            $mail->addAttachment($file);
        }
// 发送邮件 返回状态
        $mail->send();
        return true;
    }


    //组合信息的函数
    public static function msg($arr){
        $msg = '';
        foreach ($arr as $key => $value)
        {
            $msg = $msg . '<h2>' . $key . ':' . $value . '</h2>';
        }
        return $msg;
    }

}
