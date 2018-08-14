<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/2
 * Time: 18:14
 */

namespace app\index\model;


use think\Model;

class Article extends Model
{
    protected $hidden = ['delete_time','update_time'];
}