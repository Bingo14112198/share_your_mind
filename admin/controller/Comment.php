<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 14:50
 */

namespace app\admin\controller;

use think\Db;
use app\common\controller\AdminBase;
class Comment extends AdminBase
{
    public function showlist(){
        $comments = Db::name('comment')->select();
        $this->assign([
            'comments' => $comments
        ]);
        return $this->fetch();
    }

    //将用户禁言
    public function disable(){
        $id = input('id');
        //更新数据库
        $result = Db::name('user')->where('id',$id)->update(['allow_comment' => 0]);
        if($result !== false){
            return ['state'=> 1, 'msg'=>'禁言用户成功'];
        }else{
            return ['state' => 2,'msg' => '禁言用户失败'];
        }
    }

    //隐藏评论
    public function hide(){
        $id = input('id');
        //更新数据库
        $result = Db::name('comment')->where('id',$id)->update(['state' => 0]);
        if($result !== false){
            return ['state'=> 1, 'msg'=>'隐藏评论成功'];
        }else{
            return ['state' =>2,'msg' => '隐藏评论失败'];
        }
    }

    //隐藏评论
    public function show(){
        $id = input('id');
        //更新数据库
        $result = Db::name('comment')->where('id',$id)->update(['state' => 1]);
        if($result !== false){
            return ['state'=> 1, 'msg'=>'显示评论成功'];
        }else{
            return ['state' =>2,'msg' => '显示评论失败'];
        }
    }


    //删除评论
    public function del(){
        $id = input('id');
        //更新评论数
        $article_id = Db::name('comment')->where('id',$id)->find();
        $result = Db::name('comment')->where('id',$id)->delete();
        //查询文章中评论的数量
        $num = Db::name('comment')->where('article_id',$article_id['article_id'])->count();
        Db::name('article')->where('id',$article_id['article_id'])->update(['comment_num' => $num]);
        if($result !== false){
            return ['state'=> 1, 'msg'=>'删除评论成功'];
        }else{
            return ['state' => 2,'msg' => '删除评论失败'];
        }
    }
}