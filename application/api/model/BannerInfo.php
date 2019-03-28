<?php
/**
 * Created by PhpStorm.
 * User: TenYoDun
 * Date: 2019/2/19
 * Time: 13:19
 */

namespace app\api\model;


class BannerInfo extends BaseModel
{
    protected $hidden = ['img_id','create_time','update_time','delete_time'];
    public function img()
    {
        return $this->belongsTo('Img','img_id','id');

    }
    public static function getBanner()
    {
        $banner = self::with(['img'])->select();
        return $banner;
    }
}