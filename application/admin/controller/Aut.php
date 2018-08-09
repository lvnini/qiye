<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/4
 * Time: 15:49
 */

namespace app\admin\controller;


use app\admin\model\Admin;
use app\admin\model\AdminPermission;
use app\admin\model\AdminRole;
use think\Db;
use think\Validate;

class Aut extends BaseController
{

    /**
     * 后台目录管理页列表
     * @return {[type] [description]
     */
    public function catalog_list(){
        //一级目录分类
        $result = Db::name('AdminPermission')->where('grade', 1)->select();
        $this->assign('result', $result);
        //二级目录分类
        $resulttow = Db::name('AdminPermission')->where('grade', 2)->select();
        $this->assign('resulttow', $resulttow);

        return $this->fetch();
    }

    //后台目录管理页新增
    public function catalog_add(){
        $result = Db::name('AdminPermission')->where('grade', 1)->select();
        //dump($result);
        $this->assign('result', $result);
        return $this->fetch();
    }

    //ajax
    public function catalog_add_ajax(){
        //var_dump($_POST);
        if ($_POST['module_id'] == 0) {
            $_POST['grade'] = 1;
        }else {
            $_POST['grade'] = 2;
        }
        $AdminPermission = new AdminPermission($_POST);
        $result = $AdminPermission->allowField(true)->save();
        if ($result) {
            return show(1,'添加成功');
        }else {
            return show(0,'添加失败');
        }
    }

    //后台目录管理页修改
    public function catalog_edit(){
        $result = Db::name('AdminPermission')->where('grade', 1)->select();
        //dump($result);
        $this->assign('result', $result);

        $date = Db::name('AdminPermission')->where('permission_id', $_GET['permission_id'])->find();
        //dump($date);
        $this->assign('date', $date);
        return $this->fetch();
    }

    //后台目录管理页修改ajax
    public function catalog_edit_ajax(){
        if ($_POST['module_id'] == 0) {
            $_POST['grade'] = 1;
        }else {
            $_POST['grade'] = 2;
        }
        $permission_id = $_POST['permission_id'];
        unset($_POST['permission_id']);
        $AdminPermission = new AdminPermission;
        $result = $AdminPermission->allowField(true)->save($_POST,['permission_id' => $permission_id]);
        if ($result) {
            return show(1,'修改成功');
        }else {
            return show(0,'修改失败');
        }
    }

    //后台目录管理页删除
    public function catalog_del(){

        $result = Db::name('AdminPermission')->where('module_id', $_GET['permission_id'])->find();
        if (!$result) {
            $date = Db::name('AdminPermission')->where('permission_id', $_GET['permission_id'])->delete();
            if ($date) {
                $this->success('删除成功', 'Aut/catalog_list');
            }else {
                $this->error('删除失败','Aut/catalog_list');
            }
        }else {
            $this->error('有下级目录不能删除','Aut/catalog_list');
        }

    }

    /**
     * 后台账户管理页列表
     * @return {[type] [description]
     */
    public function admin_list(){
        //账户
        $Admin = new Admin();
        $result = $Admin->select();
//        var_dump($result);
        foreach ($result as $key => $value) {
            $result[$key]['role_name'] = $Admin->adminrole()->where('role_id',$value['role_id'])->value('role_name');
        }
        $this->assign('result', $result);
        return $this->fetch();
    }

    //后台账号新增
    public function admin_add(){
        //角色加载
        $result = Db::name('AdminRole')->select();
        //var_dump($result);
        $this->assign('result', $result);
        return $this->fetch();
    }

    //后台账号新增ajax
    public function admin_add_ajax(){
        if (!$_POST['user_name']) {return show(0,'请填写用户名');}
        if (!$_POST['password']) {return show(0,'请填写密码');}
        $_POST['password'] = MD5($_POST['password']);

        $check = Validate::is($_POST['email'],'email');
        if (false === $check) {
            return show(0,'邮箱地址错误');
        }
        if (!isset($_POST['statu'])) {
            $_POST['statu'] =0;
        }else {
            $_POST['statu'] =1;
        }


        $file = request()->file('img');
        if ($file) {
            $filee = $this->imgUpload($file,'uploads/banner');
            if ($filee['error'] == 1) {
                $_POST['img'] = $filee['url'];
            }else {
                return show(0,$filee['errorlog']);
            }
        }else {
            unset($_POST['img']);
        }


        $Admin = new Admin($_POST);
        $result = $Admin->allowField(true)->save();
        if ($result) {
            return show(1,'添加成功');
        }else {
            return show(0,'添加失败');
        }
    }

    //后台账号修改
    public function admin_edit(){
        //角色加载
        $result = Db::name('AdminRole')->select();
        $this->assign('result', $result);
        //账户
        $admin = new Admin();
        $date = $admin->where('admin_id', $_GET['admin_id'])->find();
        //dump($date);
        $this->assign('date', $date);
        return $this->fetch();
    }
    //后台账号修改ajax
    public function admin_edit_ajax(){
        $admin_id = $_POST['admin_id'];
        unset($_POST['admin_id']);
        $file = request()->file('img');
        if ($file) {
            $filee = $this->imgUpload($file,'uploads/admin/touxiang');
            if ($filee['error'] == 1) {
                $_POST['img'] = $filee['url'];
            }else {
                return show(0,$filee['errorlog']);
            }
        }else {
            unset($_POST['img']);
        }

        if (!$_POST['user_name']) {return show(0,'请填写用户名');}
        if (!$_POST['password']) {unset($_POST['password']);}else {$_POST['password'] = MD5($_POST['password']);}
        if (!isset($_POST['statu'])) {
            $_POST['statu'] =0;
        }else {
            $_POST['statu'] =1;
        }
        $Admin = new Admin;
        $result = $Admin->allowField(true)->save($_POST, ['admin_id' => $admin_id]);
        if ($result) {
            return show(1,'修改成功');
        }else {
            return show(0,'修改失败');
        }
    }

    //后台账号删除
    public function admin_del(){
        $date = Db::name('Admin')->where('admin_id', $_GET['admin_id'])->delete();
        if ($date) {
            $this->success('删除成功', 'Aut/admin_list');
        }else {
            $this->error('删除失败','Aut/admin_list');
        }
    }


    /**
     * 后台角色列表
     * @return {[type] [description]
     */
    public function admin_role_list(){
        $result = Db::name('admin_role')->select();
        $this->assign('result', $result);
        return $this->fetch();
    }

    //后台角色添加
    public function admin_role_add(){
        //一级目录分类
        $result = Db::name('AdminPermission')->where('grade', 1)->select();
        $this->assign('result', $result);
        //二级目录分类
        $resulttow = Db::name('AdminPermission')->where('grade', 2)->select();
        $this->assign('resulttow', $resulttow);
        return $this->fetch();
    }
    public function admin_role_add_ajax(){
        if (!$_POST['role_name']) {return show(0,'请填写角色名');}
        if (!isset($_POST['act_list'])) {
            return show(0,'请选择权限');
        }else {
            $_POST['act_list']=implode(',',$_POST['act_list']);
        }
        if (isset($_POST['quanxuan'])) {
            $_POST['act_list']= 'root';
        }
        $AdminRole = new AdminRole($_POST);
        $result = $AdminRole->allowField(true)->save();
        if ($result) {
            return show(1,'添加成功');
        }else {
            return show(0,'添加失败');
        }
    }

    //后台角色修改
    public function admin_role_edit(){
        //一级目录分类
        $result = Db::name('AdminPermission')->where('grade', 1)->select();
        //var_dump($result);
        $this->assign('result', $result);
        //二级目录分类
        foreach ($result as $key => $value) {
            //  var_dump($value);die;
            $resulttow[$key] = Db::name('AdminPermission')->where('grade', 2)->where('module_id', $value['permission_id'])->select();
        }

        $this->assign('resulttow', $resulttow);
        //角色
        $date = Db::name('AdminRole')->where('role_id', $_GET['role_id'])->find();
        $date['act_list'] = explode(',',$date['act_list']);
        $this->assign('date', $date);

        return $this->fetch();
    }
    //ajax
    public function admin_role_edit_ajax(){
        if (!$_POST['role_name']) {return show(0,'请填写角色名');}
        if (!$_POST['act_list']) {
            return show(0,'请选择权限');
        }else {
            $_POST['act_list']=implode(',',$_POST['act_list']);
        }
        if (isset($_POST['quanxuan'])) {
            $_POST['act_list']= 'root';
        }

        $role_id = $_POST['role_id'];
        unset($_POST['role_id']);
        $AdminRole = new AdminRole();
        $result = $AdminRole->allowField(true)->save($_POST, ['role_id' => $role_id]);
        if ($result) {
            return show(1,'修改成功');
        }else {
            return show(0,'修改失败');
        }
    }
    //后台角色删除
    public function admin_role_del(){
        $AdminRole = new AdminRole;
        $date = $AdminRole->where('role_id', $_GET['role_id'])->delete();
        if ($date) {
            $this->success('删除成功', 'Aut/admin_role_list');
        }else {
            $this->error('删除失败','Aut/admin_role_list');
        }
    }

    /**
     *系统操作日志
     **/
    public function log_list(){
        $arr = array();
        $arre = array();
        if (isset($_GET['time']) || isset($_GET['time_end'])) {
            if ($_GET['time'] && $_GET['time_end'] && strtotime($_GET['time_end']) > strtotime($_GET['time']) ) {
                $arr['log_time'][0] = ['>',$_GET['time'] ];
                $arr['log_time'][1] = ['<',$_GET['time_end']];
                //var_dump($arr);
            }elseif (!$_GET['time_end'] && $_GET['time']) {
                $this->success('结束时间没有填写', 'Aut/log_list');
            }elseif (!$_GET['time'] && $_GET['time_end']) {
                $this->success('开始时间没有填写', 'Aut/log_list');
            }else if(strtotime($_GET['time_end']) < strtotime($_GET['time'])){
                $this->success('开始时间早于结束时间', 'Aut/log_list');
            }
        }

        if (isset($_GET['name']) && isset($_GET['time'])) {
            $arre['log_ip'] = ['like','%'.$_GET['name'].'%'];
            $arre['log_info'] = ['like','%'.$_GET['name'].'%' ];
            $arre['admin_id'] = ['like','%'.$_GET['name'].'%'];
        }
        $result = Db::name('admin_log')->where($arr)->whereOr($arre)->order('log_time', 'desc')->paginate(100);

        // 获取分页显示
        $page = $result->render();
        // 模板变量赋值
        $this->assign('page', $page);

        //var_dump($result);
        $this->assign('result', $result);
        return $this->fetch();
    }
}