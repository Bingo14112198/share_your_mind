<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
        $articles = Db::name('article')->where('home',1)->where('state',1)->order('click','desc')->paginate(10);
        //查询分类
        $cates = Db::name('category')->select();
        $this->assign([
            'article' => $articles,
            'cates' => $cates
        ]);
        return $this->fetch();
    }
}
