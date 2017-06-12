<?php


namespace app\index\controller;
use think\Controller;
use think\Db;

class Search extends Controller
{
    public function index(){
        if(request()->isPost()){
            $keywords = input('keywords');
            if($keywords != ''){
                $articles = Db::name('article')->where('article_name','like','%'.$keywords.'%')->select();
                if(empty($articles)){
                    $articles = '';
                }
            }else{
                $articles = '';
            }

            $cates = Db::name('category')->select();

            $this->assign([
                'articles' => $articles,
                'cates' => $cates
            ]);
            return $this->fetch();
        }

    }
}