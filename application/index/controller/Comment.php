<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/4
 * Time: 15:11
 */

namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;

class Comment extends Controller
{
    public function index(){
        if(request()->isPost()){
            $id = input('id');
            $user_id = Session::get('user_id');
            $allow_comment = Db::name('user')->where('id',$user_id)->field('allow_comment')->find();
            if(!session('user_name')){
                return ['state' => 3,'mes' => '请先登录'];
            }elseif ($allow_comment['allow_comment'] == 0){
                return ['state' => 2,'mes' => '您已经被禁言'];
            }else{
                $data = [
                    'article_id'=>$id,
                    'user_name'=>Session::get('user_name'),
                    'user_id' => $user_id,
                    'comment' => input('msg'),
                    'state' => 1,
                    'time' => date('Y-m-d H:i:s')
                ];
                //查询文章名
                $article_name = Db::name('article')->where('id',$id)->field('article_name')->find();
                $data['article_name'] = $article_name['article_name'];
                //查询用户头像
                $user_head = Db::name('user')->where('id',$user_id)->field('head')->find();
                $data['user_head'] = $user_head['head'];
                $result = Db::name('comment')->insert($data);
                $comment_num = Db::name('comment')->where('article_id',$id)->count();
                //更新article
                Db::name('article')->where('id',$id)->update(['comment_num'=>$comment_num]);
                if($result !== false){
                    return ['state' => 1,'mes'=>'感谢评论'];
                }else{
                    return ['state' => 2,'mes'=>'评论失败'];
                }
            }
        }
    }
}