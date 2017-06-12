<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/7
 * Time: 16:14
 */

namespace app\index\controller;
use think\Controller;
use think\Db;

class Cates extends Controller
{
    public function index(){
        $id = input('id');
        //查询数据库
        $articles = Db::name('article')->where('cate_id',$id)->select();
        if(empty($articles)){
            $article = '';
        }else{
            $article = $articles;
        }
        $cates = Db::name('category')->select();
        $this->assign([
            'cates'=>$cates,
            'articles' => $article
        ]);
        return $this->fetch();
    }

}