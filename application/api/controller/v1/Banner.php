<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/15
 * Time: 19:57
 */

namespace app\api\controller\v1;


use app\api\validate\IDMustBePositiveInt;
use app\api\model\BannerInfo as BannerModel;
use app\lib\exception\MissException;

class Banner
{
    /**
     * 获取Banner信息
     * @url     /banner/:id
     * @http    get
     * @param   int $id banner id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getBanner()
    {
        $banner = BannerModel::getBanner();
        if(!$banner){
            throw new MissException();
        }
        return json($banner);
    }
}