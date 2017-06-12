<?php

namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Article extends Controller
{
    public function index(){
        $id = input('id');

        $article = Db::name('article')->where('id',$id)->find();
        Db::name('article')->where('id',$id)->setInc('click');
        //查询评论
        $comments = Db::name('comment')->where('article_id',$id)->where('state',1)->select();
        //查询分类
        $cates = Db::name('category')->select();
        //查看支付
        $pay = Db::name('user')->where('user_name',$article['author'])->field('allipay,weixin')->find();

        $this->assign([
            'article'=>$article,
            'comments' => $comments,
            'cates' => $cates,
            'pay' => $pay
        ]);
        return $this->fetch();
    }

    public function like(){
        $id = input('id');
        $like = Db::name('article')->where('id',$id)->field('like')->find();
        if(input('action') == 1){
            $result = Db::name('article')->where('id',$id)->update(['like'=>$like['like'] + 1]);
            if($result !== false){
                return ['state'=>1,'mes'=>'喜欢就好'];
            }else{
                return ['state' => 2,'msg'=>'操作失败'];
            }
        }else{
            $result = Db::name('article')->where('id',$id)->update(['like'=>$like['like'] + 1]);
            if($result !== false){
                return ['state'=>1,'mes'=>'不喜欢也没关系'];
            }else{
                return ['state' => 2,'msg'=>'取消失败,不喜欢都不行'];
            }
        }

    }


}