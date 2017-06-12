<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 16:28
 */

namespace app\admin\controller;
use think\Db;
use think\Session;
use app\common\controller\AdminBase;

class UserArticle extends AdminBase
{
    public function showlist(){
        $users = Db::name('article')->where('role','user')->select();
        $category = Db::name('category')->field('id,cate_name')->select();
        $this->assign([
            'user_articles' => $users,
            'cate'=>$category
        ]);
        return $this->fetch();

    }

    //待审核
    public function wait(){
        $id = input('id');
        //修改state
        $result = Db::name('article')->where('id',$id)->update(['state'=> 0]);
        if($result !== false){
            return ['state'=>1,'msg'=>'文章待审核成功'];
        }else{
            return ['state'=>0,'msg'=>'文章待审核失败'];
        }
    }

    //审核通过
    public function pass(){
        $id = input('id');
        //修改state
        $result = Db::name('article')->where('id',$id)->update(['state'=> 1]);
        if($result !== false){
            return ['state'=>1,'msg'=>'文章审核通过'];
        }else{
            return ['state'=>0,'msg'=>'文章审核不通过'];
        }
    }

    //从首页撤销
    public function drawback(){
        $id = input('id');
        //修改state
        $result = Db::name('article')->where('id',$id)->update(['home'=> 0]);
        if($result !== false){
            return ['state'=>1,'msg'=>'撤销文章成功'];
        }else{
            return ['state'=>0,'msg'=>'撤销文章失败'];
        }
    }

    //推荐到首页
    public function home(){
        $id = input('id');
        //修改state
        $result = Db::name('article')->where('id',$id)->update(['home'=> 1]);
        if($result !== false){
            return ['state'=>1,'msg'=>'推送文章成功'];
        }else{
            return ['state'=>0,'msg'=>'推送文章失败'];
        }
    }

    //文章分类
    public function cates(){
        $id = input('id');
        //查询文章类型
        $cate_id = Db::name('article')->where('id',$id)->field('cate_id')->find();
        $cates = Db::name('category')->select();
        $this->assign([
            'cates' =>$cates,
            'cate_id' =>$cate_id['cate_id'],
            'article_id'=>$id
        ]);
        return $this->fetch();
    }

    public function changecate(){
        if(request()->isPost()){
            $cate = input('c_id');
            $article = input('a_id');
            //修改类型
            $result = Db::name('article')->where('id',$article)->update(['cate_id'=>$cate]);
            if($result){
                return ['state'=>1,'mes'=>'修改成功'];
            }else{
                return ['state'=>2,'mes'=>'修改失败'];
            }
        }
    }
}