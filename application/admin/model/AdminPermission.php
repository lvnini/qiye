<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/2
 * Time: 18:12
 */

namespace app\admin\model;


use think\Model;

class AdminPermission extends Model
{
    protected $hidden = ['delete_time','update_time','create_time'];
}