<?php


namespace app\index\controller;
use app\common\controller\HomeBase;
use think\Db;
use think\Session;


class Myhome extends HomeBase
{
    public function index(){
        //获取我的文章
        $user_id = Session::get('user_id');
        //查询分类
        $cates = Db::name('category')->select();
        $my_articles = Db::name('article')->where('user_id',$user_id)->field('id,article_name,author,des,cover,author_cover,comment_num,like,click,time,state')->order('time','desc')->select();
        $my_num = Db::name('article')->where('user_id',$user_id)->count();
        $my_collection = Db::name('collection')->where('user_id',$user_id)->field('article_id')->order('time','desc')->select();
        $collection_num = Db::name('collection')->where('user_id',$user_id)->field('article_id')->count();
        $collection = [];
        foreach ($my_collection as $k=>$v){
            $collection_article = Db::name('article')->where('id',$v['article_id'])->field('id,article_name,author,des,cover,author_cover,comment_num,like,click,time')->find();
            $collection[] = $collection_article;
        }

        $like = [];
        $my_like = Db::name('like')->where('user_id',$user_id)->order('time','desc')->select();
        $like_num = Db::name('like')->where('user_id',$user_id)->count();
        foreach ($my_like as $k=>$v){
            $like_article = Db::name('article')->where('id',$v['article_id'])->field('id,article_name,author,des,cover,author_cover,comment_num,like,click,time')->find();
            $like[] = $like_article;
        }

        //查询我的关注
        $focus = Db::name('focus')->where('user_id',$user_id)->order('time','desc')->select();
        $focus_num = Db::name('focus')->where('user_id',$user_id)->count();

        $this->assign([
            'articles'=>$my_articles,
            'collection' => $collection,
            'like' => $like,
            'my_num' => $my_num,
            'like_num' =>$like_num,
            'collection_num' => $collection_num,
            'focus_num' => $focus_num,
            'cates' =>$cates,
            'focus' => $focus

        ]);
        return $this->fetch();
    }

    //取消关注
    public function removefocus(){
        if(request()->isPost()){
            $id = input('id');
            //删除数据'
            $result = Db::name('focus')->where('id',$id)->delete();
            if($result){
                return ['state'=>1,'mes'=>'取消关注成功'];
            }else{
                return ['state'=>2,'mes'=>'取消关注失败'];
            }
        }
    }

    //取消喜欢
    public function removelike(){
        if(request()->isPost()){
            $id = input('id');
            $user_id = Session::get('user_id');
            $result = Db::name('like')->where('user_id',$user_id)->where('article_id',$id)->delete();
            if($result){
                return ['state'=>1,'mes'=>'取消成功'];
            }else{
                return ['state'=>2,'mes'=>'取消失败'];
            }
        }
    }

    //取消收藏
    public function removecollect(){
        if(request()->isPost()){
            $id = input('id');
            $user_id = Session::get('user_id');
            $result = Db::name('collection')->where('user_id',$user_id)->where('article_id',$id)->delete();
            if($result){
                return ['state'=>1,'mes'=>'取消成功'];
            }else{
                return ['state'=>2,'mes'=>'取消失败'];
            }
        }
    }
}