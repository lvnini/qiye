<?php
namespace app\index\controller;

use app\index\model\Article;
use app\index\model\Banner;

class Index extends BaseController
{   

    public function index()
    {
        $banner = new Banner();
        $result = $banner->with('items')->where('id','=','1')->find();
        $this->assign('result', $result);
        return $this->fetch();
    }

    //文章列表页
    public function info()
    {   
        // 获取banner
        $banner = new Banner();
        $result = $banner->with('items')->where('id','=','2')->find();
        $this->assign('result', $result);

        //获取最新文章
        $article = new Article();
        $infoNew = $article
            ->limit(3)
            ->order('create_time', 'desc')
            ->select();
        // var_dump($infoNew->toArray());
        $this->assign('infoNew', $infoNew);
        
        foreach ($infoNew as $key => $value) {
            $exclude[] = $value['article_id'];
        }
        $excludes = implode(',',$exclude);
        // var_dump($excludes);die;

        $list = $article->where('article_id','not in',$excludes)->order('create_time', 'desc')->paginate(5);
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);

        return $this->fetch();
    }

    //文章详情页
    public function info_detail()
    {
        //获取最新文章
        $article = new Article();
        $infoNew = $article
            ->limit(3)
            ->order('create_time', 'desc')
            ->select();
        // var_dump($infoNew->toArray());
        $this->assign('infoNew', $infoNew);

        $result = $article->where('article_id','=',$_GET['id'])->find();
        // var_dump($result->toArray());
        $this->assign('result', $result);
        return $this->fetch();
    }

    //关于我们
    public function about()
    {   
        // 获取banner
        $banner = new Banner();
        $result = $banner->with('items')->where('id','=','3')->find();
        $this->assign('result', $result);

        return $this->fetch();
    }


    //产品展管
    public function product()
    {   
        // 获取banner
        $banner = new Banner();
        $result = $banner->with('items')->where('id','=','4')->find();
        $this->assign('result', $result);

        return $this->fetch();
    }

    //专业馆
    public function specialty()
    {   
        // 获取banner
        $banner = new Banner();
        $result = $banner->with('items')->where('id','=','5')->find();
        $this->assign('result', $result);

        return $this->fetch();
    }

    //健康普及馆
    public function fitness()
    {   
        // 获取banner
        $banner = new Banner();
        $result = $banner->with('items')->where('id','=','6')->find();
        $this->assign('result', $result);

        return $this->fetch();
    }

    //客服中心
    public function service()
    {   
        // 获取banner
        $banner = new Banner();
        $result = $banner->with('items')->where('id','=','1')->find();
        $this->assign('result', $result);

        return $this->fetch();
    }
    
    
}
