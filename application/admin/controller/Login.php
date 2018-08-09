<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/2
 * Time: 18:38
 */

namespace app\admin\controller;


use app\admin\model\Admin;
use think\captcha\Captcha;
use think\Controller;
use think\Db;

class Login extends Controller
{
    public function login(){
        return $this->fetch();
    }

    // 登录异步提交
    public function check(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $captcha = $_POST['captcha'];

        if (!trim($username)) {
            return show(0,'用户名不能为空');
        }
        if (!trim($password)) {
            return show(0,'密码不能为空');
        }
        if(!captcha_check($captcha)){
            return show(0,'验证码错误');
        }
        // 查询单个数据
        $ret = Admin::getByUserName($username);
        if (!$ret) {
            return show(0,'该用户不存在');
        }

        if ($ret['password']!=MD5($password)) {
            return show(0,'密码错误');
        }

        $cache['user_name']=$ret['user_name'];
        $cache['toke']=$ret['password'];
        $cache['role_id']=$ret['role_id'];
        $cache['img']=$ret['img'];
        $cache['admin_id']=$ret['admin_id'];

        session('adminUser',$cache);

        Db::table('admin')->where("user_name",$ret['user_name'])->update(['login_time'  => time(),'last_ip' => request()->ip(),]);
//        $this->adminLog('后台登录');
        return show(1,'登录成功');
    }

    // 退出登录
    public function loginout(){
        session('adminUser',null);
        $this->redirect('Login/login');
    }
}