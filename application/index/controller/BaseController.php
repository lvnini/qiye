<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/4
 * Time: 14:52
 */

namespace app\index\controller;

use app\index\model\System;
use think\Controller;

class BaseController extends Controller
{
    /**
     *
     *初始化获取基本资料
     *
    **/
    public function _initialize()
    {
        $this->system_data();
    }

    /*
     *获取系统数据
     */
    public function system_data()
    {
        $system = new System();
        $sys_data = $system->select();
        foreach ($sys_data as $key => $value) {
            $systemOnData[$value['keyword']]['name'] = $value['name'];
            $systemOnData[$value['keyword']]['value'] = $value['value'];
        }
        $this->assign('system', $systemOnData);
    }


    /**
     * 上传文件
     * @return {[type] [description]
     */
    public function imgUpload($fileArr, $path){

        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $fileArr->validate(['size'=>1024999,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . $path);
        if($info){
            // 成功上传后 获取上传信息
            $date = array(
                'error' => 1,
                'errorlog' => '上传成功',
                'type' => $info->getExtension(), // 输出 jpg
                'url' => $path.'/'.$info->getSaveName(),  // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                'name' => $info->getFilename()  // 输出 42a79759f284b767dfcb2a0197904287.jpg
            );
            return $date;

        }else{
            // 上传失败获取错误信息
            $date = array(
                'error' => 0,
                'errorlog' => $fileArr->getError()
            );
            return $date;
        }

    }


    /**
     * 上传多文件
     * @return {[type] [description]
     */
    public function imgMulUpload($fileArr, $path){

        // 移动到框架应用根目录/public/uploads/ 目录下
        foreach($fileArr as $file){
            $info = $file->validate(['size'=>1678,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . $path);
            //var_dump($file);
            if($info){
                // 成功上传后 获取上传信息
                $date = array(
                    'error' => 1,
                    'errorlog' => '上传成功',
                    'type' => $info->getExtension(), // 输出 jpg
                    'url' => $info->getSaveName(),  // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                    'name' => $info->getFilename()  // 输出 42a79759f284b767dfcb2a0197904287.jpg
                );
                return $date;

            }else{
                // 上传失败获取错误信息
                $date = array(
                    'error' => 0,
                    'errorlog' => $file->getError()
                );
                return $date;
            }
        }

    }

}