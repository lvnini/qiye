<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/2
 * Time: 18:13
 */

namespace app\admin\model;


use think\Model;

class AdminRole extends Model
{
    protected $hidden = ['delete_time','update_time','create_time'];
}