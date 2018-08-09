<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/2
 * Time: 18:07
 */

namespace app\admin\model;


use think\Model;

class Admin extends Model
{
    protected $hidden = ['delete_time','update_time','create_time'];

    public function adminrole(){
        return $this->hasMany('AdminRole');
    }
}