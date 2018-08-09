<?php
/**
 * Created by PhpStorm.
 * User: lvnini
 * Date: 2018/8/4
 * Time: 19:05
 */

namespace app\admin\controller;


use app\admin\model\Article;
use app\admin\model\Banner;
use app\admin\model\BannerItem;
use app\admin\model\System;

class Content extends BaseController
{
    //网站信息管理
    public function system_list(){
        $system = new System();
        $results = $system->select();
        $this->assign('results', $results);
        return $this->fetch();
    }

    //网站信息管理 异步
    public function system_list_ajax(){
        $file = request()->file('2');
        if ($file) {
            $filee = $this->imgUpload($file,'uploads/banner');
            if ($filee['error'] == 1) {
                $_POST['2'] = $filee['url'];
            }else {
                return show(0,$filee['errorlog']);
            }
        }else {
            unset($_POST['2']);
        }

        $system = new System;
        $i = 0;
        foreach ($_POST as $key => $value) {
            $list[$i]['id'] = $key;
            $list[$i]['value'] = $value;
            $i++;
        }
        $results = $system->isUpdate()->saveAll($list);
        if ($results) {
            return show(1,'修改成功');
        }else {
            return show(0,'修改失败');
        }
    }


    public function banner_list(){
        $banner = new Banner();
        $results = $banner -> select();
        $this->assign('results', $results);
        return $this->fetch();
    }

    public function banner_edit(){
        $banner = new Banner();
        $result = $banner->with('items')->where('id',$_GET['id'])->find();
        $this->assign('result', $result);
        return $this->fetch();
    }

    public function banner_edit_ajax(){
        $id = $_POST['id'];
        unset($_POST['id']);
        $banner = new Banner();
        $results = $banner->where('id',$id)->update($_POST);
        if ($results) {
            return show(1,'修改成功');
        }else {
            return show(0,'修改失败');
        }
    }

    public function banner_item_add(){
        return $this->fetch();
    }

    public function banner_item_add_ajax(){
        $file = request()->file('img_url');
        if ($file) {
            $filee = $this->imgUpload($file,'uploads/banner');
            if ($filee['error'] == 1) {
                $_POST['img_url'] = $filee['url'];
            }else {
                return show(0,$filee['errorlog']);
            }
        }else {
            unset($_POST['img_url']);
        }
        $banneritem = new BannerItem();
        $results = $banneritem->isUpdate(false)->save($_POST);
        if ($results) {
            return show(1,'添加成功');
        }else {
            return show(0,'添加失败');
        }
    }

    public function banner_item_edit(){
        $banneritem = new BannerItem();
        $id = $_GET['id'];
        $results = $banneritem->where('id', $id)->find();
        $this->assign('results', $results);
        return $this->fetch();
    }

    public function banner_item_edit_ajax(){
        $file = request()->file('img_url');
        if ($file) {
            $filee = $this->imgUpload($file,'uploads/banner');
            if ($filee['error'] == 1) {
                $_POST['img_url'] = $filee['url'];
            }else {
                return show(0,$filee['errorlog']);
            }
        }else {
            unset($_POST['img_url']);
        }
        $banneritem = new BannerItem();
        $results = $banneritem->update($_POST);
        if ($results) {
            return show(1,'修改成功');
        }else {
            return show(0,'修改失败');
        }

    }

    public function info_list(){
        $article = new Article();
        $results = $article->select();
        $this->assign('results', $results);
        return $this->fetch();
    }

    public function info_add(){

        return $this->fetch();
    }

    public function info_add_ajax(){
        $file = request()->file('thumb');
        if ($file) {
            $filee = $this->imgUpload($file,'uploads/banner');
            if ($filee['error'] == 1) {
                $_POST['thumb'] = $filee['url'];
            }else {
                return show(0,$filee['errorlog']);
            }
        }else {
            unset($_POST['thumb']);
        }
        $article = new Article();
        $results = $article->isUpdate(false)->save($_POST);
        if ($results) {
            return show(1,'添加成功');
        }else {
            return show(0,'添加失败');
        }
    }

    public function info_edit(){
        $article = new Article();
        $results = $article->where('article_id', $_GET['id'])->find();
        $this->assign('results', $results);
        return $this->fetch();
    }

    public function info_edit_ajax(){
        $file = request()->file('thumb');
        if ($file) {
            $filee = $this->imgUpload($file,'uploads/banner');
            if ($filee['error'] == 1) {
                $_POST['thumb'] = $filee['url'];
            }else {
                return show(0,$filee['errorlog']);
            }
        }else {
            unset($_POST['thumb']);
        }
        $article = new Article();
        $results = $article->allowField(true)->save($_POST,['article_id', $_POST['article_id']]);
        if ($results) {
            return show(1,'修改成功');
        }else {
            return show(0,'修改失败');
        }
    }

    public function info_del(){
        $article = new Article;
        $date = $article->where('article_id', $_GET['id'])->delete();
        if ($date) {
            $this->success('删除成功', 'content/info_list');
        }else {
            $this->error('删除失败','content/info_list');
        }
    }



}