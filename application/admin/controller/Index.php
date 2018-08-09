<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/2
 * Time: 14:35
 */

namespace app\admin\controller;

class Index extends BaseController
{

    public function index()
    {
//        var_dump($_SESSION);
        return $this->fetch();
    }

    public function form()
    {
        return $this->fetch();
    }

    public function img_ajax(){
        $file = request()->file('picture');
        if ($file) {
            $filee = $this->imgUpload($file,'uploads/banner');
            if ($filee['error'] == 1) {
                return show(1,'上传成功','http://'.$_SERVER['HTTP_HOST'].'/'.$filee['url']);
            }else {
                return show(0,$filee['errorlog']);
            }
        }else {
            return show(0,'文件上传失败');
        }

    }
}