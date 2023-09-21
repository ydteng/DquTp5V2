<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/25
 * Time: 19:40
 */

return [
    /*
     * wxde1fda66c02d3eea
     *
     *
     * bac033d11bb02ef997f2196bfba226a9
     *
     * */
    'app_id' => 'yours',
    'app_secret' => 'yours',
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?".
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code"
];
