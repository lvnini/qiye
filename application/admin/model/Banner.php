<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/2
 * Time: 18:15
 */

namespace app\admin\model;


use think\Model;

class Banner extends Model
{
    protected $hidden = ['delete_time','update_time','create_time'];

    public function items()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }
}