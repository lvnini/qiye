<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/4
 * Time: 14:52
 */

namespace app\admin\controller;


use app\admin\model\Admin;
use app\admin\model\AdminPermission;
use app\admin\model\AdminRole;
use think\Controller;
use think\Db;

class BaseController extends Controller
{
    /*
   * 初始化操作
   */
    public function _initialize()
    {
        $controllers = request()->controller();
        $actions = request()->action();
        //过滤不需要登陆的页面
        if (in_array($controllers, array('Login', 'logout', 'vertify')) || in_array($actions, array('login', 'Uploadify'))) {

        } else {
//            var_dump(session('adminUser'));
            if (session('adminUser.user_name') != null && session('adminUser.toke') != null && session('adminUser.role_id') != null) {
                $loginse = $this->public_login();//检查账号是否虚假
                if (session('adminUser.role_id') > 0 && $loginse) {
                    //$this->check_priv();//检查管理员菜单操作权限
                    $this->public_menu();//获取权限目录
                } else {
                    $this->error('请先登陆', url('Login/login'), 1);
                }
            } else {
                $this->error('请先登陆', url('Login/login'), 1);
            }

        }
    }

    /*
     *判断登录账号是否虚假
     */
    public function public_login()
    {
        if (session('adminUser.role_id') != null && session('adminUser.user_name') != null && session('adminUser.toke') != null) {
            $admin = new Admin();
            // 查询单个数据
            $right = $admin->where("user_name", session('adminUser.user_name'))->find();
            if ($right != null && $right['password'] == session('adminUser.toke')) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 获取权限目录
     * @return {[type] [description]
     */
    public function public_menu()
    {
        $Admin = new Admin();
        $AdminRole = new AdminRole();
        $AdminPermission = new AdminPermission();

        $admin_username = session('adminUser.user_name');
        $admin_role_id = $Admin->where('user_name', $admin_username)->value('role_id');
        $admin_act_list = $AdminRole->where('role_id', $admin_role_id)->value('act_list');

        if ($admin_act_list != 'root') {
            $admin_act_list_2 = explode(',', $admin_act_list);
        }

        $admin_act_list_1 = Db::name('AdminPermission')->where('grade', 1)->select();

        foreach ($admin_act_list_1 as $key => $value) {
            if ($admin_act_list != 'root') {
                $value['url'] = $AdminPermission->where('module_id', $value['permission_id'])->column('permission_id');
                $intersection = array_intersect($value['url'], $admin_act_list_2);
                if ($intersection) {
                    $admin_url_list_1[] = $value;
                }

            } else {
                $admin_url_list_1 = $admin_act_list_1;
            }

        }

        if ($admin_act_list != 'root') {
            foreach ($admin_act_list_2 as $_key => $_value) {
                $admin_url_list_2[$_key] = Db::name('AdminPermission')->where('permission_id', $_value)->find();
            }
        } else {
            $admin_url_list_2 = array();
            foreach ($admin_act_list_1 as $key => $value) {
                $admin_url_list_2 = Db::name('AdminPermission')->where('grade', '2')->select();
            }
        }

        $controllers = request()->controller();

        $this->assign('admin_url', $controllers);
        $this->assign('admin_url_list_1', $admin_url_list_1);
        $this->assign('admin_url_list_2', $admin_url_list_2);
    }


    /**
     * 上传文件
     * @return {[type] [description]
     */
    public function imgUpload($fileArr, $path){

        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $fileArr->validate(['size'=>1024999,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . $path);
//        var_dump($fileArr);
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